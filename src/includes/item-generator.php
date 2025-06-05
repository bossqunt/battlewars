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
// 3. Each item type must have a set of base stats (physical_attack, Armor, etc.)
// 4. Modifiers can roll in "tiers", for example, t0 to t6, where each tier has a different set of modifier rolls for example.. t1 = 1-5%, t2 = 5-10%, t3 = 10-15%, etc. (or whatever you want)
// 5. For modifiers which roll physical_physical_attack, there should be an increased probability to roll physical_physical_attack_percent, this will be in addition to the base state of the item, and the physical_physical_attack modifier
// 6. For rings and amulets, the base stats should be different from weapons and armor, and should have a different set of modifiers (for example, rings can roll crit_chance_percent, crit_multi_percent, life_steal_percent, etc.)
// I have included all the new modifiers, and I've included all modifiers based on item type

$modifiers = [
    'physical_physical_attack',
    'physical_physical_attack_percent',
    'physical_defence',
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
    'fire_physical_attack', 
    'fire_physical_attack_percent',
    'ice_physical_attack', 
    'ice_physical_attack_percent',
    'lightning_physical_attack', 
    'lightning_physical_attack_percent',
    'poison_physical_attack', 
    'poison_physical_attack_percent',
    'fire_defence_percent',
    'ice_defence_percent',
    'lightning_defence_percent',
    'poison_defence_percent',
    'gold'
];

// Adding new elemental physical_attack and Armor modifiers
$modifiers = array_merge($modifiers, [
    'fire_physical_attack_flat', 'fire_physical_attack_percent',
    'ice_physical_attack_flat', 'ice_physical_attack_percent',
    'lightning_physical_attack_flat', 'lightning_physical_attack_percent',
    'poison_physical_attack_flat', 'poison_physical_attack_percent',
    'fire_Armor_flat', 'fire_Armor_percent',
    'ice_Armor_flat', 'ice_Armor_percent',
    'lightning_Armor_flat', 'lightning_Armor_percent',
    'poison_Armor_flat', 'poison_Armor_percent'
]);

$modifiersByType = [
    'Weapon' => [
        'physical_physical_attack', 
        'physical_physical_attack_percent', 
        'crit_chance_percent', 
        'crit_multi_percent', 
        'life_steal_percent', 
        'speed', 
        'speed_percent', 
        'fire_physical_attack', 
        'fire_physical_attack_percent', 
        'ice_physical_attack', 
        'ice_physical_attack_percent', 
        'lightning_physical_attack', 
        'lightning_physical_attack_percent', 
        'poison_physical_attack', 
        'poison_physical_attack_percent'
    ],
    'Helmet' => [
        'armor', 
        'armor_percent', 
        'health', 
        'health_percent', 
        'health_regen', 
        'physical_defence',
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
    [   
        'name' => 'Starter', 
        'multiplier' => 0.7, 
        'level_range' => [1, 10],
    ],
    [   'name' => 'Wooden', 
        'multiplier' => 1.0, 
        'level_range' => [1, 10]
    ],
    [   'name' => 'Cloth', 
        'multiplier' => 1.1, 
        'level_range' => [11, 20]
    ],
    [   'name' => 'Leather', 
        'multiplier' => 1.2, 
        'level_range' => [21, 30]
    ],
    [   'name' => 'Bronze', 
        'multiplier' => 1.3, 
        'level_range' => [31, 40]
    ],
    [   'name' => 'Copper', 
        'multiplier' => 1.3, 
        'level_range' => [31, 40]
    ],
    [   'name' => 'Silver', 
        'multiplier' => 1.4, 
        'level_range' => [41, 50]
    ],
    [   'name' => 'Gold', 
        'multiplier' => 1.5, 
        'level_range' => [51, 60]
    ],
    [   'name' => 'Platinum',
        'multiplier' => 1.6, 
        'level_range' => [61, 70]
],
    [   'name' => 'Titanium', 
        'multiplier' => 1.7, 
        'level_range' => [71, 80]
    ],
    [   'name' => 'Mithril', 
        'multiplier' => 1.8, 
        'level_range' => [81, 90]
    ],
    [   'name' => 'Adamantite', 
        'multiplier' => 1.9, 
        'level_range' => [91, 100]
    ],
    [   'name' => 'Obsidian', 
        'multiplier' => 2.0, 
        'level_range' => [101, 110]
    ],
    [   'name' => 'Dragon', 
        'multiplier' => 2.1, 
        'level_range' => [111, 120]
    ],
    ['name' => 'Celestial', 'multiplier' => 2.2, 'level_range' => [121, 130]],
    ['name' => 'Ethereal', 'multiplier' => 2.3, 'level_range' => [131, 140]],
    ['name' => 'Special', 'multiplier' => 2.4, 'level_range' => [141, 150]],
    ['name' => 'Divine', 'multiplier' => 2.5, 'level_range' => [151, 160]],
    ['name' => 'Mythical', 'multiplier' => 2.6, 'level_range' => [161, 170]],
    ['name' => 'Legendary', 'multiplier' => 2.7, 'level_range' => [171, 180]],
    ['name' => 'Ancient', 'multiplier' => 2.8, 'level_range' => [181, 190]],
    ['name' => 'Eldritch', 'multiplier' => 3.0, 'level_range' => [191, 200]],
    ['name' => 'Cursed', 'multiplier' => 3.1, 'level_range' => [201, 210]],
    ['name' => 'Doomed', 'multiplier' => 3.2, 'level_range' => [211, 220]],
    ['name' => 'Fallen', 'multiplier' => 3.3, 'level_range' => [221, 230]],
    ['name' => 'Forsaken', 'multiplier' => 3.4, 'level_range' => [231, 240]],
    ['name' => 'Wicked', 'multiplier' => 3.5, 'level_range' => [241, 250]],
    ['name' => 'Vile', 'multiplier' => 3.6, 'level_range' => [251, 260]],
    ['name' => 'Sinister', 'multiplier' => 3.7, 'level_range' => [261, 270]],
    ['name' => 'Malicious', 'multiplier' => 3.8, 'level_range' => [271, 280]],
    ['name' => 'Infernal', 'multiplier' => 3.9, 'level_range' => [281, 290]],
    ['name' => 'Nether', 'multiplier' => 4.0, 'level_range' => [291, 300]],
    ['name' => 'Abyssal', 'multiplier' => 4.1, 'level_range' => [301, 310]],
    ['name' => 'Infernal', 'multiplier' => 4.2, 'level_range' => [311, 320]],
    ['name' => 'Void', 'multiplier' => 4.3, 'level_range' => [321, 330]],
    ['name' => 'Eternal', 'multiplier' => 4.4, 'level_range' => [331, 340]],
    ['name' => 'Cosmic', 'multiplier' => 4.5, 'level_range' => [341, 350]],
    ['name' => 'Stellar', 'multiplier' => 4.6, 'level_range' => [351, 360]],
    ['name' => 'Galactic', 'multiplier' => 4.7, 'level_range' => [361, 370]],
    ['name' => 'Celestial', 'multiplier' => 4.8, 'level_range' => [371, 380]],
    ['name' => 'Astral', 'multiplier' => 4.9, 'level_range' => [381, 390]],
    ['name' => 'Transcendent', 'multiplier' => 5.0, 'level_range' => [391, 400]],
    ['name' => 'Omniscient', 'multiplier' => 5.1, 'level_range' => [401, 410]],
    ['name' => 'Supreme', 'multiplier' => 5.2, 'level_range' => [411, 420]],
    ['name' => 'Ascendant', 'multiplier' => 5.3, 'level_range' => [421, 430]],
    ['name' => 'Primordial', 'multiplier' => 5.4, 'level_range' => [431, 440]],
    ['name' => 'Elysian', 'multiplier' => 5.5, 'level_range' => [441, 450]],
    ['name' => 'Seraphic', 'multiplier' => 5.6, 'level_range' => [451, 460]],
    ['name' => 'Empyrean', 'multiplier' => 5.7, 'level_range' => [461, 470]],
    ['name' => 'Transcendent', 'multiplier' => 5.8, 'level_range' => [471, 480]],
    ['name' => 'Divine', 'multiplier' => 5.9, 'level_range' => [481, 490]],
    ['name' => 'Godly', 'multiplier' => 6.0, 'level_range' => [491, 500]]
];

// Helper: get prefix config by name
function getPrefixConfig($name, $prefixConfigs)
{
    if (!isset($prefixConfigs[0])) {
        trigger_error('Prefix configuration is missing or invalid.', E_USER_WARNING);
        return null; // Return a default value or handle the error gracefully
    }

    foreach ($prefixConfigs as $cfg) {
        if (isset($cfg['name']) && $cfg['name'] === $name) {
            return $cfg;
        }
    }
    // fallback
    return $prefixConfigs[0];
}

// Weapon subtype base stats config (per subtype)
$weaponSubtypeBaseStats = [
    'Sword' => ['physical_attack' => 6, 'armor' => 4, 'speed' => 0, 'crit_chance' => 0, 'crit_multi' => 0],
    'Axe' => ['physical_attack' => 8, 'armor' => 1, 'speed' => 0, 'crit_chance' => 0, 'crit_multi' => 0],
    'Dagger' => ['physical_attack' => 4, 'armor' => 1, 'speed' => 0, 'crit_chance' => 0, 'crit_multi' => 0],
    'Spear' => ['physical_attack' => 7, 'armor' => 3, 'speed' => 0, 'crit_chance' => 0, 'crit_multi' => 0],
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
        'Amulet of Defence',
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
    'Amulet of Attack' => ['physical_attack' => 2],
    'Amulet of Crit' => ['crit_chance' => 5],
    'Amulet of CritMulti' => ['crit_multi' => 2],
    'Amulet of Lifesteal' => ['life_steal' => 2],
    'Amulet of Health' => ['health' => 10],
    'Amulet of Armor' => ['armor' => 2],
    'Amulet of Stamina' => ['stamina' => 5],
];
$ringSubtypeBaseStats = [
    'Ring of Attack' => ['physical_attack' => 1],
    'Ring of Crit' => ['crit_chance' => 3],
    'Ring of CritMulti' => ['crit_multi' => 1],
    'Ring of Lifesteal' => ['life_steal' => 1],
    'Ring of Health' => ['health' => 5],
    'Ring of Defence' => ['armor' => 1],
    'Ring of Stamina' => ['stamina' => 3],
];

// Define stat categories for each item type (all integer ranges)
$baseStats = [
    'Weapon' => ['physical_attack' => [5, 5], 'armor' => [0, 5], 'crit_chance' => [2, 15], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [0, 0], 'speed' => [0, 0], 'health' => [0, 0], 'stamina' => [0, 0]],
    'Helmet' => ['physical_attack' => [0, 0], 'armor' => [1, 1], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [0, 0], 'speed' => [0, 0], 'health' => [0, 0], 'stamina' => [0, 0]],
    'Armor' => ['physical_attack' => [1, 1], 'armor' => [1, 1], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [0, 0], 'speed' => [0, 0], 'health' => [5, 10], 'stamina' => [0, 0]],
    'Boots' => ['physical_attack' => [1, 1], 'armor' => [0, 5], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [0, 0], 'speed' => [1, 1], 'health' => [0, 0], 'stamina' => [0, 0]],
    'Shield' => ['physical_attack' => [1, 1], 'armor' => [5, 10], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [0, 0], 'speed' => [0, 0], 'health' => [0, 0], 'stamina' => [0, 0]],
    'Amulet' => ['physical_attack' => [0, 0], 'armor' => [0, 0], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [0, 0], 'speed' => [0, 0], 'health' => [0, 0], 'stamina' => [0, 0]],
    'Ring' => ['physical_attack' => [0, 0], 'armor' => [0, 0], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [0, 0], 'speed' => [0, 0], 'health' => [0, 0], 'stamina' => [0, 0]],
    'Legs' => ['physical_attack' => [1, 1], 'armor' => [5, 10], 'crit_chance' => [0, 0], 'crit_multi' => [0, 0], 'life_steal' => [0, 0], 'armor' => [8, 0], 'speed' => [0, 0], 'health' => [0, 0], 'stamina' => [0, 0]],
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

function rollModifiers($type, $rarity, $modifiersByType, $tiers) {
    $numModifiers = [
        'Common' => 1,
        'Uncommon' => 2,
        'Rare' => 3,
        'Epic' => rand(3, 4),
        'Legendary' => rand(5, 6),
        'Godly' => 6
    ][$rarity];

    $availableModifiers = $modifiersByType[$type] ?? [];
    $rolledModifiers = [];

    while (count($rolledModifiers) < $numModifiers && !empty($availableModifiers)) {
        $modifier = array_splice($availableModifiers, array_rand($availableModifiers), 1)[0];

        // Roll tier for the modifier
        $tier = array_rand($tiers);
        $value = rand($tiers[$tier]['min'], $tiers[$tier]['max']);

        $rolledModifiers[] = [
            'modifier' => $modifier,
            'tier' => $tier,
            'value' => $value
        ];

        // If the modifier is an attack type, consider rolling its percent counterpart
        if (strpos($modifier, 'attack') !== false && strpos($modifier, 'percent') === false) {
            $percentModifier = $modifier . '_percent';
            if (in_array($percentModifier, $availableModifiers) && rand(0, 100) < 50) { // 50% chance
                $percentTier = array_rand($tiers);
                $percentValue = rand($tiers[$percentTier]['min'], $tiers[$percentTier]['max']);

                $rolledModifiers[] = [
                    'modifier' => $percentModifier,
                    'tier' => $percentTier,
                    'value' => $percentValue
                ];

                // Remove the percent modifier from available modifiers
                $availableModifiers = array_diff($availableModifiers, [$percentModifier]);
            }
        }
    }

    return $rolledModifiers;
}

// Function to generate a random item with scaling based on its base type and prefix/level
function generateItem($type, $itemNames, $baseStats, $prefixConfig, $subtype = null, $rarity = 'Common') {
    global $modifiersByType;

    // If subtype is not provided, pick random (for non-weapons)
    if ($type === 'Weapon' && $subtype !== null) {
        $name = $prefixConfig['name'] . " " . $subtype;
    } else {
        $subtype = $itemNames[$type][array_rand($itemNames[$type])];
        $name = $prefixConfig['name'] . " " . $subtype;
    }

    $stats = $baseStats[$type];

    // Roll modifiers based on rarity
    $tiers = [
        't0' => ['min' => 1, 'max' => 5],
        't1' => ['min' => 6, 'max' => 10],
        't2' => ['min' => 11, 'max' => 15],
        't3' => ['min' => 16, 'max' => 20],
        't4' => ['min' => 21, 'max' => 25],
        't5' => ['min' => 26, 'max' => 30],
        't6' => ['min' => 31, 'max' => 35]
    ];

    $rolledModifiers = rollModifiers($type, $rarity, $modifiersByType, $tiers);

    return [
        'name' => $name,
        'type' => $type,
        'rarity' => $rarity,
        'level_range' => $prefixConfig['level_range'],
        'base_stats' => $stats,
        'modifiers' => $rolledModifiers
    ];
}

function generateItemTable($types, $itemNames, $baseStats, $prefixConfigs, $groupBy) {
    global $rarities, $modifiersByType;

    $html = '<table class="min-w-full divide-y divide-gray-200 border border-gray-300 rounded-lg shadow-sm bg-white">';
    $html .= '<thead><tr>';
    $html .= '<th>Name</th><th>Type</th><th>Rarity</th><th>Modifiers</th>';
    $html .= '</tr></thead><tbody>';

    foreach ($prefixConfigs as $prefixConfig) {
        foreach ($types as $type) {
            $item = generateItem($type, $itemNames, $baseStats, $prefixConfig, null, 'Rare');
            $html .= '<tr>';
            $html .= '<td>' . $item['name'] . '</td>';
            $html .= '<td>' . $item['type'] . '</td>';
            $html .= '<td>' . $item['rarity'] . '</td>';
            $html .= '<td>';
            foreach ($item['modifiers'] as $mod) {
                $html .= $mod['modifier'] . ' (Tier: ' . $mod['tier'] . ', Value: ' . $mod['value'] . ')<br>';
            }
            $html .= '</td>';
            $html .= '</tr>';
        }
    }

    $html .= '</tbody></table>';

    return $html;
}

// Adding logic to prefix item names with elemental properties
function applyElementalPrefix($name, $modifiers) {
    $elementalPrefixes = ['Fire', 'Ice', 'Lightning', 'Poison'];
    foreach ($elementalPrefixes as $prefix) {
        if (strpos($name, $prefix) === false && (in_array(strtolower($prefix) . '_physical_attack_flat', $modifiers) || in_array(strtolower($prefix) . '_physical_attack_percent', $modifiers))) {
            return $prefix . ' ' . $name;
        }
    }
    return $name;
}
?>