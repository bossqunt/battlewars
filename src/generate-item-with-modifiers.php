<?php
include_once './includes/item-generator.php';

/**
 * Generate an item with modifiers based on rarity and level range.
 *
 * @param string $type The type of the item (e.g., Weapon, Armor).
 * @param array $itemNames The list of item names by type.
 * @param array $baseStats The base stats for the item type.
 * @param array $prefixConfig The prefix configuration for the item's level range.
 * @param string $rarity The rarity of the item (e.g., Common, Rare).
 * @param string|null $subtype The subtype of the item (e.g., Sword, Axe).
 * @return array The generated item with modifiers.
 */
function generateItemWithModifiers($type, $itemNames, $baseStats, $prefixConfig, $rarity, $subtype = null) {
    global $modifiersByType;

    // Generate base item
    $item = generateItem($type, $itemNames, $baseStats, $prefixConfig, $subtype);

    // Determine the number of modifiers based on rarity
    $rarityModifiers = [
        'Common' => 1,
        'Uncommon' => 2,
        'Rare' => 3,
        'Epic' => rand(3, 4),
        'Legendary' => rand(5, 6),
        'Godly' => 6,
    ];
    $numModifiers = $rarityModifiers[$rarity];

    // Roll modifiers
    $availableModifiers = $modifiersByType[$type] ?? [];
    $rolledModifiers = [];
    while (count($rolledModifiers) < $numModifiers && !empty($availableModifiers)) {
        $modifier = $availableModifiers[array_rand($availableModifiers)];

        // Increase probability for all damage types to roll their percent-based modifiers
        if (str_ends_with($modifier, '_attack') && rand(0, 100) < 50) {
            $rolledModifiers[] = $modifier . '_percent';
        }

        $rolledModifiers[] = $modifier;
        $availableModifiers = array_diff($availableModifiers, $rolledModifiers);
    }

    // Apply modifiers to the item
    foreach ($rolledModifiers as $modifier) {
        $tier = rand(0, 6); // Roll tier (t0 to t6)
        $modifierValue = rollModifierValue($modifier, $tier);

        // Ensure all modifiers, including attacks, crit, speed, etc., include tier information
        $item[$modifier] = [
            'value' => $modifierValue,
            'tier' => 't' . $tier // Include tier information for all modifiers
        ];
    }

    // Add rarity and modifiers to the item
    $item['rarity'] = $rarity;
    $item['modifiers'] = $rolledModifiers;

    return $item;
}

/**
 * Roll a value for a modifier based on its tier.
 *
 * @param string $modifier The name of the modifier.
 * @param int $tier The tier of the modifier (0 to 6).
 * @return int The rolled value for the modifier.
 */
function rollModifierValue($modifier, $tier) {
    $tierRanges = [
        0 => [1, 5],
        1 => [5, 10],
        2 => [10, 15],
        3 => [15, 20],
        4 => [20, 25],
        5 => [25, 30],
        6 => [30, 35],
    ];
    $range = $tierRanges[$tier] ?? [1, 5];
    return rand($range[0], $range[1]);
}

// Example usage
$generatedItem = generateItemWithModifiers('Weapon', $itemNames, $baseStats, $prefixConfigs[0], 'Godly', 'Sword');
echo "<pre>";
print_r($generatedItem);
echo "</pre>";