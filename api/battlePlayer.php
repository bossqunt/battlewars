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
            $battleLog['result'] = "You're lucky, your opponent is already dead.. ";
            $battleLog['victory'] = 1;
            $battleLog['battle'] = "The tile is yours for the taking..";
            echo json_encode($battleLog);
            exit();
        }
        if($player->getStamina() <= 0) {
            $battleLog['result'] = "You don't enough stamina required to fight";
            $battleLog['battle'] = "You don't enough stamina required to fight.";
            echo json_encode($battleLog);
            exit();
        } else {
            //Deduct the cost of the stamina cost for battle from playe
            $staminaCost = 2;
            $player->updateStamina($staminaCost);
        }

        $playerSpeed = $player->getSpeed();
        $opponentSpeed = $opponent->getSpeed();

        $firstTurn = $playerSpeed >= $opponentSpeed ? $player : $opponent;
        $secondTurn = $firstTurn === $player ? $opponent : $opponent;
               

        while ($playerCurrentHp > 0 && $opponentCurrentHp > 0) {
           
            $attacker = $firstTurn;
            $target = $firstTurn === $player ? $opponent : $player;
            $damage = calculateDamage($attacker, $target);

            if ($target === $opponent) {
                $opponentCurrentHp -= $damage;
            } else {
                $playerCurrentHp -= $damage;
            }

            $battleLog['battle'][] = "{$attacker->getName()} does {$damage} damage to {$target->getName()} (Player: {$playerCurrentHp}/{$player->getMaxHp()} HP, Opponent: {$opponentCurrentHp}/{$opponentMaxHp} HP)";

            if ($playerCurrentHp <= 0 || $opponentCurrentHp <= 0) break;

            if (rollDice(6) > 4 && $firstTurn === $player) continue; // Player's chance to attack consecutively

            $attacker = $secondTurn;
            $target = $secondTurn === $player ? $opponent : $player;
            $damage = calculateDamage($attacker, $target);

            if ($target === $opponent) {
                $opponentCurrentHp -= $damage;
            } else {
                $playerCurrentHp -= $damage;
            }

            $battleLog['battle'][] = "{$attacker->getName()} does {$damage} damage to {$target->getName()} (Player: {$playerCurrentHp}/{$player->getMaxHp()} HP, opponent: {$opponentCurrentHp}/{$opponentMaxHp} HP)";
        }

        if ($playerCurrentHp <= 0) {
            $battleLog['outcome'] = json_encode($battleLog['battle']);
            $battleLog['result'] = "{$player->getName()} has been defeated...";
            $battleLog['victory'] = 0;
            $playerWon = 0;
            updateBattleHistory($conn, $playerId, $opponentId, null, $battleLog['result'], $battleLog['outcome'], 0, 0, $battleLog['victory'] = 0);
            $player->updatePlayerBattleReward($playerCurrentHp, 0, 0);
        } elseif ($opponentCurrentHp <= 0) {
            $battleLog['outcome'] = json_encode($battleLog['battle']);
            $battleLog['result'] = "{$player->getName()} wins the battle against " . $opponent->getName();
            $battleLog['victory'] = 1;
            $battleLog['exp'] = 'you received no exp';
            $battleLog['gold'] = 'you received no gold';
            $battleLog['loot_message'] = "You own ($x, $y) in area $areaid!";
            updateBattleHistory($conn, $playerId, $opponentId, null, $battleLog['result'], $battleLog['outcome'], $battleLog['victory']);
            $battleLog['world_event'] = "{$player->getName()} has taken ownership from {$opponent->getName()} at ($x, $y) in area $areaid!";
            updateWorldEvent($conn, $playerId, $battleLog['world_event']);
            //takeOwnership($conn, $playerId, $opponentId, $areaid, $x, $y);
            // adjust players final HP
            $player->updatePlayerBattleReward($playerCurrentHp);
            $player->setCurrentAreaAsOwner($areaid, $x, $y);
            // comment this line for player testing.. this will update opponents HP to 0
            //$opponent->updatePlayerBattleReward($opponentCurrentHp);
        }

        echo json_encode($battleLog);
    } else {
        echo json_encode(array("status" => "error", "message" => "Error: You are unauthorised :)" ));
    }
}
?>

