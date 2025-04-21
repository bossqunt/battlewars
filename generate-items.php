<?php
session_start();

include 'includes/sidebar.php';
?>

<?php

// Define global drop pool for item prefixes, types, and names
$itemPrefixes = ['Unholy'];
$itemTypes = ['Weapon', 'Helmet', 'Armor', 'Boots', 'Shield', 'Amulet', 'Ring', 'Legs'];

// Define item names specific to each type (e.g., Steel Armor, Titan Helmet)
$itemNames = [
    'Weapon' => ['Sword', 'Axe', 'Spear', 'Dagger'],
    'Helmet' => ['Helmet', 'Helm', 'Cap'],
    'Armor' => ['Armor', 'Plate', 'Chestplate'],
    'Boots' => ['Boots', 'Greaves'],
    'Shield' => ['Shield', 'Buckler', 'Guard'],
    'Amulet' => ['Amulet of Strength', 'Amulet of Wisdom', 'Amulet of Protection'],
    'Ring' => ['Ring of Power', 'Ring of Luck', 'Ring of Fire'],
    'Legs' => ['Leggings', 'Greaves', 'Pants']
];

// Define stat categories for each item type
$baseStats = [
    'Weapon' => ['attack' => [5, 25], 'defense' => [0, 5], 'crit_chance' => [2, 15], 'crit_multi' => [1.1, 2.0], 'life_steal' => [0, 0.1], 'armor' => [0, 0], 'speed' => [1, 5], 'health' => [0, 20], 'stamina' => [0, 15]],
    'Helmet' => ['attack' => [0, 5], 'defense' => [10, 25], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [5, 15], 'speed' => [0, 0], 'health' => [5, 20], 'stamina' => [5, 15]],
    'Armor' => ['attack' => [0, 10], 'defense' => [15, 40], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [10, 30], 'speed' => [0, 0], 'health' => [15, 50], 'stamina' => [10, 25]],
    'Boots' => ['attack' => [0, 5], 'defense' => [0, 5], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [3, 10], 'speed' => [2, 10], 'health' => [3, 15], 'stamina' => [2, 10]],
    'Shield' => ['attack' => [0, 0], 'defense' => [20, 50], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [15, 50], 'speed' => [0, 0], 'health' => [5, 25], 'stamina' => [5, 20]],
    'Amulet' => ['attack' => [0, 0], 'defense' => [0, 0], 'crit_chance' => [5, 15], 'crit_multi' => [1.1, 1.5], 'life_steal' => [0.1, 0.2], 'armor' => [0, 0], 'speed' => [0, 2], 'health' => [10, 30], 'stamina' => [0, 5]],
    'Ring' => ['attack' => [0, 0], 'defense' => [0, 0], 'crit_chance' => [2, 10], 'crit_multi' => [1.1, 2.0], 'life_steal' => [0, 0.05], 'armor' => [0, 0], 'speed' => [0, 0], 'health' => [5, 20], 'stamina' => [0, 10]],
    'Legs' => ['attack' => [0, 5], 'defense' => [10, 30], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [8, 20], 'speed' => [0, 0], 'health' => [8, 30], 'stamina' => [5, 20]],
];

// Define level ranges
$levelRanges = [
    [1, 10],
    [11, 20],
    [21, 30],
    [31, 40],
    [41, 50],
    [51, 60],
    [61, 70],
    [71, 80],
    [81, 90],
    [91, 100]
];

// Function to generate random stats within the specified ranges
function generateStat($min, $max, $decimal = 0) {
    return round(rand($min * 100, $max * 100) / 100, $decimal);
}

// Function to apply tier scaling based on the name
function applyNameScaling($baseValue, $name) {
    $scalingFactors = [
        'Steel' => 1,
        'Titan' => 1.5,
        'Ancient' => 1.8,
        'Enchanted' => 1.2,
        'Cursed' => 1.3,
        'Divine' => 2,
        'Mythic' => 2.5,

    ];

    $scalingFactor = isset($scalingFactors[$name]) ? $scalingFactors[$name] : 1;

    return round($baseValue * $scalingFactor, 2);
}

// Function to generate a random item with scaling based on its base type
function generateItem($type, $itemNames, $baseStats, $itemPrefixes, $levelRange) {
    $prefix = $itemPrefixes[array_rand($itemPrefixes)];
    $name = $prefix . " " . $itemNames[$type][array_rand($itemNames[$type])];

    $stats = $baseStats[$type];

    foreach ($stats as $stat => $range) {
        $baseValue = generateStat($range[0], $range[1]);
        $stats[$stat] = applyNameScaling($baseValue, explode(' ', $name)[0]);
    }

    $levelMultiplier = $levelRange[0] / 10;

    foreach ($stats as $stat => $value) {
        $stats[$stat] *= $levelMultiplier;
    }

    return [
        'name' => $name,
        'type' => $type,
        'level_range' => $levelRange,  // Add the level range here
        'attack' => $stats['attack'],
        'defense' => $stats['defense'],
        'crit_chance' => $stats['crit_chance'],
        'crit_multi' => $stats['crit_multi'],
        'life_steal' => $stats['life_steal'],
        'armor' => $stats['armor'],
        'speed' => $stats['speed'],
        'health' => $stats['health'],
        'stamina' => $stats['stamina'],
        'gold' => generateStat(50, 200)
    ];
}

// Function to generate and print out the HTML table for multiple items for each level range
function generateItemTable($types, $itemNames, $baseStats, $itemPrefixes, $levelRanges, $groupBy) {
    $html = '<form method="POST">
                <label for="groupBy">Group by:</label>
                <select id="groupBy" name="groupBy" onchange="this.form.submit()">
                    <option value="type" ' . ($groupBy == 'type' ? 'selected' : '') . '>Item Type</option>
                    <option value="prefix" ' . ($groupBy == 'prefix' ? 'selected' : '') . '>Base Type (Prefix)</option>
                </select>
            </form><br>';

    $groupedItems = [];
    foreach ($types as $type) {
        foreach ($levelRanges as $levelRange) {
            for ($i = 0; $i < 3; $i++) {
                $item = generateItem($type, $itemNames, $baseStats, $itemPrefixes, $levelRange);

                $groupKey = $groupBy == 'prefix' ? explode(' ', $item['name'])[0] : $item['type'];
                $groupedItems[$groupKey][] = $item;
            }
        }
    }

    $html .= '<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Level Range</th>
                        <th>Attack</th>
                        <th>Defense</th>
                        <th>Crit Chance</th>
                        <th>Crit Multi</th>
                        <th>Life Steal</th>
                        <th>Armor</th>
                        <th>Speed</th>
                        <th>Health</th>
                        <th>Stamina</th>
                        <th>Gold</th>
                    </tr>
                </thead>
                <tbody>';

    foreach ($groupedItems as $group => $items) {
        $html .= '<tr><td colspan="13"><strong>' . ucfirst($group) . '</strong></td></tr>';
        foreach ($items as $item) {
            $levelRange = implode(' - ', $item['level_range']);
            $html .= '<tr>
                        <td>' . $item['name'] . '</td>
                        <td>' . $item['type'] . '</td>
                        <td>' . $levelRange . '</td>  <!-- Display Level Range -->
                        <td>' . $item['attack'] . '</td>
                        <td>' . $item['defense'] . '</td>
                        <td>' . $item['crit_chance'] . '</td>
                        <td>' . $item['crit_multi'] . '</td>
                        <td>' . $item['life_steal'] . '</td>
                        <td>' . $item['armor'] . '</td>
                        <td>' . $item['speed'] . '</td>
                        <td>' . $item['health'] . '</td>
                        <td>' . $item['stamina'] . '</td>
                        <td>' . $item['gold'] . '</td>
                    </tr>';
        }
    }

    $html .= '</tbody></table>';
    return $html;
}

// Get the selected group-by option from the dropdown
$groupBy = isset($_POST['groupBy']) ? $_POST['groupBy'] : 'type';

// Generate and display the item table
echo generateItemTable($itemTypes, $itemNames, $baseStats, $itemPrefixes, $levelRanges, $groupBy);

?>
