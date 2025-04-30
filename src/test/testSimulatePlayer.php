<?php
require_once('../controller/Database.php');
require_once('../controller/Battle.php');

// --- Simulated player state ---
$player = [
    'id' => 0, // Simulated, not real DB id
    'level' => 1,
    'exp' => 0,
    'gold' => 0,
    'inventory' => [],
    'expToLevel' => 100, // Example value, adjust as per your game logic
];

// --- Simulation parameters ---
$monsterId = rand(1,14); // Or randomize/select from a list
$iterations = 100;
$winCount = 0;
$lossCount = 0;
$totalExpGained = 0;
$totalGoldGained = 0;
$levelUps = 0;
$dropResults = [];
$monstersFought = 0;

// --- Setup DB connection for reading monster/item data only ---
$database = new Database();
$conn = $database->getConnection();

for ($i = 0; $i < $iterations; $i++) {
    // Simulate battle (replace with your own logic if needed)
    $battleResult = simulateBattle($player, $monsterId, $conn); // returns ['win'=>bool, 'exp'=>int, 'gold'=>int]
    $monstersFought++;
    if ($battleResult['win']) {
        $winCount++;
        $player['exp'] += $battleResult['exp'];
        $player['gold'] += $battleResult['gold'];
        $totalExpGained += $battleResult['exp'];
        $totalGoldGained += $battleResult['gold'];

        // Handle level up (simple example)
        while ($player['exp'] >= $player['expToLevel']) {
            $player['exp'] -= $player['expToLevel'];
            $player['level']++;
            $levelUps++;
            // Increase expToLevel for next level (e.g., +20% per level)
            $player['expToLevel'] = (int)($player['expToLevel'] * 1.2);
        }

        // Simulate drops (use your drop logic, but do not update DB)
        $drops = handleItemDrops($conn, $player['id'], $monsterId); // returns array of items
        foreach ($drops as $item) {
            $key = "{$item['name']}|rarity:{$item['rarity']}";
            if (!isset($dropResults[$key])) $dropResults[$key] = 0;
            $dropResults[$key]++;
            // Add to inventory (optional)
            $player['inventory'][] = $item;
        }
    } else {
        $lossCount++;
    }
}

// --- Output results ---
arsort($dropResults);
echo "<!DOCTYPE html><html><head><title>Simulated Player Battle Results</title>
<style>
body { font-family: Arial, sans-serif; margin: 2em; }
table { border-collapse: collapse; width: 60%; }
th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: left; }
th { background: #f0f0f0; }
tr:nth-child(even) { background: #fafafa; }
</style>
</head><body>";

echo "<h2>Simulated Player Battle Results ($iterations battles)</h2>";
echo "<ul>";
echo "<li>Wins: $winCount</li>";
echo "<li>Losses: $lossCount</li>";
echo "<li>Win Rate: " . number_format(($winCount/$iterations)*100,2) . "%</li>";
echo "<li>Total Gold Gained: $totalGoldGained</li>";
echo "<li>Total Exp Gained: $totalExpGained</li>";
echo "<li>Levels Gained: $levelUps (Final Level: {$player['level']})</li>";
echo "<li>Current Exp Toward Next Level: {$player['exp']} / {$player['expToLevel']}</li>";
echo "<li>Total Monsters Fought: $monstersFought</li>";
echo "<li>Total Drops: " . array_sum($dropResults) . "</li>";
echo "</ul>";

echo "<h3>Drop Results</h3>";
echo "<table>";
echo "<tr><th>Item Name</th><th>Rarity</th><th>Drops</th></tr>";
foreach ($dropResults as $itemKey => $count) {
    list($name, $rarity) = explode('|rarity:', $itemKey);
    echo "<tr>";
    echo "<td>" . htmlspecialchars($name) . "</td>";
    echo "<td>" . htmlspecialchars($rarity) . "</td>";
    echo "<td>$count</td>";
    echo "</tr>";
}
echo "</table>";

echo "</body></html>";

/**
 * Simulate a battle outcome for the player vs monster.
 * Replace this with your actual battle logic if available.
 */
function simulateBattle($player, $monsterId, $conn) {
    // Example: 70% win chance, random exp/gold
    $win = (mt_rand(1, 100) <= 70);
    $exp = $win ? mt_rand(10, 30) : 0;
    $gold = $win ? mt_rand(5, 20) : 0;
    return ['win' => $win, 'exp' => $exp, 'gold' => $gold];
}
?>
