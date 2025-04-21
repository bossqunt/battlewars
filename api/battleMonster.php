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

// Function to handle item drops
function handleItemDrops($conn, $playerId, $monsterId)
{
    $itemsDropped = [];

    // Fetch rarity tiers
    $rarityStmt = $conn->query("SELECT * FROM item_rarities");
    $rarities = [];
    while ($rarity = $rarityStmt->fetch_assoc()) {
        $rarities[] = $rarity;

    }

    // Fetch item drops
    $stmt = $conn->prepare('SELECT item_id, drop_chance FROM monster_item_drops WHERE monster_id = ?');
    $stmt->bind_param('i', $monsterId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $dropChance = $row['drop_chance'];
        $itemId = $row['item_id'];

        if (rollDice(100) <= $dropChance) {

            // Fetch item base stats
            $itemStmt = $conn->prepare('SELECT * FROM items WHERE id = ?');
            $itemStmt->bind_param('i', $itemId);
            $itemStmt->execute();
            $item = $itemStmt->get_result()->fetch_assoc();

            // Roll rarity
            $rolledRarity = rollRarity($rarities);

            // Fetch and apply rarity modifiers
            $modStmt = $conn->prepare("SELECT * FROM item_rarity_modifiers WHERE rarity_id = ?");
            $modStmt->bind_param('i', $rolledRarity['id']);
            $modStmt->execute();
            $mods = $modStmt->get_result();

            $modifiedStats = applyModifiers($item, $mods);


            // Insert into inventory
            $insertStmt = $conn->prepare(
                'INSERT INTO player_inventory 
                (player_id, item_id, quantity, rarity, attack, defense, crit_chance, crit_multi, life_steal, armor, speed, health, stamina) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );

            $insertStmt->bind_param(
                'iiiiiiiiiiiii',
                $playerId,
                $itemId,
                $item['quantity'],
                $rolledRarity['id'],
                $modifiedStats['attack'],
                $modifiedStats['defense'],
                $modifiedStats['crit_chance'],
                $modifiedStats['crit_multi'],
                $modifiedStats['life_steal'],
                $modifiedStats['armor'],
                $modifiedStats['speed'],
                $modifiedStats['health'],
                $modifiedStats['stamina']
            );
            $insertStmt->execute();

            // Include rarity in returned item
            $item['rarity'] = (int) $rolledRarity['id'];
            $itemsDropped[] = $item;
        }
    }

    return $itemsDropped;
}

function rollRarity($rarities)
{
    $roll = rand(1, 100);
    $cumulative = 0;

    foreach ($rarities as $rarity) {
        $cumulative += $rarity['chance'];
        if ($roll <= $cumulative) {
            return $rarity;
        }
    }

    return end($rarities); // fallback
}
function applyModifiers($baseItem, $modifiers)
{
    $stats = [
        'attack',
        'defense',
        'crit_chance',
        'crit_multi',
        'life_steal',
        'armor',
        'speed',
        'health',
        'stamina'
    ];

    foreach ($stats as $stat) {
        $baseItem[$stat] = (int) $baseItem[$stat]; // ensure numeric
    }

    while ($mod = $modifiers->fetch_assoc()) {
        $stat = $mod['stat_name'];
        // Get random value between min_value and max_value
        $value = rand($mod['min_value'], $mod['max_value']);

        if (!isset($baseItem[$stat]))
            continue;

        if ($mod['modifier_type'] === 'percent') {
            $baseItem[$stat] += floor($baseItem[$stat] * ($value / 100));
        } elseif ($mod['modifier_type'] === 'fixed') {
            $baseItem[$stat] += $value;
        }
    }

    return $baseItem;
}

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
            $battleLog['result'] = "You don't enough stamina required to fight";
            $battleLog['gold_gain'] = 0;
            $battleLog['exp_gain'] = 0;
            $battleLog['battle'] = "You lose.";
            echo json_encode($battleLog);
            exit();
        } else {
            //Deduct the cost of the stamina cost for battle from playe
            $staminaCost = 2;
            $player->updateStamina($staminaCost);
        }

        $playerSpeed = $player->getSpeed();
        $monsterSpeed = $monster->getSpeed();

        $firstTurn = $playerSpeed >= $monsterSpeed ? $player : $monster;
        $secondTurn = $firstTurn === $player ? $monster : $player;

        // Helper function for critical and lifesteal
        function calculateAttack($attacker, $target) {
            // Only use getPlayerEquippedItemAttack if attacker is Player, else fallback to calculateDamage
            $isPlayer = get_class($attacker) === 'Player';
            if ($isPlayer && method_exists($attacker, 'getPlayerItemAttack')) {
                $baseDamage = $attacker->getPlayerItemAttack($target);
            } else {
                $baseDamage = calculateDamage($attacker, $target);
            }

            // Only apply crit if attacker is a Player
            $critChance = $isPlayer && method_exists($attacker, 'getPlayerItemCritChance') ? $attacker->getPlayerItemCritChance() : 0;
            $critMulti  = $isPlayer && method_exists($attacker, 'getPlayerItemCritMulti') ? $attacker->getPlayerItemCritMulti() : 1;
            $isCrit = false;
            if ($critChance > 0 && rand(1, 100) <= $critChance) {
                $baseDamage = intval($baseDamage * $critMulti);
                $isCrit = true;
            }

            // Life steal: applies to all hits, but only a chance to leech (10% flat chance)
            $lifeSteal = $isPlayer && method_exists($attacker, 'getPlayerItemLifesteal') ? $attacker->getPlayerItemLifesteal() : 0;
            $lifeStealChance = 10; // Flat 10% chance
            $lifeStealAmount = 0;
            if ($lifeSteal > 0 && $baseDamage > 0 && rand(1, 100) <= $lifeStealChance) {
                $lifeStealAmount = intval($baseDamage * ($lifeSteal / 100));
            }

            return [
                'damage' => $baseDamage,
                'isCrit' => $isCrit,
                'lifeSteal' => $lifeStealAmount
            ];
        }

        while ($playerCurrentHp > 0 && $monsterCurrentHp > 0) {

            $attacker = $firstTurn;
            $target = $firstTurn === $player ? $monster : $player;
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

            if ($playerCurrentHp <= 0 || $monsterCurrentHp <= 0)
                break;

            // Speed-based consecutive attack: 10% chance if faster
            if ($playerSpeed > $monsterSpeed && $firstTurn === $player && rand(1, 100) <= 10) {
                $battleLog['battle'][] = "{$player->getName()} attacks consecutively due to speed advantage!";
                continue;
            }
            if ($monsterSpeed > $playerSpeed && $firstTurn === $monster && rand(1, 100) <= 10) {
                $battleLog['battle'][] = "{$monster->getName()} attacks consecutively due to speed advantage!";
                continue;
            }

            $attacker = $secondTurn;
            $target = $secondTurn === $player ? $monster : $player;
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

                        // Trigger world event if rarity >= 1
                        if (!$worldEventTriggered && isset($item['rarity']) && $item['rarity'] >= 1) {
                 
                            $rarity_name = $player->getItemRarity($item['rarity']);
                            $worldEvent = "{$player->getName()} has looted a {$rarity_name} {$item['name']}!";
                            updateWorldEvent($conn, $playerId, $worldEvent);
                            $worldEventTriggered = true;
                        }
                    }
                }
            }
        } else {
            $battleLog['loot_message'] = "No items dropped.";
        }

        echo json_encode($battleLog);
    } else {
        echo json_encode(array("status" => "error", "message" => "Error: You are unauthorised :)"));
    }
}
?>