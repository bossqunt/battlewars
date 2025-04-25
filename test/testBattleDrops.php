<?php
require_once('../controller/Database.php');
require_once('../controller/Battle.php');

// Set your test player and monster IDs here
$testPlayerId = 67;
$testMonsterId = 55;

// Setup DB connection
$database = new Database();
$conn = $database->getConnection();

// Run the drop logic multiple times to see distribution
$dropResults = [];
$iterations = 10000;

// Track win/loss
$winCount = 0;
$lossCount = 0;

for ($i = 0; $i < $iterations; $i++) {
    $drops = handleItemDrops($conn, $testPlayerId, $testMonsterId);
    // Determine win/loss by whether any drops occurred (you can adjust this logic if needed)
    if (!empty($drops)) {
        $winCount++;
    } else {
        $lossCount++;
    }
    foreach ($drops as $item) {
        $key = "{$item['name']}|rarity:{$item['rarity']}";
        if (!isset($dropResults[$key])) $dropResults[$key] = 0;
        $dropResults[$key]++;
    }
}

// Sort results by drops descending
arsort($dropResults);

$totalDrops = 0;

// Output as HTML
echo "<!DOCTYPE html><html><head><title>Battle Drop Test Results</title>
<style>
body { font-family: Arial, sans-serif; margin: 2em; }
table { border-collapse: collapse; width: 60%; }
th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: left; }
th { background: #f0f0f0; }
tr:nth-child(even) { background: #fafafa; }
</style>
</head><body>";

echo "<h2>Drop results after $iterations battles</h2>";
echo "<table>";
echo "<tr><th>Item Name</th><th>Rarity</th><th>Drops</th><th>Drop Rate (%)</th></tr>";

foreach ($dropResults as $itemKey => $count) {
    list($name, $rarity) = explode('|rarity:', $itemKey);
    $rate = number_format(($count / $iterations) * 100, 2);
    echo "<tr>";
    echo "<td>" . htmlspecialchars($name) . "</td>";
    echo "<td>" . htmlspecialchars($rarity) . "</td>";
    echo "<td>$count</td>";
    echo "<td>$rate</td>";
    echo "</tr>";
    $totalDrops += $count;
}
echo "<tr style='font-weight:bold; background:#e0e0e0;'><td colspan='2'>Total drops</td><td colspan='2'>$totalDrops (in $iterations battles)</td></tr>";
echo "</table>";

// Win/loss stats
$winRate = number_format(($winCount / $iterations) * 100, 2);
$lossRate = number_format(($lossCount / $iterations) * 100, 2);
echo "<h3>Win/Loss Stats</h3>";
echo "<ul>";
echo "<li>Wins: $winCount (" . $winRate . "%)</li>";
echo "<li>Losses: $lossCount (" . $lossRate . "%)</li>";
echo "</ul>";

echo "</body></html>";
?>
