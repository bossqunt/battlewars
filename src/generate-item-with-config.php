<?php
include_once './config/item-config.php';

$config = include './config/item-config.php';

/**
 * Generate an item with modifiers based on rarity and level range using the centralized configuration.
 *
 * @param string $type The type of the item (e.g., Weapon, Armor).
 * @param string $rarity The rarity of the item (e.g., Common, Rare).
 * @return array The generated item with modifiers.
 */
function generateItemWithConfig($type, $rarity) {
    global $config;

    // Get item type configuration
    $itemTypeConfig = $config['item_types'][$type] ?? null;
    if (!$itemTypeConfig) {
        throw new Exception("Invalid item type: $type");
    }

    // Get rarity configuration
    $rarityConfig = $config['rarities'][$rarity] ?? null;
    if (!$rarityConfig) {
        throw new Exception("Invalid rarity: $rarity");
    }

    // Base item
    $item = [
        'type' => $type,
        'rarity' => $rarity,
        'base_stats' => $itemTypeConfig['base_stats'],
        'modifiers' => []
    ];

    // Determine the number of modifiers
    $numModifiers = is_array($rarityConfig['modifiers'])
        ? rand($rarityConfig['modifiers'][0], $rarityConfig['modifiers'][1])
        : $rarityConfig['modifiers'];

    // Roll modifiers
    $availableModifiers = $itemTypeConfig['modifiers'];
    while (count($item['modifiers']) < $numModifiers && !empty($availableModifiers)) {
        $modifier = $availableModifiers[array_rand($availableModifiers)];

        // Check for special probabilities (e.g., physical_attack_percent)
        if (isset($config['probabilities'][$modifier . '_percent']) && rand(0, 100) < $config['probabilities'][$modifier . '_percent']) {
            $item['modifiers'][] = [
                'name' => $modifier . '_percent',
                'tier' => rollTier(),
                'value' => rollModifierValue($modifier . '_percent', rollTier())
            ];
        }

        $tier = rollTier();
        $item['modifiers'][] = [
            'name' => $modifier,
            'tier' => $tier,
            'value' => rollModifierValue($modifier, $tier)
        ];

        $availableModifiers = array_diff($availableModifiers, [$modifier]);
    }

    return $item;
}

/**
 * Roll a tier for a modifier.
 *
 * @return string The rolled tier (e.g., t0, t1).
 */
function rollTier() {
    global $config;
    $tiers = array_keys($config['tiers']);
    return $tiers[array_rand($tiers)];
}

/**
 * Roll a value for a modifier based on its tier.
 *
 * @param string $modifier The name of the modifier.
 * @param string $tier The tier of the modifier (e.g., t0, t1).
 * @return int The rolled value for the modifier.
 */
function rollModifierValue($modifier, $tier) {
    global $config;
    $range = $config['tiers'][$tier] ?? [1, 5];
    return rand($range[0], $range[1]);
}

// Example usage
$generatedItem = generateItemWithConfig('Weapon', 'Rare');
echo "<pre>";
print_r($generatedItem);
echo "</pre>";