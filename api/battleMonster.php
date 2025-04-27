<?php
session_start(); // Start the session
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors', 1);
require_once('../controller/Database.php');
require_once('../controller/Player.php');
require_once('../controller/Monster.php');
require_once('../controller/authCheck.php');
require_once '../controller/Battle.php'; // Adjust the path as needed


// Check the connection
$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['playerId']) && isset($_GET['monsterId']) && ($_GET['playerId']) == $playerId) {

        $playerId = intval($_GET['playerId']);
        $monsterId = intval($_GET['monsterId']);

        $player = new Player($conn, $playerId);
        $monster = new Monster($conn, $monsterId);

        $battleLog = [];

        $playerCurrentHp = $player->getCurrentHp();
        $monsterCurrentHp = $monster->getMaxHp();
        $monsterMaxHp = $monster->getMaxHp();

        if ($playerCurrentHp <= 0) {
            $battleLog['result'] = "Battling when you're dead, what were you thinking?";
            $battleLog['gold_gain'] = 0;
            $battleLog['exp_gain'] = 0;
            $battleLog['battle'] = "You lose.";
            echo json_encode($battleLog);
            exit();
        }
        if ($player->getStamina() <= 0) {
            $battleLog['result'] = "You don't have enough stamina required to fight";
            $battleLog['gold_gain'] = 0;
            $battleLog['exp_gain'] = 0;
            $battleLog['battle'] = "You lose.";
            echo json_encode($battleLog);
            exit();
        } else {
            //Deduct the cost of the stamina cost for battle from playe
            $staminaCost = 2;
            $player->updateStamina($staminaCost);
            $player->setPlayerPveBattleCount();
        }

        $playerSpeed = $player->getSpeed();
        $monsterSpeed = $monster->getSpeed();

        $firstTurn = $playerSpeed >= $monsterSpeed ? $player : $monster;
        $secondTurn = $firstTurn === $player ? $monster : $player;

        // Helper function for critical and lifesteal
        function calculateAttack($attacker, $target) {
            $isPlayer = get_class($attacker) === 'Player';
            // Always use getAttack for both Player and Monster
            if (method_exists($attacker, 'getAttack')) {
                $baseDamage = $attacker->getAttack($target);
            } else {
                $baseDamage = 0;
            }

            // defence scaling: diminishing returns, but never 0 damage
            // New formula: max 50% reduction, always at least 50% of baseDamage gets through
            $defence = method_exists($target, 'getDefence') ? $target->getDefence() : 0;
            $defenceFactor = min($defence / ($defence + 100), 0.5); // max 50% reduction
            $damageAfterDefence = $baseDamage * (1 - $defenceFactor);

            // Level difference factor
            $attackerLevel = method_exists($attacker, 'getLevel') ? $attacker->getLevel() : 1;
            $targetLevel = method_exists($target, 'getLevel') ? $target->getLevel() : 1;
            $levelDiff = $targetLevel - $attackerLevel;
            $levelFactor = 1 - 0.02 * max(0, $levelDiff); // Each level higher reduces damage by 2%
            $levelFactor = max(0, $levelFactor); // Clamp to 0 minimum

            $finalDamage = $damageAfterDefence * $levelFactor;

            // Flat random adjustment: -2 to +2
            $flatVariance = mt_rand(-2, 2);
            $finalDamageWithVariance = $finalDamage + $flatVariance;

            // Only apply crit if attacker is a Player
            $critChance = $isPlayer && method_exists($attacker, 'getPlayerItemCritChance') ? $attacker->getPlayerItemCritChance() : 0;
            $critMulti  = $isPlayer && method_exists($attacker, 'getPlayerItemCritMulti') ? $attacker->getPlayerItemCritMulti() : 1;
            $isCrit = false;
            $critApplied = 1;
            $finalCritDamage = $finalDamageWithVariance;
            if ($critChance > 0 && rand(1, 100) <= $critChance) {
                if($critMulti == 0) {
                    $critMulti = 10; // Default crit percent if not set (10%)
                }
                $finalCritDamage = $finalDamageWithVariance * (1 + ($critMulti / 100));
                $isCrit = true;
                $critApplied = $critMulti;
            }

            // Life steal: applies to all hits, but only a chance to leech (10% flat chance)
            $lifeSteal = $isPlayer && method_exists($attacker, 'getPlayerItemLifesteal') ? $attacker->getPlayerItemLifesteal() : 0;
            $lifeStealChance = 10; // Flat 10% chance
            $lifeStealAmount = 0;
            if ($lifeSteal > 0 && $finalCritDamage > 0 && rand(1, 100) <= $lifeStealChance) {
                $lifeStealAmount = intval($finalCritDamage * ($lifeSteal / 100));
            }

            // Guarantee minimum 1 damage if baseDamage > 0, after all modifiers
            if ($baseDamage > 0) {
                $finalCritDamage = max(1, intval(ceil($finalCritDamage)));
            } else {
                $finalCritDamage = 0;
            }

            // Calculation details string
            $calculation_details = 
                "Calculation: Base={$baseDamage}, Defence={$defence}, DefenceFactor=" . round($defenceFactor, 3) .
                ", AfterDefence=" . round($damageAfterDefence, 2) .
                ", LevelFactor=" . round($levelFactor, 3) .
                ", AfterLevel=" . round($finalDamage, 2) .
                ", Variance={$flatVariance}, AfterVariance=" . round($finalDamageWithVariance, 2) .
                ", CritMulti=" . round($critApplied, 2) .
                ", AfterCrit=" . round($finalCritDamage, 2) .
                ", Lifesteal={$lifeSteal}%, LifestealAmt={$lifeStealAmount}";

            return [
                'damage' => $finalCritDamage,
                'isCrit' => $isCrit,
                'lifeSteal' => $lifeStealAmount,
                'calculation_details' => $calculation_details
            ];
        }

        while ($playerCurrentHp > 0 && $monsterCurrentHp > 0) {

            $attacker = $firstTurn;
            $target = $firstTurn === $player ? $monster : $player;

            // Miss logic: 5% chance if attacker has less speed than target
            $attackerSpeed = $attacker->getSpeed();
            $targetSpeed = $target->getSpeed();
            $missed = false;
            if ($attackerSpeed < $targetSpeed && mt_rand(1, 100) <= 5) {
                $battleLog['battle'][] = "{$attacker->getName()} missed their attack on {$target->getName()}!";
                $missed = true;
            }

            if (!$missed) {
                // Debug: log monster/player base attack before calculation
                if ($attacker instanceof Monster) {
                    $battleLog['battle'][] = "[DEBUG] Monster base attack: " . $attacker->getAttack($target);
                } elseif ($attacker instanceof Player) {
                    $battleLog['battle'][] = "[DEBUG] Player base attack: " . $attacker->getAttack($target);
                }

                $result = calculateAttack($attacker, $target);
                $damage = $result['damage'];

                if ($target === $monster) {
                    $monsterCurrentHp -= $damage;
                    // Life steal for player
                    if ($result['lifeSteal'] > 0 && $attacker === $player) {
                        $playerCurrentHp = min($playerCurrentHp + $result['lifeSteal'], $player->getMaxHp());
                    }
                } else {
                    $playerCurrentHp -= $damage;
                    // Life steal for monster (if ever implemented)
                    if ($result['lifeSteal'] > 0 && $attacker === $monster) {
                        $monsterCurrentHp = min($monsterCurrentHp + $result['lifeSteal'], $monster->getMaxHp());
                    }
                }

                $critMsg = $result['isCrit'] ? " (CRITICAL HIT!)" : "";
                $lsMsg = $result['lifeSteal'] > 0 ? " (Life Steal: +{$result['lifeSteal']} HP)" : "";
                $battleLog['battle'][] = "{$attacker->getName()} does {$damage} damage to {$target->getName()}{$critMsg}{$lsMsg} (Player: {$playerCurrentHp}/{$player->getMaxHp()} HP, Monster: {$monsterCurrentHp}/{$monsterMaxHp} HP)";
                $battleLog['battle'][] = "[DEBUG] " . $result['calculation_details'];
            }

            if ($playerCurrentHp <= 0 || $monsterCurrentHp <= 0)
                break;

            // Speed-based consecutive attack: 10% chance if faster
            if ($playerSpeed > $monsterSpeed && $firstTurn === $player && rand(1, 100) <= 2) {
                $battleLog['battle'][] = "{$player->getName()} attacks consecutively due to speed advantage!";
                continue;
            }
            if ($monsterSpeed > $playerSpeed && $firstTurn === $monster && rand(1, 100) <= 2) {
                $battleLog['battle'][] = "{$monster->getName()} attacks consecutively due to speed advantage!";
                continue;
            }

            $attacker = $secondTurn;
            $target = $secondTurn === $player ? $monster : $player;

            // Miss logic for second turn
            $attackerSpeed = $attacker->getSpeed();
            $targetSpeed = $target->getSpeed();
            $missed = false;
            if ($attackerSpeed < $targetSpeed && mt_rand(1, 100) <= 5) {
                $battleLog['battle'][] = "{$attacker->getName()} missed their attack on {$target->getName()}!";
                $missed = true;
            }

            if (!$missed) {
                $result = calculateAttack($attacker, $target);
                $damage = $result['damage'];

                if ($target === $monster) {
                    $monsterCurrentHp -= $damage;
                    if ($result['lifeSteal'] > 0 && $attacker === $player) {
                        $playerCurrentHp = min($playerCurrentHp + $result['lifeSteal'], $player->getMaxHp());
                    }
                } else {
                    $playerCurrentHp -= $damage;
                    if ($result['lifeSteal'] > 0 && $attacker === $monster) {
                        $monsterCurrentHp = min($monsterCurrentHp + $result['lifeSteal'], $monster->getMaxHp());
                    }
                }

                $critMsg = $result['isCrit'] ? " (CRITICAL HIT!)" : "";
                $lsMsg = $result['lifeSteal'] > 0 ? " (Life Steal: +{$result['lifeSteal']} HP)" : "";
                $battleLog['battle'][] = "{$attacker->getName()} does {$damage} damage to {$target->getName()}{$critMsg}{$lsMsg} (Player: {$playerCurrentHp}/{$player->getMaxHp()} HP, Monster: {$monsterCurrentHp}/{$monsterMaxHp} HP)";
                $battleLog['battle'][] = "[DEBUG] " . $result['calculation_details'];
            }
        }

        if ($playerCurrentHp <= 0) {
            $battleLog['outcome'] = json_encode($battleLog['battle']);
            $battleLog['result'] = "{$player->getName()} has been defeated...";
            $battleLog['victory'] = 0;
            updateBattleHistory($conn, $playerId, null, $monsterId, $battleLog['result'], $battleLog['outcome'], $battleLog['victory'], 0, 0);
            $player->updatePlayerBattleReward($playerCurrentHp, 0, 0);
        } elseif ($monsterCurrentHp <= 0) {
            $expReward = $monster->getExp();
            $goldReward = $monster->getGold();
            $battleLog['outcome'] = json_encode($battleLog['battle']);
            $battleLog['result'] = "{$player->getName()} wins the battle against " . $monster->getName();
            $battleLog['victory'] = 1;
            $battleLog['exp'] = $monster->getExp();
            $battleLog['gold'] = $monster->getGold();

            updateBattleHistory($conn, $playerId, null, $monsterId, $battleLog['result'], $battleLog['outcome'], $battleLog['victory'], $expReward, $goldReward);
            // this must occur before update player, as this will update player exp first.

            // Check if player has leveled up
            $battleLog['levelup'] = $player->checkLevelUp($monster->getExp()) ? true : false;
            $player->updatePlayerBattleReward($playerCurrentHp, $goldReward, $expReward);
            $player->setPlayerPveBattleWinCount();

            // Handle item drops
            $itemsDropped = handleItemDrops($conn, $playerId, $monsterId);
            $battleLog['items_dropped'] = $itemsDropped;

            // Add loot message
            if (!empty($itemsDropped)) {
                $lootMessage = "You have received the following items: ";
                $worldEventTriggered = false;

                foreach ($itemsDropped as $item) {
                    if ($item) {
                        $lootMessage .= "{$item['name']} (x{$item['quantity']}), ";

                        // Trigger world event if rarity >= 0.1 (0.1%)
                        if (
                            !$worldEventTriggered &&
                            isset($item['rarity']) &&
                            is_numeric($item['rarity']) &&
                            floatval($item['rarity']) >= 0.1
                        ) {
                            // Always treat rarity as float, e.g. 90 = 90%, 0.1 = 0.1%
                            $rarityFloat = floatval($item['rarity']);
                            // Roll out of 1000 for 0.1% precision
                            if (mt_rand(1, 1000) <= ($rarityFloat * 10)) {
                                $rarity_name = $player->getItemRarity($item['rarity']);
                                $worldEvent = "{$player->getName()} has looted a {$rarity_name} {$item['name']}!";
                                updateWorldEvent($conn, $playerId, $worldEvent);
                                $worldEventTriggered = true;
                            }
                        }
                    }
                }
            }

            // --- Boss defeat tracking ---
            $area_id = $player->getPlayerAreaId(); // or getAreaId()
            $areaQuery = $conn->prepare("SELECT max_level FROM areas WHERE id = ?");
            $areaQuery->bind_param('i', $area_id);
            $areaQuery->execute();
            $areaResult = $areaQuery->get_result();
            $areaRow = $areaResult->fetch_assoc();
            // if the monster level is equal to the max level of the area, it is a boss
            if ($areaRow && $monster->getLevel() == $areaRow['max_level']) {
                // Use Player method to mark boss as defeated
                $player->setBossDefeated($area_id);
                $battleLog['boss_defeated'] = true;
                $worldEvent = "{$player->getName()} has defeated the boss {$monster->getName()}!";
                updateWorldEvent($conn, $playerId, $worldEvent);
            }
            
            // --- end boss defeat tracking ---
        } else {
            $battleLog['loot_message'] = "No items dropped.";
        }

        echo json_encode($battleLog);
    } else {
        echo json_encode(array("status" => "error", "message" => "Error: You are unauthorised :)"));
    }
}
?>