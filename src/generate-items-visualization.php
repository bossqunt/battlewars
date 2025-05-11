<?php
include_once './includes/item-generator.php';

// Define early and late game prefixes for visualization
$earlyGamePrefixes = ['Starter', 'Wooden', 'Cloth'];
$lateGamePrefixes = ['Mythic', 'Celestial', 'Ethereal'];

// Generate early game items
$earlyGameItems = [];
foreach ($earlyGamePrefixes as $prefixName) {
    $prefixConfig = getPrefixConfig($prefixName, $prefixConfigs);
    foreach ($itemTypes as $type) {
        if ($type === 'Weapon') {
            foreach ($itemNames['Weapon'] as $subtype) {
                $earlyGameItems[] = generateItem($type, $itemNames, $baseStats, $prefixConfig, $subtype);
            }
        } else {
            $earlyGameItems[] = generateItem($type, $itemNames, $baseStats, $prefixConfig);
        }
    }
}

// Generate late game items
$lateGameItems = [];
foreach ($lateGamePrefixes as $prefixName) {
    $prefixConfig = getPrefixConfig($prefixName, $prefixConfigs);
    foreach ($itemTypes as $type) {
        if ($type === 'Weapon') {
            foreach ($itemNames['Weapon'] as $subtype) {
                $lateGameItems[] = generateItem($type, $itemNames, $baseStats, $prefixConfig, $subtype);
            }
        } else {
            $lateGameItems[] = generateItem($type, $itemNames, $baseStats, $prefixConfig);
        }
    }
}

// Display the items in a table
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Rarity Visualization</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <h1>Item Rarity Visualization</h1>

    <h2>Early Game Items</h2>
    <?php echo generateItemTable($itemTypes, $itemNames, $baseStats, $prefixConfigs, 'type'); ?>

    <h2>Late Game Items</h2>
    <?php echo generateItemTable($itemTypes, $itemNames, $baseStats, $prefixConfigs, 'type'); ?>
</body>
</html>