<?php
session_start(); // Start the session
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors', 1);
require_once('../controller/Database.php');
require_once('../controller/Player.php');
require_once('../controller/authCheck.php');
require_once('../controller/Battle.php');

// Check the connection
$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['playerId'], $input['opponentId']) && $input['playerId'] == $playerId) {

        $playerId = intval($input['playerId']);
        $opponentId = intval($input['opponentId']);
        $areaid = intval($input['area_id']);
        $x = intval($input['x']);
        $y = intval($input['y']);

        $player = new Player($conn, $playerId);
        $opponent = new Player($conn, $opponentId);

        $battleLog = [];

        $playerCurrentHp = $player->getCurrentHp();
        $opponentCurrentHp = $opponent->getCurrentHp();
        $opponentMaxHp = $opponent->getMaxHp();

        if($playerCurrentHp <= 0) {
            $battleLog['result'] = "Battling when you're dead, what were you thinking?";
            $battleLog['victory'] = 0;
            $battleLog['battle'] = "You're an idiot.";
            echo json_encode($battleLog);
            exit();
        }
        if($opponentCurrentHp <= 0) {
            $player->setPlayerPvpBattleCount();
            $player->setPlayerPvpBattleWinCount();
            $battleLog['result'] = "You're lucky, your opponent is already dead.. ";
            $battleLog['victory'] = 1;
            $battleLog['battle'] = "The tile is yours for the taking..";
            echo json_encode($battleLog);
            $player->setCurrentAreaOwnerAsPlayer($areaid, $x, $y);         
            $battleLog['world_event'] = "{$player->getName()} has killed {$opponent->getName()} and taken ownership of grid ($x, $y) in area $areaid!";
            updateWorldEvent($conn, $playerId, $battleLog['world_event']);
            exit();
        }
        if($player->getStamina() <= 0) {
            $battleLog['result'] = "You don't have enough stamina to fight";
            $battleLog['battle'] = "You don't have enough stamina to fight.";
            echo json_encode($battleLog);
            exit();
        } else {
            //Deduct the cost of the stamina cost for battle from playe
            $staminaCost = 2;
            $player->updateStamina($staminaCost);
        }

        $playerSpeed = $player->getSpeed();
        $opponentSpeed = $opponent->getSpeed();

        // Fix turn order assignment
        if ($playerSpeed >= $opponentSpeed) {
            $firstTurn = $player;
            $secondTurn = $opponent;
        } else {
            $firstTurn = $opponent;
            $secondTurn = $player;
        }

        while ($playerCurrentHp > 0 && $opponentCurrentHp > 0) {
            // First attacker's turn
            $attacker = $firstTurn;
            $target = $secondTurn;

            // Miss logic: 5% chance if attacker has less speed than target
            $attackerSpeed = $attacker->getSpeed();
            $targetSpeed = $target->getSpeed();
            $missed = false;
            if ($attackerSpeed < $targetSpeed && mt_rand(1, 100) <= 5) {
                $battleLog['battle'][] = "{$attacker->getName()} missed their attack on {$target->getName()}!";
                $missed = true;
            }

            if (!$missed) {
                $damage = calculateVariableDamage($attacker, $target);

                if ($target === $opponent) {
                    $opponentCurrentHp -= $damage;
                } else {
                    $playerCurrentHp -= $damage;
                }

                $battleLog['battle'][] = "{$attacker->getName()} does {$damage} damage to {$target->getName()} (Player: {$playerCurrentHp}/{$player->getMaxHp()} HP, Opponent: {$opponentCurrentHp}/{$opponentMaxHp} HP)";
            }

            if ($playerCurrentHp <= 0 || $opponentCurrentHp <= 0) break;

            // Optional: Player's chance to attack consecutively (if player is firstTurn)
            if ($firstTurn === $player && rollDice(6) > 4) {
                continue;
            }

            // Second attacker's turn
            $attacker = $secondTurn;
            $target = $firstTurn;

            // Miss logic for second turn
            $attackerSpeed = $attacker->getSpeed();
            $targetSpeed = $target->getSpeed();
            $missed = false;
            if ($attackerSpeed < $targetSpeed && mt_rand(1, 100) <= 5) {
                $battleLog['battle'][] = "{$attacker->getName()} missed their attack on {$target->getName()}!";
                $missed = true;
            }

            if (!$missed) {
                $damage = calculateVariableDamage($attacker, $target);

                if ($target === $opponent) {
                    $opponentCurrentHp -= $damage;
                } else {
                    $playerCurrentHp -= $damage;
                }

                $battleLog['battle'][] = "{$attacker->getName()} does {$damage} damage to {$target->getName()} (Player: {$playerCurrentHp}/{$player->getMaxHp()} HP, Opponent: {$opponentCurrentHp}/{$opponentMaxHp} HP)";
            }
        }

        if ($playerCurrentHp <= 0) {
            $battleLog['outcome'] = json_encode($battleLog['battle']);
            $battleLog['result'] = "{$player->getName()} has been defeated...";
            $battleLog['victory'] = 0;
            $playerWon = 0;
            $player->updatePlayerBattleReward($playerCurrentHp, 0, 0);
            $player->setPlayerPvpBattleCount();
            $opponent->updatePlayerBattleReward($playerCurrentHp,0,0);

            updateBattleHistory($conn, $playerId, $opponentId, null, $battleLog['result'], $battleLog['outcome'], 0, 0, $battleLog['victory'] = 0);

        } elseif ($opponentCurrentHp <= 0) {
            $battleLog['outcome'] = json_encode($battleLog['battle']);
            $battleLog['result'] = "{$player->getName()} wins the battle against " . $opponent->getName();
            $battleLog['victory'] = 1;
            $battleLog['exp'] = 'you received no exp';
            $battleLog['gold'] = 'you received no gold';
            $battleLog['loot_message'] = "You own ($x, $y) in area $areaid!";
            $battleLog['world_event'] = "{$player->getName()} has killed {$opponent->getName()} and taken ownership of grid ($x, $y) in area $areaid!";
            $player->setPlayerPvpBattleCount();
            $player->setPlayerPvpBattleWinCount();
            $player->updatePlayerBattleReward($playerCurrentHp);
            $player->setCurrentAreaOwnerAsPlayer($areaid, $x, $y);
            $opponent->updatePlayerBattleReward($opponentCurrentHp);

            updateBattleHistory($conn, $playerId, $opponentId, null, $battleLog['result'], $battleLog['outcome'], $battleLog['victory']);
            updateWorldEvent($conn, $playerId, $battleLog['world_event']);
        }
        echo json_encode($battleLog);
    } else {
        echo json_encode(array("status" => "error", "message" => "Error: You are unauthorised :)" ));
    }
}

function calculateVariableDamage($attacker, $target) {
    $baseDamage = calculateDamage($attacker, $target);
    // Flat random adjustment: -2 to +2
    $flatVariance = mt_rand(-2, 2);
    $damage = $baseDamage + $flatVariance;
    // Minimum 1 if baseDamage > 0
    return $baseDamage > 0 ? max(1, $damage) : 0;
}
?>

