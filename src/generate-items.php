<?php
session_start();
include 'includes/sidebar.php';
require_once './includes/item-generator.php'; // <-- shared item logic

// Get the selected group-by option from the dropdown
$groupBy = isset($_POST['groupBy']) ? $_POST['groupBy'] : 'type';

// Generate and display the item table
echo generateItemTable($itemTypes, $itemNames, $baseStats, $prefixConfigs, $groupBy);

// --- Rarity visualization section ---

// Handle dropdown selection
$lowTierName = isset($_POST['lowTier']) ? $_POST['lowTier'] : 'Wooden';
$highTierName = isset($_POST['highTier']) ? $_POST['highTier'] : 'Godforged';

// Dropdown form
echo '<div class="mt-10" id="rarity-visualization">';
echo '<h2 class="text-lg font-bold mb-2">Rarity Modifier Visualization: Low vs High Tier Comparison</h2>';
echo '<form method="POST" class="mb-4 flex flex-wrap gap-4 items-center">';
echo '<label for="lowTier" class="font-semibold">Low Tier:</label>';
echo '<select name="lowTier" id="lowTier" class="border px-2 py-1">';
foreach ($prefixConfigs as $cfg) {
    $selected = ($cfg['name'] === $lowTierName) ? 'selected' : '';
  echo '<option value="' . htmlspecialchars($cfg['name']) . '" ' . $selected . '>' . htmlspecialchars( $cfg['name'] . ' (' . $cfg['level_range'][0] . '-' ) . $cfg['level_range'][1] . ')' . '</option>';
}
echo '</select>';
echo '<label for="highTier" class="font-semibold ml-4">High Tier:</label>';
echo '<select name="highTier" id="highTier" class="border px-2 py-1">';
foreach ($prefixConfigs as $cfg) {
    $selected = ($cfg['name'] === $highTierName) ? 'selected' : '';
    echo '<option value="' . htmlspecialchars($cfg['name']) . '" ' . $selected . '>' . htmlspecialchars( $cfg['name'] . ' (' . $cfg['level_range'][0] . '-' ) . $cfg['level_range'][1] . ')' . '</option>';
}
echo '</select>';
echo '<button type="submit" class="ml-4 px-3 py-1 bg-indigo-600 text-white rounded">Compare</button>';
echo '</form>';

echo '<div class="overflow-x-auto">';
echo '<table class="min-w-max text-xs border mb-6"><thead><tr>';
echo '<th class="px-2 py-1"></th>';
echo '<th class="px-2 py-1 text-center" colspan="' . (count($baseStats[$itemTypes[0]])+2) . '">Low Tier (' . htmlspecialchars($lowTierName) . ')</th>';
echo '<th class="px-2 py-1 text-center" colspan="' . (count($baseStats[$itemTypes[0]])+2) . '">High Tier (' . htmlspecialchars($highTierName) . ')</th>';
echo '</tr></thead><tbody>';

foreach ($itemTypes as $type) {
    $prefixConfigLow = null;
    $prefixConfigHigh = null;
    foreach ($prefixConfigs as $cfg) {
        if ($cfg['name'] === $lowTierName) $prefixConfigLow = $cfg;
        if ($cfg['name'] === $highTierName) $prefixConfigHigh = $cfg;
    }
    if (!$prefixConfigLow || !$prefixConfigHigh) continue;

    $itemLow = generateItem($type, $itemNames, $baseStats, $prefixConfigLow);
    $itemHigh = generateItem($type, $itemNames, $baseStats, $prefixConfigHigh);

    echo '<tr class="bg-gray-100"><td class="font-semibold px-2 py-1" colspan="' . (2*(count($baseStats[$type])+2)+1) . '">' . htmlspecialchars($type) . '</td></tr>';
    // Table headers for stats
    echo '<tr>';
    echo '<td></td>';
    echo '<td class="px-2 py-1 font-semibold">Level Range </td>';
    foreach ($baseStats[$type] as $stat => $_) {
        echo '<td class="px-2 py-1 font-semibold">' . ucfirst($stat) . '</td>';
    }
    echo '<td class="px-2 py-1 font-semibold">Rarity</td>';
    echo '<td class="px-2 py-1 font-semibold">Level Range</td>';
    foreach ($baseStats[$type] as $stat => $_) {
        echo '<td class="px-2 py-1 font-semibold">' . ucfirst($stat) . '</td>';
    }
    echo '<td class="px-2 py-1 font-semibold">Rarity</td>';
    echo '</tr>';

    foreach ($rarities as $rid => $rname) {
        echo '<tr>';
        // Low tier
        echo '<td></td>';
        echo '<td class="px-2 py-1">' . implode(' - ', $itemLow['level_range']) . '</td>';
        foreach ($baseStats[$type] as $stat => $_) {
            // ...existing code for stat display (low tier)...
            $base = $itemLow[$stat];
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
                $min = $base + floor($base * ($mod['min_value'] / 100));
                $max = $base + floor($base * ($mod['max_value'] / 100));
                $display = ($min == $max) ? $min : "$min - $max";
                $class = ($min > $base) ? 'text-green-600' : (($max < $base) ? 'text-red-600' : '');
                echo '<td class="px-2 py-1 ' . $class . '">' . $display . '</td>';
            } elseif ($mod) {
                list($min, $max) = getRarityStatRange($base, $mod);
                $display = ($min == $max) ? $min : "$min - $max";
                $class = ($min > $base) ? 'text-green-600' : (($max < $base) ? 'text-red-600' : '');
                echo '<td class="px-2 py-1 ' . $class . '">' . $display . '</td>';
            } else {
                echo '<td class="px-2 py-1">' . $base . '</td>';
            }
        }
        echo '<td class="px-2 py-1 font-semibold">' . $rname . '</td>';
        // High tier
        echo '<td class="px-2 py-1">' . implode(' - ', $itemHigh['level_range']) . '</td>';
        foreach ($baseStats[$type] as $stat => $_) {
            // ...existing code for stat display (high tier)...
            $base = $itemHigh[$stat];
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
                $min = $base + floor($base * ($mod['min_value'] / 100));
                $max = $base + floor($base * ($mod['max_value'] / 100));
                $display = ($min == $max) ? $min : "$min - $max";
                $class = ($min > $base) ? 'text-green-600' : (($max < $base) ? 'text-red-600' : '');
                echo '<td class="px-2 py-1 ' . $class . '">' . $display . '</td>';
            } elseif ($mod) {
                list($min, $max) = getRarityStatRange($base, $mod);
                $display = ($min == $max) ? $min : "$min - $max";
                $class = ($min > $base) ? 'text-green-600' : (($max < $base) ? 'text-red-600' : '');
                echo '<td class="px-2 py-1 ' . $class . '">' . $display . '</td>';
            } else {
                echo '<td class="px-2 py-1">' . $base . '</td>';
            }
        }
        echo '<td class="px-2 py-1 font-semibold">' . $rname . '</td>';
        echo '</tr>';
    }
    
}
echo '</tbody></table></div>';
 // Add quick scroll links at the bottom of the page
 echo "<div class='quick-scroll-top mb-1'>";
 echo "<a href='#generate-item' class='text-indigo-600 underline'>Back to Top</a>";
 echo "</div>";

echo '</div>';

?>