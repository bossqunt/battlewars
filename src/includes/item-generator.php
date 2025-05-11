<?php
include_once './controller/Database.php';

// Weapon types Sword, Axe, Dagger, Spear, Bow, Staff, Crossbow
// Armor types Helmet, Armor, Boots, Shield, Amulet, Ring, Legs

// REQUIREMENTS
// 1. Items must have a prefix relative to their level range
// 2. The number of rolled modifiers must be limited based on rarity
// - Common: 1 modifier
// - Uncommon: 2 modifiers
// - Rare: 3 modifiers
// - Epic: 3-4 modifiers
// - Legendary: 5-6 modifiers
// - Godly: 6 modifiers
// 3. Each item type must have a set of base stats (attack, defense, etc.)
// 4. Modifiers can roll in "tiers", for example, t0 to t6, where each tier has a different set of modifier rolls for example.. t1 = 1-5%, t2 = 5-10%, t3 = 10-15%, etc. (or whatever you want)
// 5. For modifiers which roll physical_attack, there should be an increased probability to roll physical_attack_percent, this will be in addition to the base state of the item, and the physical_attack modifier
// 6. For rings and amulets, the base stats should be different from weapons and armor, and should have a different set of modifiers (for example, rings can roll crit_chance_percent, crit_multi_percent, life_steal_percent, etc.)
// I have included all the new modifiers, and I've included all modifiers based on item type

$modifiers = [
    'physical_attack',
    'physical_attack_percent',
    'crit_chance_percent', 
    'crit_multi_percent', 
    'life_steal_percent', 
    'armor', 
    'armor_percent',
    'speed', 
    'speed_percent',
    'health', 
    'health_percent',
    'health_regen',
    'stamina', 
    'fire_attack', 
    'fire_attack_percent',
    'ice_attack', 
    'ice_attack_percent',
    'lightning_attack', 
    'lightning_attack_percent',
    'poison_attack', 
    'poison_attack_percent',
    'fire_defence_percent',
    'ice_defence_percent',
    'lightning_defence_percent',
    'poison_defence_percent',
    'physical_defence_percent', 
    'gold'
];

// Adding new elemental attack and defense modifiers
$modifiers = array_merge($modifiers, [
    'fire_attack_flat', 'fire_attack_percent',
    'ice_attack_flat', 'ice_attack_percent',
    'lightning_attack_flat', 'lightning_attack_percent',
    'poison_attack_flat', 'poison_attack_percent',
    'fire_defense_flat', 'fire_defense_percent',
    'ice_defense_flat', 'ice_defense_percent',
    'lightning_defense_flat', 'lightning_defense_percent',
    'poison_defense_flat', 'poison_defense_percent'
]);

$modifiersByType = [
    'Weapon' => [
        'physical_attack', 
        'physical_attack_percent', 
        'crit_chance_percent', 
        'crit_multi_percent', 
        'life_steal_percent', 
        'speed', 
        'speed_percent', 
        'fire_attack', 
        'fire_attack_percent', 
        'ice_attack', 
        'ice_attack_percent', 
        'lightning_attack', 
        'lightning_attack_percent', 
        'poison_attack', 
        'poison_attack_percent'
    ],
    'Helmet' => [
        'armor', 
        'armor_percent', 
        'health', 
        'health_percent', 
        'health_regen', 
        'fire_defence_percent', 
        'ice_defence_percent', 
        'lightning_defence_percent', 
        'poison_defence_percent'
    ],
    'Armor' => [
        'armor', 
        'armor_percent', 
        'health', 
        'health_percent', 
        'health_regen', 
        'fire_defence_percent', 
        'ice_defence_percent', 
        'lightning_defence_percent', 
        'poison_defence_percent'
    ],
    'Boots' => [
        'armor', 
        'armor_percent', 
        'health', 
        'health_percent', 
        'health_regen', 
        'fire_defence_percent', 
        'ice_defence_percent', 
        'lightning_defence_percent', 
        'poison_defence_percent'
    ],
    'Shield' => [
        'armor', 
        'armor_percent', 
        'health', 
        'health_percent',
        'health_regen',
        'fire_defence_percent',
        'ice_defence_percent',
        'lightning_defence_percent',
        'poison_defence_percent',
    ],
    'Amulet' => [
        'armor', 
        'armor_percent', 
        'health', 
        'health_percent', 
        'health_regen', 
        'fire_defence_percent', 
        'ice_defence_percent', 
        'lightning_defence_percent', 
        'poison_defence_percent',
        'crit_chance_percent',
        'crit_multi_percent',
        'speed',
        'speed_percent',   
    ],
    'Ring' => [
        'armor', 
        'armor_percent', 
        'health', 
        'health_percent', 
        'health_regen', 
        'fire_defence_percent', 
        'ice_defence_percent', 
        'lightning_defence_percent', 
        'poison_defence_percent',
        'crit_chance_percent',
        'crit_multi_percent',
        'speed',
        'speed_percent',   
    ],
    'Legs' => [
        'armor', 
        'armor_percent', 
        'health', 
        'health_percent', 
        'health_regen', 
        'fire_defence_percent', 
        'ice_defence_percent', 
        'lightning_defence_percent', 
        'poison_defence_percent'
    ],
];

// Prefix configs, itemTypes, itemNames, baseStats, etc.
$prefixConfigs = [
    ['name' => 
        'Starter', 
        'multiplier' => 0.7, 
        'level_range' => [1, 10]
    ],
    ['name' => 'Wooden', 'multiplier' => 1.0, 'level_range' => [1, 10]],
    ['name' => 'Cloth', 'multiplier' => 1.1, 'level_range' => [11, 20]],
    ['name' => 'Leather', 'multiplier' => 1.2, 'level_range' => [21, 30]],
    ['name' => 'Bronze', 'multiplier' => 1.3, 'level_range' => [31, 40]],
    ['name' => 'Iron', 'multiplier' => 1.4, 'level_range' => [41, 50]],
    ['name' => 'Steel', 'multiplier' => 1.5, 'level_range' => [51, 60]],
    ['name' => 'Plate', 'multiplier' => 1.6, 'level_range' => [61, 70]],
    ['name' => 'Tempered', 'multiplier' => 1.7, 'level_range' => [71, 80]],
    ['name' => 'Forged', 'multiplier' => 1.8, 'level_range' => [81, 90]],
    ['name' => 'Gladiator', 'multiplier' => 1.9, 'level_range' => [91, 100]],
    ['name' => 'Crusader', 'multiplier' => 2.0, 'level_range' => [101, 110]],
    ['name' => 'Enchanted', 'multiplier' => 2.1, 'level_range' => [111, 120]],
    ['name' => 'Arcane', 'multiplier' => 2.2, 'level_range' => [121, 130]],
    ['name' => 'Astral', 'multiplier' => 2.3, 'level_range' => [131, 140]],
    ['name' => 'Divine', 'multiplier' => 2.4, 'level_range' => [141, 150]],
    ['name' => 'Mythic', 'multiplier' => 2.5, 'level_range' => [151, 160]],
    ['name' => 'Celestial', 'multiplier' => 2.6, 'level_range' => [161, 170]],
    ['name' => 'Ethereal', 'multiplier' => 2.7, 'level_range' => [171, 180]],
    ['name' => 'Godforged', 'multiplier' => 2.8, 'level_range' => [181, 190]],
    ['name' => 'Deity', 'multiplier' => 3.0, 'level_range' => [191, 200]],
];





// Helper: get prefix config by name
function getPrefixConfig($name, $prefixConfigs)
{
    foreach ($prefixConfigs as $cfg) {
        if ($cfg['name'] === $name)
            return $cfg;
    }
    // fallback
    return $prefixConfigs[0];
}

// Weapon subtype base stats config (per subtype)
$weaponSubtypeBaseStats = [
    'Sword' => ['attack' => 6, 'defence' => 4, 'speed' => 4, 'crit_chance' => 1, 'crit_multi' => 1],
    'Axe' => ['attack' => 8, 'defence' => 1, 'speed' => 2, 'crit_chance' => 1, 'crit_multi' => 0],
    'Dagger' => ['attack' => 4, 'defence' => 1, 'speed' => 7, 'crit_chance' => 2, 'crit_multi' => 2],
    'Spear' => ['attack' => 7, 'defence' => 3, 'speed' => 5, 'crit_chance' => 1, 'crit_multi' => 1],
];

$itemTypes = ['Weapon', 'Helmet', 'Armor', 'Boots', 'Shield', 'Amulet', 'Ring', 'Legs'];

// Define item names specific to each type (now with subtypes for Amulet and Ring)
$itemNames = [
    'Weapon' => ['Sword', 'Axe', 'Spear', 'Dagger'],
    'Helmet' => ['Helmet'],
    'Armor' => ['Armor'],
    'Boots' => ['Boots'],
    'Shield' => ['Shield'],
    'Amulet' => [
        'Amulet of Attack',
        'Amulet of Crit',
        'Amulet of CritMulti',
        'Amulet of Lifesteal',
        'Amulet of Health',
        'Amulet of Defense',
        'Amulet of Stamina'
    ],
    'Ring' => [
        'Ring of Attack',
        'Ring of Crit',
        'Ring of CritMulti',
        'Ring of Lifesteal',
        'Ring of Health',
        'Ring of Defence',
        'Ring of Stamina'
    ],
    'Legs' => ['Legs']
];

// Subtype base stats for Amulet and Ring
$amuletSubtypeBaseStats = [
    'Amulet of Attack' => ['attack' => 2],
    'Amulet of Crit' => ['crit_chance' => 5],
    'Amulet of CritMulti' => ['crit_multi' => 2],
    'Amulet of Lifesteal' => ['life_steal' => 2],
    'Amulet of Health' => ['health' => 10],
    'Amulet of Defense' => ['defence' => 2],
    'Amulet of Stamina' => ['stamina' => 5],
];
$ringSubtypeBaseStats = [
    'Ring of Attack' => ['attack' => 1],
    'Ring of Crit' => ['crit_chance' => 3],
    'Ring of CritMulti' => ['crit_multi' => 1],
    'Ring of Lifesteal' => ['life_steal' => 1],
    'Ring of Health' => ['health' => 5],
    'Ring of Defence' => ['defence' => 1],
    'Ring of Stamina' => ['stamina' => 3],
];

// Define stat categories for each item type (all integer ranges)
$baseStats = [
    'Weapon' => ['attack' => [5, 5], 'defence' => [0, 5], 'crit_chance' => [2, 15], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [0, 0], 'speed' => [0, 0], 'health' => [0, 0], 'stamina' => [0, 0]],
    'Helmet' => ['attack' => [0, 0], 'defence' => [1, 1], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [0, 0], 'speed' => [0, 0], 'health' => [0, 0], 'stamina' => [0, 0]],
    'Armor' => ['attack' => [1, 1], 'defence' => [1, 1], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [0, 0], 'speed' => [0, 0], 'health' => [5, 10], 'stamina' => [0, 0]],
    'Boots' => ['attack' => [1, 1], 'defence' => [0, 5], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [0, 0], 'speed' => [1, 1], 'health' => [0, 0], 'stamina' => [0, 0]],
    'Shield' => ['attack' => [1, 1], 'defence' => [5, 10], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [0, 0], 'speed' => [0, 0], 'health' => [0, 0], 'stamina' => [0, 0]],
    'Amulet' => ['attack' => [0, 0], 'defence' => [0, 0], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [0, 0], 'speed' => [0, 0], 'health' => [0, 0], 'stamina' => [0, 0]],
    'Ring' => ['attack' => [0, 0], 'defence' => [0, 0], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [0, 0], 'speed' => [0, 0], 'health' => [0, 0], 'stamina' => [0, 0]],
    'Legs' => ['attack' => [1, 1], 'defence' => [5, 10], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [8, 0], 'speed' => [0, 0], 'health' => [0, 0], 'stamina' => [0, 0]],
];

// Function to generate random stats within the specified ranges (always integer)
function generateStat($min, $max)
{
    return rand($min, $max);
}

// Function to apply tier scaling based on the prefix config
function applyNameScaling($baseValue, $prefixConfig)
{
    $scalingFactor = $prefixConfig['multiplier'];
    return intval(round($baseValue * $scalingFactor));
}

// Function to generate a random item with scaling based on its base type and prefix/level
function generateItem($type, $itemNames, $baseStats, $prefixConfig, $subtype = null)
{
    // If subtype is not provided, pick random (for non-weapons)
    if ($type === 'Weapon' && $subtype !== null) {
        $name = $prefixConfig['name'] . " " . $subtype;
    } else {
        $subtype = $itemNames[$type][array_rand($itemNames[$type])];
        $name = $prefixConfig['name'] . " " . $subtype;
    }
    $stats = $baseStats[$type];

    // If weapon, override base stats with subtype config
    if ($type === 'Weapon' && isset($GLOBALS['weaponSubtypeBaseStats'][$subtype])) {
        foreach ($GLOBALS['weaponSubtypeBaseStats'][$subtype] as $stat => $val) {
            if (isset($stats[$stat])) {
                $stats[$stat] = [$val, $val];
            }
        }
    }

    // If Amulet, override base stats with amulet subtype config
    if ($type === 'Amulet' && isset($GLOBALS['amuletSubtypeBaseStats'][$subtype])) {
        // Set all stats to [0,0] first (no base stats unless subtype gives)
        foreach ($stats as $stat => $_) {
            $stats[$stat] = [0, 0];
        }
        foreach ($GLOBALS['amuletSubtypeBaseStats'][$subtype] as $stat => $val) {
            $stats[$stat] = [$val, $val];
        }
    }

    // If Ring, override base stats with ring subtype config
    if ($type === 'Ring' && isset($GLOBALS['ringSubtypeBaseStats'][$subtype])) {
        foreach ($stats as $stat => $_) {
            $stats[$stat] = [0, 0];
        }
        foreach ($GLOBALS['ringSubtypeBaseStats'][$subtype] as $stat => $val) {
            $stats[$stat] = [$val, $val];
        }
    }

    foreach ($stats as $stat => $range) {
        // Only roll crit_chance if minimum level is 20 or higher
        if ($stat === 'crit_chance') {
            if ($prefixConfig['level_range'][0] < 20) {
                $stats[$stat] = 0;
                continue;
            }
            $maxCritChancePerItem = 10;
            $minCritChancePerItem = 2;
            $baseValue = generateStat($minCritChancePerItem, $maxCritChancePerItem);
            $stats[$stat] = applyNameScaling($baseValue, $prefixConfig);
            continue;
        }
        // Cap crit_multi at 300% (3.0)
        if ($stat === 'crit_multi') {
            $baseValue = generateStat($range[0], $range[1]);
            $stats[$stat] = applyNameScaling($baseValue, $prefixConfig);
            $stats[$stat] = min($stats[$stat], 3);
            continue;
        }
        // If the stat range is an array with identical min/max, use that value directly
        if (is_array($range) && count($range) == 2 && $range[0] === $range[1]) {
            $baseValue = $range[0];
        } else {
            $baseValue = generateStat($range[0], $range[1]);
        }
        $stats[$stat] = applyNameScaling($baseValue, $prefixConfig);
    }

    // No longer need weapon subtype multipliers, as base stats are set above

    // Ensure multiplier is at least 1
    $levelMultiplier = max(2, intval($prefixConfig['level_range'][0] / 10));
    foreach ($stats as $stat => $value) {
        $stats[$stat] = intval($stats[$stat] * $levelMultiplier);
    }

    // Cap crit_chance after all multipliers (hard cap per item)
    if ($prefixConfig['level_range'][0] >= 20 && isset($stats['crit_chance'])) {
        $stats['crit_chance'] = min($stats['crit_chance'], 12);
    }

    // Exponential gold scaling based on minimum level (gentle curve, slightly higher for low levels)
    $baseGold = rand(50, 80); // was 20
    $gold = intval($baseGold * pow(1.045, $prefixConfig['level_range'][0])); // 4.5% increase per level

    return [
        'name' => $name,
        'type' => $type,
        'level_range' => $prefixConfig['level_range'],
        'attack' => $stats['attack'],
        'defence' => $stats['defence'],
        'crit_chance' => $stats['crit_chance'],
        'crit_multi' => $stats['crit_multi'],
        'life_steal' => $stats['life_steal'],
        'armor' => $stats['armor'],
        'speed' => $stats['speed'],
        'health' => $stats['health'],
        'stamina' => $stats['stamina'],
        'gold' => $gold
    ];
}

// Fetch rarities and modifiers from DB (for rarity preview)
$mysqli = new Database();

// Fetch rarities
$rarities = [];
$rarityRes = $mysqli->fetchAll("SELECT * FROM item_rarities ORDER BY id ASC");
foreach ($rarityRes as $row) {
    $rarities[$row['id']] = $row['name'];
}

// Fetch modifiers
$modifiers = [];
$modRes = $mysqli->fetchAll("SELECT * FROM item_rarity_modifiers ORDER BY rarity_id ASC");
foreach ($modRes as $row) {
    $modifiers[$row['rarity_id']][] = $row;
}

// 2. Helper to apply rarity modifier to a stat value
function getRarityStatRange($base, $mod)
{
    if ($mod['modifier_type'] === 'percent') {
        $min = $base + floor($base * ($mod['min_value'] / 100));
        $max = $base + floor($base * ($mod['max_value'] / 100));
    } else { // fixed
        $min = $base + $mod['min_value'];
        $max = $base + $mod['max_value'];
    }
    return [$min, $max];
}

function generateItemTable($types, $itemNames, $baseStats, $prefixConfigs, $groupBy)
{
    global $rarities, $modifiers;
    $html = '<form method="POST">
    <div class="mb-1" id="generate-item">
                <label for="groupBy">Group by:</label>
                <select id="groupBy" name="groupBy" onchange="this.form.submit()">
                    <option value="type" ' . ($groupBy == 'type' ? 'selected' : '') . '>Item Type</option>
                    <option value="prefix" ' . ($groupBy == 'prefix' ? 'selected' : '') . '>Base Type (Prefix)</option>
                </select>
            </form><br>';

    $groupedItems = [];
    foreach ($prefixConfigs as $prefixConfig) {
        foreach ($types as $type) {
            if ($type === 'Weapon') {
                // Generate one of each weapon subtype
                foreach ($itemNames['Weapon'] as $subtype) {
                    $item = generateItem($type, $itemNames, $baseStats, $prefixConfig, $subtype);
                    $groupKey = $groupBy == 'prefix' ? $prefixConfig['name'] : $type;
                    $groupedItems[$groupKey][] = $item;
                }
            } else {
                $item = generateItem($type, $itemNames, $baseStats, $prefixConfig);
                $groupKey = $groupBy == 'prefix' ? $prefixConfig['name'] : $type;
                $groupedItems[$groupKey][] = $item;
            }
        }
    }

    // Add quick scroll links at the top of the page
    $html .= '<div class="quick-scroll-top mb-4">';
    $html .= '<a href="#rarity-visualization" class="text-indigo-600 underline">Skip to Rarity Modifier Visualization</a>';
    $html .= '</div>';

    $html .= '
<h1 class="text-x2 py-1 mb-1">
  <span class="text-muted-foreground font-light">Battlewarz /</span>
  <span class="font-bold"> Generate Items</span>
</h1>
<div class="w-full overflow-x-auto rpg-panel space-y-4">
  <table class="min-w-full divide-y divide-gray-200 border border-gray-300 rounded-lg shadow-sm bg-white">
    <thead class="bg-gray-100">
      <tr>
        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase">Name</th>
        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase">Type</th>
        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase">Level Range</th>
        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase">Attack</th>
        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase">Defense</th>
        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase">Crit Chance</th>
        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase">Crit Multi</th>
        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase">Life Steal</th>
        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase">Armor</th>
        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase">Speed</th>
        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase">Health</th>
        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase">Stamina</th>
        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase">Gold</th>
        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase">Rarity Preview</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-100">';
    foreach ($groupedItems as $group => $items) {
        $html .= '<tr class="bg-gray-50"><td colspan="14" class="px-3 py-2 font-semibold text-indigo-700">' . ucfirst($group) . '</td></tr>';
        foreach ($items as $item) {
            $levelRange = implode(' - ', $item['level_range']);
            $html .= '<tr class="hover:bg-indigo-50 transition">
                        <td class="px-3 py-2">' . $item['name'] . '</td>
                        <td class="px-3 py-2">' . $item['type'] . '</td>
                        <td class="px-3 py-2">' . $levelRange . '</td>
                        <td class="px-3 py-2">' . $item['attack'] . '</td>
                        <td class="px-3 py-2">' . $item['defence'] . '</td>
                        <td class="px-3 py-2">' . $item['crit_chance'] . '</td>
                        <td class="px-3 py-2">' . $item['crit_multi'] . '</td>
                        <td class="px-3 py-2">' . $item['life_steal'] . '</td>
                        <td class="px-3 py-2">' . $item['armor'] . '</td>
                        <td class="px-3 py-2">' . $item['speed'] . '</td>
                        <td class="px-3 py-2">' . $item['health'] . '</td>
                        <td class="px-3 py-2">' . $item['stamina'] . '</td>
                        <td class="px-3 py-2">' . $item['gold'] . '</td>
                        <td class="px-3 py-2"></td>
                    </tr>';
            // Subrows for each rarity, each stat in its respective column
            foreach ($rarities as $rid => $rname) {
                $subrow = '<tr class="rarity-subrow" style="font-size:11px;">';
                $subrow .= '<td colspan="3"></td>'; // skip name, type, level range
                foreach (['attack', 'defence', 'crit_chance', 'crit_multi', 'life_steal', 'armor', 'speed', 'health', 'stamina', 'gold'] as $stat) {
                    $base = $item[$stat];
                    $mod = null;
                    if (isset($modifiers[$rid])) {
                        foreach ($modifiers[$rid] as $m) {
                            if ($m['stat_name'] === $stat) {
                                $mod = $m;
                                break;
                            }
                        }
                    }
                    if ($stat === 'gold' && $mod && $mod['modifier_type'] === 'percent') {
                        // Apply percent scaling to gold
                        $min = $base + floor($base * ($mod['min_value'] / 100));
                        $max = $base + floor($base * ($mod['max_value'] / 100));
                        $display = ($min == $max) ? $min : "$min-$max";
                        $color = ($min > $base) ? 'green' : (($max < $base) ? 'red' : 'inherit');
                    } elseif ($stat === 'gold') {
                        $display = $base;
                        $color = 'inherit';
                    } elseif ($mod) {
                        list($min, $max) = getRarityStatRange($base, $mod);
                        $display = ($min == $max) ? $min : "$min-$max";
                        $color = ($min > $base) ? 'green' : (($max < $base) ? 'red' : 'inherit');
                    } else {
                        $display = $base;
                        $color = 'inherit';
                    }
                    $subrow .= '<td class="px-3 py-1" style="color:' . $color . '">' . $display . '</td>';
                }
                // Rarity name in last column
                $subrow .= '<td class="px-3 py-1 font-semibold">' . $rname . '</td>';
                $subrow .= '</tr>';
                $html .= $subrow;
            }
        }
    }
    $html .= '</tbody></table></div>';

    return $html;
}

// Adding logic to prefix item names with elemental properties
function applyElementalPrefix($name, $modifiers) {
    $elementalPrefixes = ['Fire', 'Ice', 'Lightning', 'Poison'];
    foreach ($elementalPrefixes as $prefix) {
        if (strpos($name, $prefix) === false && (in_array(strtolower($prefix) . '_attack_flat', $modifiers) || in_array(strtolower($prefix) . '_attack_percent', $modifiers))) {
            return $prefix . ' ' . $name;
        }
    }
    return $name;
}
?>