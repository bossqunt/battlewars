<?php
session_start();

include 'includes/sidebar.php';
?>

<?php

// Define global drop pool for item prefixes, types, and names, and associate scaling factors
$itemPrefixes = [
    'Wooden'    => 1.0, // 1-10
    'Cloth'     => 1.1,  // 11-20
    'Leather'   => 1.2, // 21-30
    'Bronze'    => 1.3, // 31-40
    'Iron'      => 1.4, // 41-50
    'Steel'     => 1.5,        // 51-60
    'Plate'     => 1.6,
    'Tempered'  => 1.7,
    'Forged'    => 1.8,
    'Gladiator' => 1.9,
    'Crusader'  => 2.0,
    'Enchanted' => 2.1,
    'Arcane'    => 2.2,
    'Astral'    => 2.3,
    'Divine'    => 2.4,
    'Mythic'    => 2.5,
    'Celestial' => 2.6,
    'Ethereal'  => 2.7,
    'Godforged' => 2.8,
    'Deity'     => 2.9
];
$itemTypes = ['Weapon', 'Helmet', 'Armor', 'Boots', 'Shield', 'Amulet', 'Ring', 'Legs'];

// Define item names specific to each type (e.g., Steel Armor, Titan Helmet)
$itemNames = [
    'Weapon' => ['Sword', 'Axe', 'Spear', 'Dagger'],
    'Helmet' => ['Helmet'],
    'Armor' => ['Armor'],
    'Boots' => ['Boots'],
    'Shield' => ['Shield'],
    'Amulet' => ['Amulet'],
    'Ring' => ['Ring'],
    'Legs' => ['Legs']
];

// Define stat categories for each item type (all integer ranges)
$baseStats = [
    'Weapon' => ['attack' => [5, 5], 'defense' => [0, 5], 'crit_chance' => [2, 15], 'crit_multi' => [1, 2], 'life_steal' => [0, 1], 'armor' => [0, 0], 'speed' => [1, 5], 'health' => [0, 20], 'stamina' => [0, 15]],
    'Helmet' => ['attack' => [1, 5], 'defense' => [10, 25], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [5, 15], 'speed' => [0, 0], 'health' => [5, 20], 'stamina' => [5, 15]],
    'Armor' => ['attack' => [1, 10], 'defense' => [15, 40], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [10, 30], 'speed' => [0, 0], 'health' => [15, 50], 'stamina' => [10, 25]],
    'Boots' => ['attack' => [1, 5], 'defense' => [0, 5], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [3, 10], 'speed' => [2, 10], 'health' => [3, 15], 'stamina' => [2, 10]],
    'Shield' => ['attack' => [1, 1], 'defense' => [20, 50], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [15, 50], 'speed' => [0, 0], 'health' => [5, 25], 'stamina' => [5, 20]],
    'Amulet' => ['attack' => [1, 1], 'defense' => [0, 0], 'crit_chance' => [5, 15], 'crit_multi' => [1, 1], 'life_steal' => [0, 0], 'armor' => [0, 0], 'speed' => [0, 2], 'health' => [10, 30], 'stamina' => [0, 5]],
    'Ring' => ['attack' => [1, 1], 'defense' => [0, 0], 'crit_chance' => [2, 10], 'crit_multi' => [1, 2], 'life_steal' => [0, 0], 'armor' => [0, 0], 'speed' => [0, 0], 'health' => [5, 20], 'stamina' => [0, 10]],
    'Legs' => ['attack' => [1, 5], 'defense' => [10, 30], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [8, 20], 'speed' => [0, 0], 'health' => [8, 30], 'stamina' => [5, 20]],
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
    [91, 100],
    [101, 110],
    [111, 120],
    [121, 130],
    [131, 140],
    [141, 150],
    [151, 160],
    [161, 170],
    [171, 180],
    [181, 190],
    [191, 200]

];

// Map each prefix to a level range (1:1 mapping)
$prefixNames = array_keys($itemPrefixes);
$prefixLevelMap = array_combine($prefixNames, array_slice($levelRanges, 0, count($prefixNames)));

// Function to generate random stats within the specified ranges (always integer)
function generateStat($min, $max) {
    return rand($min, $max);
}

// Function to apply tier scaling based on the name (integer scaling)
function applyNameScaling($baseValue, $prefix) {
    global $itemPrefixes;
    $scalingFactor = isset($itemPrefixes[$prefix]) ? $itemPrefixes[$prefix] : 1;
    return intval(round($baseValue * $scalingFactor));
}

// Weapon subtype stat multipliers
$weaponSubtypeStats = [
    'Sword' => [
        'attack' => 1.0,
        'defense' => 1.0,
        'speed' => 1.0,
    ],
    'Axe' => [
        'attack' => 1.3,
        'defense' => 0.2,
        'speed' => 0.9,
    ],
    'Dagger' => [
        'attack' => 0.7,
        'defense' => 0.8,
        'speed' => 1.5,
    ],
    'Spear' => [
        'attack' => 1.1,
        'defense' => 0.5,
        'speed' => 1.0,
    ],
];

// Function to generate a random item with scaling based on its base type and prefix/level
function generateItem($type, $itemNames, $baseStats, $prefix, $levelRange, $subtype = null) {
    // If subtype is not provided, pick random (for non-weapons)
    if ($type === 'Weapon' && $subtype !== null) {
        $name = $prefix . " " . $subtype;
    } else {
        $subtype = $itemNames[$type][array_rand($itemNames[$type])];
        $name = $prefix . " " . $subtype;
    }
    $stats = $baseStats[$type];

    foreach ($stats as $stat => $range) {
        $baseValue = generateStat($range[0], $range[1]);
        $stats[$stat] = applyNameScaling($baseValue, $prefix);
    }

    // Apply weapon subtype multipliers if type is Weapon
    if ($type === 'Weapon') {
        $mod = $GLOBALS['weaponSubtypeStats'][$subtype];
        if (isset($mod['attack']))  $stats['attack']  = intval(round($stats['attack']  * $mod['attack']));
        if (isset($mod['defense'])) $stats['defense'] = intval(round($stats['defense'] * $mod['defense']));
        if (isset($mod['speed']))   $stats['speed']   = intval(round($stats['speed']   * $mod['speed']));
    }

    // Ensure multiplier is at least 1
    $levelMultiplier = max(2, intval($levelRange[0] / 10));
    foreach ($stats as $stat => $value) {
        $stats[$stat] = intval($stats[$stat] * $levelMultiplier);
    }

    return [
        'name' => $name,
        'type' => $type,
        'level_range' => $levelRange,
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

// Function to generate and print out the HTML table for items, grouped by prefix or type
function generateItemTable($types, $itemNames, $baseStats, $itemPrefixes, $prefixLevelMap, $groupBy) {
    $html = '<form method="POST">
                <label for="groupBy">Group by:</label>
                <select id="groupBy" name="groupBy" onchange="this.form.submit()">
                    <option value="type" ' . ($groupBy == 'type' ? 'selected' : '') . '>Item Type</option>
                    <option value="prefix" ' . ($groupBy == 'prefix' ? 'selected' : '') . '>Base Type (Prefix)</option>
                </select>
            </form><br>';

    $groupedItems = [];
    foreach ($prefixLevelMap as $prefix => $levelRange) {
        foreach ($types as $type) {
            if ($type === 'Weapon') {
                // Generate one of each weapon subtype
                foreach ($itemNames['Weapon'] as $subtype) {
                    $item = generateItem($type, $itemNames, $baseStats, $prefix, $levelRange, $subtype);
                    $groupKey = $groupBy == 'prefix' ? $prefix : $type;
                    $groupedItems[$groupKey][] = $item;
                }
            } else {
                $item = generateItem($type, $itemNames, $baseStats, $prefix, $levelRange);
                $groupKey = $groupBy == 'prefix' ? $prefix : $type;
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
                        <td>' . $levelRange . '</td>
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
echo generateItemTable($itemTypes, $itemNames, $baseStats, $itemPrefixes, $prefixLevelMap, $groupBy);

?>
