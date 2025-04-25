<?php
require_once('../controller/Database.php');
require_once('../controller/Battle.php');

// Setup DB connection
$database = new Database();
$conn = $database->getConnection();

// Fetch rarity tiers
$rarityStmt = $conn->query("SELECT * FROM item_rarities ORDER BY id ASC");
$rarities = [];
while ($rarity = $rarityStmt->fetch_assoc()) {
    $rarities[] = $rarity;
}

// Fetch a sample item (adjust item_id as needed)
$itemId = 51; // Change to a valid item id
$itemStmt = $conn->prepare('SELECT * FROM items WHERE id = ?');
$itemStmt->bind_param('i', $itemId);
$itemStmt->execute();
$baseItem = $itemStmt->get_result()->fetch_assoc();

if (!$baseItem) {
    die("No item found with id $itemId");
}

// Simulate rarity rolls and stat modifications
$iterations = 100;
$rolls = [];

for ($i = 0; $i < $iterations; $i++) {
    $rolledRarity = rollRarity($rarities);

    // Fetch and apply rarity modifiers
    $modStmt = $conn->prepare("SELECT * FROM item_rarity_modifiers WHERE rarity_id = ?");
    $modStmt->bind_param('i', $rolledRarity['id']);
    $modStmt->execute();
    $mods = $modStmt->get_result();

    $modifiedStats = applyModifiers($baseItem, $mods);

    $rolls[] = [
        'rarity_id' => $rolledRarity['id'],
        'rarity_name' => $rolledRarity['name'],
        'rarity_chance' => $rolledRarity['chance'],
        'base' => $baseItem,
        'modified' => $modifiedStats
    ];
}

// Output as HTML table
echo "<!DOCTYPE html><html><head><title>Rarity Roll Stat Demo</title>
<style>
body { font-family: Arial, sans-serif; margin: 2em; }
table { border-collapse: collapse; width: 100%; }
th, td { border: 1px solid #ccc; padding: 6px 10px; text-align: left; }
th { background: #f0f0f0; }
tr:nth-child(even) { background: #fafafa; }
</style>
</head><body>";

echo "<h2>Sample of $iterations Rarity Rolls for Item: " . htmlspecialchars($baseItem['name']) . "</h2>";
echo "<table>";
echo "<tr>
<th>#</th>
<th>Rarity</th>
<th>Chance (%)</th>
<th>Base Attack</th>
<th>Base Crit</th>
<th>Base Def</th>
<th>Mod Attack</th>
<th>Mod Crit</th>
<th>Mod Def</th>
<th>All Base Stats</th>
<th>All Modified Stats</th>
</tr>";

foreach ($rolls as $i => $roll) {
    echo "<tr>";
    echo "<td>" . ($i + 1) . "</td>";
    echo "<td>" . htmlspecialchars($roll['rarity_name']) . "</td>";
    echo "<td>" . htmlspecialchars($roll['rarity_chance']) . "</td>";
    echo "<td>" . htmlspecialchars($roll['base']['attack']) . "</td>";
    echo "<td>" . htmlspecialchars($roll['base']['crit_chance']) . "</td>";
    echo "<td>" . htmlspecialchars($roll['base']['defense']) . "</td>";
    echo "<td>" . htmlspecialchars($roll['modified']['attack']) . "</td>";
    echo "<td>" . htmlspecialchars($roll['modified']['crit_chance']) . "</td>";
    echo "<td>" . htmlspecialchars($roll['modified']['defense']) . "</td>";
    echo "<td><small>" . htmlspecialchars(json_encode($roll['base'])) . "</small></td>";
    echo "<td><small>" . htmlspecialchars(json_encode($roll['modified'])) . "</small></td>";
    echo "</tr>";
}
echo "</table>";
echo "</body></html>";
?>
