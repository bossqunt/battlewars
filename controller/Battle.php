<?php

// Helper function for random dice rolls
function rollDice($sides = 6) {
    return rand(1, $sides);
}

function updateWorldEvent($conn, $playerId, $event) {
    try {
        // Update the world event for the player
        $stmt = $conn->prepare('INSERT INTO world_events (player_id, event) VALUES (?, ?)');
        $stmt->bind_param('is', $playerId, $event);
        $stmt->execute();
        return true;
    } catch (Exception $e) {
        error_log('Database error in updateWorldEvent(): ' . $e->getMessage());
        return false;
    }
}

// Function to update battle history with a limit of 10 records
function updateBattleHistory(
    $conn,
    int $playerId,
    ?int $opponentId,
    ?int $monsterId,
    string $result,
    string $outcome,
    int $playerWon,
    ?int $expGain = null,
    ?int $goldGain = null
) {
    try {
        // Check current number of records
        $stmtCount = $conn->prepare('SELECT COUNT(*) as count FROM battle_history WHERE player_id = ?');
        $stmtCount->bind_param('i', $playerId);
        $stmtCount->execute();
        $countResult = $stmtCount->get_result()->fetch_assoc();
        $currentCount = intval($countResult['count']);

        // If there are more than 10 records, delete the oldest one
        if ($currentCount >= 10) {
            $stmtDelete = $conn->prepare('DELETE FROM battle_history WHERE id = (SELECT id FROM battle_history WHERE player_id = ? ORDER BY id ASC LIMIT 1)');
            $stmtDelete->bind_param('i', $playerId);
            $stmtDelete->execute();
        }

        // Prepare the insert query dynamically
        if ($expGain === null || $goldGain === null) {
            $stmtInsert = $conn->prepare('INSERT INTO battle_history (player_id, opponent_id, monster_id, result, outcome, player_won) VALUES (?, ?, ?, ?, ?, ?)');
            $stmtInsert->bind_param('iiissi', $playerId, $opponentId, $monsterId, $result, $outcome, $playerWon);
        } else {
            $stmtInsert = $conn->prepare('INSERT INTO battle_history (player_id, opponent_id, monster_id, result, outcome, exp_gain, gold_gain, player_won) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $stmtInsert->bind_param('iiisssii', $playerId, $opponentId, $monsterId, $result, $outcome, $expGain, $goldGain, $playerWon);
        }

        $stmtInsert->execute();
        return true;

    } catch (Exception $e) {
        error_log('Database error in updateBattleHistory(): ' . $e->getMessage());
        return false;
    }
}


// Function to calculate damage
function calculateDamage($attacker, $target) {
    // Base attack and defense
    $baseAttack = $attacker->getAttack();
    $baseDefense = $target->getDefence();

    // Random modifiers
    $attackModifier = rollDice(6); // Roll a 6-sided die for the attack modifier
    $defenseModifier = rollDice(6); // Roll a 6-sided die for the defense modifier

    // Factor in levels
    $levelDifference = $attacker->getLevel() - $target->getLevel();
    $levelFactor = max(0, $levelDifference);

    // New weapon attack modifier and skill factor
    $weaponAttack = method_exists($attacker, 'getWeaponAttack') ? $attacker->getWeaponAttack() : 0;
    $skill = method_exists($attacker, 'getSkill') ? $attacker->getSkill() : 1; // Default skill to 1 if method doesn't exist

    // Calculate final attack value
    $finalAttack = $baseAttack + (6 / 5 * $weaponAttack) * $skill + (4 / 28) + $attackModifier;

    // Calculate final defense value
    $finalDefense = $baseDefense + $defenseModifier;

    // Calculate damage
    $damage = max(0, ($finalAttack + $attackModifier) - $finalDefense);

    // // Print debug information
    // echo "Base Attack: $baseAttack, Weapon Attack: $weaponAttack, Skill: $skill, Attack Modifier: $attackModifier, Final Attack: $finalAttack<br>";
    // echo "Base Defense: $baseDefense, Defense Modifier: $defenseModifier, Final Defense: $finalDefense<br>";
    // echo "Level Factor: $levelFactor, Calculated Damage: $damage<br>";

    return round($damage);
}

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
    // Support float chances (e.g., 0.1) by multiplying by 10 for 0.1% precision
    $totalChance = 0;
    foreach ($rarities as $rarity) {
        $totalChance += floatval($rarity['chance']);
    }
    $roll = rand(1, intval($totalChance * 10));
    $cumulative = 0;
    foreach ($rarities as $rarity) {
        $cumulative += floatval($rarity['chance']) * 10;
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

    // Start with base stats
    $modified = [];
    foreach ($stats as $stat) {
        $modified[$stat] = isset($baseItem[$stat]) ? (int)$baseItem[$stat] : 0;
    }

    // Apply modifiers if any
    while ($mod = $modifiers->fetch_assoc()) {
        $stat = $mod['stat_name'];
        $value = rand($mod['min_value'], $mod['max_value']);
        if (!isset($modified[$stat])) continue;

        if ($mod['modifier_type'] === 'percent') {
            $modified[$stat] += floor($modified[$stat] * ($value / 100));
        } elseif ($mod['modifier_type'] === 'fixed') {
            $modified[$stat] += $value;
        }
    }

    return $modified;
}

// Test function for rarity distribution (for debugging)
function testRarityDistribution($rarities, $iterations = 100000) {
    $results = [];
    for ($i = 0; $i < $iterations; $i++) {
        $rarity = rollRarity($rarities);
        $id = $rarity['id'];
        if (!isset($results[$id])) $results[$id] = 0;
        $results[$id]++;
    }
    // Output results
    echo "<table border='1'><tr><th>Rarity ID</th><th>Name</th><th>Chance</th><th>Rolls</th><th>Rate (%)</th></tr>";
    foreach ($rarities as $rarity) {
        $id = $rarity['id'];
        $rolls = isset($results[$id]) ? $results[$id] : 0;
        $rate = number_format(($rolls / $iterations) * 100, 3);
        echo "<tr><td>{$id}</td><td>{$rarity['name']}</td><td>{$rarity['chance']}</td><td>{$rolls}</td><td>{$rate}</td></tr>";
    }
    echo "</table>";
}