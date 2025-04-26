<?php
ob_start();
require './includes/sidebar.php';
require_once './controller/Database.php';
require_once './controller/AuthCheck.php';
require_once './includes/item-generator.php'; // <-- NEW: shared item logic

if ($isAdmin != 1) {
    exit;
}

$conn = new Database();

$levelRanges = [
    "1-10" => [1, 10],
    "11-20" => [11, 20],
    "21-30" => [21, 30],
    "31-40" => [31, 40],
    "41-50" => [41, 50],
    "51-60" => [51, 60],
    "61-70" => [61, 70],
    "71-80" => [71, 80],
    "81-90" => [81, 90],
    "91-100" => [91, 100],
    "101-110" => [101, 110],
    "111-120" => [111, 120],
    "121-130" => [121, 130],
    "131-140" => [131, 140],
    "141-150" => [141, 150],
    "151-160" => [151, 160],
    "161-170" => [161, 170],
    "171-180" => [171, 180],
    "181-190" => [181, 190],
    "191-200" => [191, 200]
];

$categoryMultipliers = [
    'weak' =>   ['hp' => 5,  'attack' => 1,   'defence' => 0.5, 'speed' => 2,   'exp' => 0.5, 'gold' => 0.5],
    'mid' =>    ['hp' => 10, 'attack' => 2,   'defence' => 1.5, 'speed' => 1.5, 'exp' => 1,   'gold' => 1],
    'strong' => ['hp' => 20, 'attack' => 4,   'defence' => 2.5, 'speed' => 1.2, 'exp' => 2,   'gold' => 3],
    'boss' =>   ['hp' => 50, 'attack' => 8,   'defence' => 5,   'speed' => 1,   'exp' => 5,   'gold' => 8],
];

// Category-based prefixes
$prefixes = [
  'weak' => [
      'Tiny', 'Frail', 'Lesser', 'Weak', 'Cracked', 'Wilted', 'Feeble', 'Dull',
      'Dim', 'Rusty', 'Small', 'Broken', 'Soft', 'Bent', 'Minor', 'Puny',
      'Waning', 'Limp', 'Shabby', 'Wretched', 'Flimsy', 'Dented', 'Pale',
      'Grimy', 'Basic', 'Brittle', 'Dusty'
  ],
  'mid' => [
      'Savage', 'Cursed', 'Dark', 'Wild', 'Twisted', 'Fiery', 'Shadow', 'Grim',
      'Raging', 'Dire', 'Spiteful', 'Poisoned', 'Bleeding', 'Burning', 'Wicked',
      'Vile', 'Haunted', 'Bloodstained', 'Enraged', 'Howling', 'Frenzied',
      'Echoing', 'Piercing', 'Gnashing', 'Relentless', 'Ashen', 'Decayed'
  ],
  'strong' => [
      'Ancient', 'Fierce', 'Vicious', 'Venomous', 'Brutal', 'Savage', 'Terror',
      'Ironclad', 'Bonecrushing', 'Nightmarish', 'Infernal', 'Thundering',
      'Barbed', 'Warlord', 'Hellbound', 'Fanged', 'Behemoth', 'Unholy',
      'Spiked', 'Wailing', 'Colossal', 'Crushing', 'Wicked', 'Obsessed',
      'Darksteel', 'Void-touched', 'Sinister'
  ],
  'boss' => [
      'Dread', 'Elder', 'Mythic', 'Apocalyptic', 'Hellborn', 'Titanic',
      'Worldbreaker', 'Cataclysmic', 'Corrupted', 'Demonic', 'Nether', 'Primordial',
      'Voidspawn', 'Godless', 'Soulflayer', 'Nightshade', 'Ruinous', 'Endless',
      'Emberlord', 'Stormcaller', 'Chrono', 'Bloodlord', 'Revenant', 'Oblivion',
      'Deathbringer', 'Plaguefather', 'King', 'Queen'
  ]
];

$suffixes = [''];

// 1. Expanded monster base names per level range (10+ per range)
$monsterBaseNames = [
    "1-10" => ['Rat', 'Bat', 'Slime', 'Spider', 'Beetle', 'Worm', 'Frog', 'Moth', 'Ant', 'Maggot', 'Snail', 'Mouse'],
    "11-20" => ['Goblin', 'Wolf', 'Zombie', 'Skeleton', 'Imp', 'Bandit', 'Kobold', 'Gnoll', 'Jackal', 'Vulture', 'Scorpion', 'Toad'],
    "21-30" => ['Orc', 'Bandit', 'Ghoul', 'Warg', 'Harpy', 'Lizardman', 'Boar', 'Crocodile', 'Hyena', 'Vampire Bat', 'Ghast', 'Shade'],
    "31-40" => ['Troll', 'Ogre', 'Wraith', 'Basilisk', 'Gargoyle', 'Mummy', 'Minotaur', 'Dire Wolf', 'Giant Spider', 'Specter', 'Wight', 'Bugbear'],
    "41-50" => ['Minotaur', 'Werewolf', 'Vampire', 'Lich', 'Chimera', 'Banshee', 'Golem', 'Wendigo', 'Succubus', 'Incubus', 'Giant Ant', 'Hellhound'],
    "51-60" => ['Giant', 'Golem', 'Wyvern', 'Hydra', 'Specter', 'Cyclops', 'Basilisk', 'Stone Golem', 'Frost Giant', 'Fire Elemental', 'Earth Elemental', 'Storm Giant'],
    "61-70" => ['Drake', 'Manticore', 'Banshee', 'Djinn', 'Salamander', 'Efreet', 'Chimera', 'Sea Serpent', 'Thunderbird', 'Sandworm', 'Lamia', 'Medusa'],
    "71-80" => ['Dreadknight', 'Kraken', 'Phoenix', 'Leviathan', 'Cerberus', 'Gorgon', 'Sphinx', 'Bone Dragon', 'Nightmare', 'Hellion', 'Frost Wyrm', 'Storm Drake'],
    "81-90" => ['Archdemon', 'Behemoth', 'Colossus', 'Nightmare', 'Seraph', 'Balor', 'Pit Fiend', 'Death Knight', 'Voidspawn', 'Celestial Lion', 'Astral Wraith', 'Shadow Titan'],
    "91-100" => ['Dragon', 'Elder Wyrm', 'World Eater', 'Void Horror', 'Celestial', 'Star Serpent', 'Oblivion Beast', 'Solaris', 'Lunar Drake', 'Abyssal Leviathan', 'Eclipse Demon', 'Sun Wyrm'],
    "101-110" => ['Titan', 'Astral Dragon', 'Void Hydra', 'Eclipse Serpent', 'Stellar Golem', 'Cosmic Horror', 'Galewing', 'Nova Phoenix', 'Spectral Colossus', 'Shadow Leviathan'],
    "111-120" => ['Elder Titan', 'Void Phoenix', 'Celestial Hydra', 'Astral Behemoth', 'Solar Colossus', 'Lunar Chimera', 'Galactic Drake', 'Nebula Serpent', 'Starlight Wyrm', 'Comet Golem'],
    "121-130" => ['Aether Drake', 'Quantum Hydra', 'Singularity Wyrm', 'Eventide Colossus', 'Radiant Phoenix', 'Void Reaver', 'Stellar Leviathan', 'Eclipse Titan', 'Nebula Chimera', 'Cosmic Djinn'],
    "131-140" => ['Chrono Dragon', 'Infinity Serpent', 'Eclipse Behemoth', 'Solar Leviathan', 'Lunar Titan', 'Astral Sphinx', 'Galactic Hydra', 'Celestial Djinn', 'Starlord', 'Void Monarch'],
    "141-150" => ['Time Warden', 'Eternity Dragon', 'Abyssal Titan', 'Stellar Phoenix', 'Quantum Colossus', 'Nebula Leviathan', 'Radiant Djinn', 'Celestial Hydra', 'Void Sphinx', 'Cosmic Warden'],
    "151-160" => ['Infinity Wyrm', 'Chrono Behemoth', 'Eventide Hydra', 'Starlight Phoenix', 'Galactic Titan', 'Solar Monarch', 'Lunar Warden', 'Aether Colossus', 'Nebula Djinn', 'Quantum Sphinx'],
    "161-170" => ['Eclipse Monarch', 'Stellar Warden', 'Void Colossus', 'Celestial Phoenix', 'Astral Hydra', 'Galactic Wyrm', 'Solar Djinn', 'Lunar Behemoth', 'Aether Sphinx', 'Comet Titan'],
    "171-180" => ['Infinity Monarch', 'Chrono Hydra', 'Eventide Phoenix', 'Starlight Titan', 'Galactic Colossus', 'Solar Wyrm', 'Lunar Djinn', 'Aether Behemoth', 'Nebula Sphinx', 'Quantum Warden'],
    "181-190" => ['Eclipse Hydra', 'Stellar Monarch', 'Void Phoenix', 'Celestial Colossus', 'Astral Titan', 'Galactic Behemoth', 'Solar Sphinx', 'Lunar Wyrm', 'Aether Warden', 'Comet Behemoth'],
    "191-200" => ['Infinity Hydra', 'Chrono Monarch', 'Eventide Wyrm', 'Starlight Colossus', 'Galactic Phoenix', 'Solar Behemoth', 'Lunar Titan', 'Aether Monarch', 'Nebula Wyrm', 'Quantum Colossus']
];

// 2. Boss suffixes
$bossSuffixes = [
    'the Destroyer', 'the Ancient', 'the Eternal', 'the Unchained', 'the Devourer',
    'the Cursed', 'the Fallen', 'the Supreme', 'the Infinite', 'the Ender'
];

function generateMonsterName($baseName, $prefixesByCategory, $suffixes, $category) {
    $prefixList = $prefixesByCategory[$category];
    $prefix = $prefixList[array_rand($prefixList)];
    $suffix = $suffixes[array_rand($suffixes)];
    return trim("$prefix $baseName $suffix");
}

function generateStats($level, $category, $multipliers) {
  $m = $multipliers[$category];

  // Random variation: ±15%
  $variation = function($base) {
      $percent = rand(-15, 15) / 100;
      return round($base * (1 + $percent));
  };

  // Rare elite boost chance (5%)
  $isElite = rand(1, 100) <= 5;
  $eliteBonus = $isElite ? 1.1 : 1;

  // Special aggressive trait (15% chance)
  $isAggressive = rand(1, 100) <= 15;
  $attackBoostFactor = $isAggressive ? rand(20, 40) / 100 : 0; // 20–40% boost
  $attackMultiplier = $m['attack'] * (1 + $attackBoostFactor); // scaling up attack

  $hp = $variation($level * $m['hp']) * $eliteBonus;
  $attack = $variation($level * $attackMultiplier) * $eliteBonus;
  $defence = $variation($level * $m['defence']) * $eliteBonus;
  $speed = round($variation($level * $m['speed']) * $eliteBonus);
  $exp = round(($level * 10 * $m['exp']) * (rand(80, 120) / 100));
  $goldMin = round($level * 2 * $m['gold']);
  $goldMax = round($level * 6 * $m['gold']);
  $gold = rand($goldMin, $goldMax) * $eliteBonus;

  return [
      'hp' => round($hp),
      'attack' => round($attack),
      'defence' => round($defence),
      'speed' => $speed,
      'exp' => $exp,
      'gold' => round($gold)
  ];
}
?>

<div class="main-content">
    <h1>Monster Generator</h1>
    <form method="POST">
        <button type="submit" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 mb-4">Generate SQL Monsters</button>
    </form>
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <form method="POST">
        <input type="hidden" name="do_execute_sql" value="1">
        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 mb-4"
            onclick="return confirm('Are you sure you want to execute all generated SQL statements? This will truncate and overwrite all monster/item/drop data!');">
            Execute All SQL Statements
        </button>
    </form>
    <h2>Generated SQL:</h2>
    <pre>
<?php
$insertSQL = "INSERT INTO `monsters` (`name`, `level`, `hp`, `attack`, `speed`, `on_death_exp`, `on_death_gold`, `defence`) VALUES\n";
$values = [];
$monsterList = []; // Store monsters for table display

function generateMonsterNameV2($baseName, $prefixesByCategory, $category, $isBoss, $bossSuffixes) {
    $prefixList = $prefixesByCategory[$category];
    $prefix = $prefixList[array_rand($prefixList)];
    $suffix = $isBoss ? ' ' . $bossSuffixes[array_rand($bossSuffixes)] : '';
    return trim("$prefix $baseName$suffix");
}

function getRandomLevelInRange($min, $max) {
    return rand($min, $max);
}

$monstersByRange = []; // For distributing items per range

foreach ($levelRanges as $rangeKey => [$minLevel, $maxLevel]) {
    $baseNames = $monsterBaseNames[$rangeKey];
    $shuffledNames = $baseNames;
    shuffle($shuffledNames);

    $rangeMonsters = [];

    // 5 weak
    for ($i = 0; $i < 5; $i++) {
        $baseName = $shuffledNames[array_rand($shuffledNames)];
        $level = getRandomLevelInRange($minLevel, $minLevel + max(0, floor(($maxLevel - $minLevel) / 3)));
        $stats = generateStats($level, 'weak', $categoryMultipliers);
        $name = addslashes(generateMonsterNameV2($baseName, $prefixes, 'weak', false, $bossSuffixes));
        $values[] = "('$name', $level, {$stats['hp']}, {$stats['attack']}, {$stats['speed']}, {$stats['exp']}, {$stats['gold']}, {$stats['defence']})";
        $monsterList[] = [
            'name' => stripslashes($name),
            'level' => $level,
            'hp' => $stats['hp'],
            'attack' => $stats['attack'],
            'defence' => $stats['defence'],
            'speed' => $stats['speed'],
            'exp' => $stats['exp'],
            'gold' => $stats['gold'],
            'rangeKey' => $rangeKey
        ];
        $rangeMonsters[] = count($monsterList) - 1;
    }
    // 5 mid
    for ($i = 0; $i < 5; $i++) {
        $baseName = $shuffledNames[array_rand($shuffledNames)];
        $low = $minLevel + max(1, floor(($maxLevel - $minLevel) / 3));
        $high = $minLevel + max(1, floor(2 * ($maxLevel - $minLevel) / 3));
        $level = getRandomLevelInRange($low, $high);
        $stats = generateStats($level, 'mid', $categoryMultipliers);
        $name = addslashes(generateMonsterNameV2($baseName, $prefixes, 'mid', false, $bossSuffixes));
        $values[] = "('$name', $level, {$stats['hp']}, {$stats['attack']}, {$stats['speed']}, {$stats['exp']}, {$stats['gold']}, {$stats['defence']})";
        $monsterList[] = [
            'name' => stripslashes($name),
            'level' => $level,
            'hp' => $stats['hp'],
            'attack' => $stats['attack'],
            'defence' => $stats['defence'],
            'speed' => $stats['speed'],
            'exp' => $stats['exp'],
            'gold' => $stats['gold'],
            'rangeKey' => $rangeKey
        ];
        $rangeMonsters[] = count($monsterList) - 1;
    }
    // 3 strong
    for ($i = 0; $i < 3; $i++) {
        $baseName = $shuffledNames[array_rand($shuffledNames)];
        $low = $minLevel + max(1, floor(2 * ($maxLevel - $minLevel) / 3));
        $level = getRandomLevelInRange($low, $maxLevel - 1);
        $stats = generateStats($level, 'strong', $categoryMultipliers);
        $name = addslashes(generateMonsterNameV2($baseName, $prefixes, 'strong', false, $bossSuffixes));
        $values[] = "('$name', $level, {$stats['hp']}, {$stats['attack']}, {$stats['speed']}, {$stats['exp']}, {$stats['gold']}, {$stats['defence']})";
        $monsterList[] = [
            'name' => stripslashes($name),
            'level' => $level,
            'hp' => $stats['hp'],
            'attack' => $stats['attack'],
            'defence' => $stats['defence'],
            'speed' => $stats['speed'],
            'exp' => $stats['exp'],
            'gold' => $stats['gold'],
            'rangeKey' => $rangeKey
        ];
        $rangeMonsters[] = count($monsterList) - 1;
    }
    // 1 boss
    $baseName = $shuffledNames[array_rand($shuffledNames)];
    $level = $maxLevel;
    $stats = generateStats($level, 'boss', $categoryMultipliers);
    $name = addslashes(generateMonsterNameV2($baseName, $prefixes, 'boss', true, $bossSuffixes));
    $values[] = "('$name', $level, {$stats['hp']}, {$stats['attack']}, {$stats['speed']}, {$stats['exp']}, {$stats['gold']}, {$stats['defence']})";
    $monsterList[] = [
        'name' => stripslashes($name),
        'level' => $level,
        'hp' => $stats['hp'],
        'attack' => $stats['attack'],
        'defence' => $stats['defence'],
        'speed' => $stats['speed'],
        'exp' => $stats['exp'],
        'gold' => $stats['gold'],
        'rangeKey' => $rangeKey
    ];
    $rangeMonsters[] = count($monsterList) - 1;

    $monstersByRange[$rangeKey] = $rangeMonsters;
}

// Build items per range, and assign to monsters
$monsterDrops = []; // monster index => array of items

foreach ($levelRanges as $rangeKey => [$minLevel, $maxLevel]) {
    $itemsInRange = [];
    foreach ($prefixConfigs as $prefixConfig) {
        // Only prefixes that match this range
        if ($prefixConfig['level_range'][0] != $minLevel || $prefixConfig['level_range'][1] != $maxLevel) continue;
        foreach ($itemTypes as $type) {
            if ($type === 'Weapon') {
                foreach ($itemNames['Weapon'] as $subtype) {
                    $item = generateItem($type, $itemNames, $baseStats, $prefixConfig, $subtype);
                    $itemsInRange[] = $item;
                }
            } else {
                $item = generateItem($type, $itemNames, $baseStats, $prefixConfig);
                $itemsInRange[] = $item;
            }
        }
    }
    // Distribute items among monsters in this range
    $monsterIndexes = $monstersByRange[$rangeKey];
    $numMonsters = count($monsterIndexes);
    $numItems = count($itemsInRange);

    // Assign each item to at least one monster, and higher-level monsters get more items
    // Sort monsters by their level ascending
    usort($monsterIndexes, function($a, $b) use ($monsterList) {
        return $monsterList[$a]['level'] <=> $monsterList[$b]['level'];
    });

    // Assign each item to a monster, then assign remaining items to higher-level monsters
    $itemAssignments = array_fill(0, $numMonsters, []);
    for ($i = 0; $i < min($numItems, $numMonsters); $i++) {
        $itemAssignments[$i][] = $itemsInRange[$i];
    }
    $remaining = array_slice($itemsInRange, $numMonsters);
    $hi = $numMonsters - 1;
    foreach ($remaining as $item) {
        $itemAssignments[$hi][] = $item;
        if ($hi > 0) $hi--;
    }

    // --- Assign drop chances ---
    // Lower levels: higher drop chance, higher levels: lower drop chance
    // No more than 10% total drop chance per monster (sum of all its items)
    // We'll use a linear scale: minDrop = 1.5%, maxDrop = 10%, scale by level range
    $minDrop = 1.5; // percent
    $maxDrop = 10.0; // percent
    $levelSpan = 200 - 1;
    $rangeAvgLevel = ($minLevel + $maxLevel) / 2;
    // Drop chance for this range (lower levels get higher chance)
    $rangeDropChance = $maxDrop - (($rangeAvgLevel - 1) / $levelSpan) * ($maxDrop - $minDrop);
    // Distribute drop chance among all items for this monster (per monster, sum <= $rangeDropChance)
    foreach ($monsterIndexes as $idx => $monsterIdx) {
        if (!isset($monsterDrops[$monsterIdx])) $monsterDrops[$monsterIdx] = [];
        $items = $itemAssignments[$idx];
        $num = count($items);
        if ($num == 0) continue;
        // Distribute drop chance, but never exceed $rangeDropChance per monster
        $perItemChance = $rangeDropChance / $num;
        // Cap per item at 5% (if only 1 item, still not more than 10%)
        $perItemChance = min($perItemChance, 5.0);
        foreach ($items as $item) {
            $item['drop_chance'] = round($perItemChance, 2);
            $monsterDrops[$monsterIdx][] = $item;
        }
    }
}

// --- MONSTER INSERTS ---
echo "-- MONSTER TABLE (truncate, reset PK, insert)\n";
echo "-- " . count($monsterList) . " records\n";
echo "TRUNCATE TABLE monsters;\n";
echo "ALTER TABLE monsters AUTO_INCREMENT = 1;\n";
echo "INSERT INTO `monsters` (`name`, `level`, `hp`, `attack`, `speed`, `on_death_exp`, `on_death_gold`, `defence`) VALUES\n";
echo implode(",\n", $values) . ";\n";

// --- ITEM INSERTS ---
// Build $itemInsertRows before using it
$itemInsertRows = [];
foreach ($prefixConfigs as $prefixConfig) {
    foreach ($itemTypes as $type) {
        if ($type === 'Weapon') {
            foreach ($itemNames['Weapon'] as $subtype) {
                $item = generateItem($type, $itemNames, $baseStats, $prefixConfig, $subtype);
                $name = addslashes($item['name']);
                $itemInsertRows[] = "('$name', '{$item['type']}', {$item['attack']}, {$item['defence']}, {$item['crit_chance']}, {$item['crit_multi']}, {$item['life_steal']}, {$item['armor']}, {$item['speed']}, {$item['health']}, {$item['stamina']}, {$item['gold']})";
            }
        } else {
            $item = generateItem($type, $itemNames, $baseStats, $prefixConfig);
            $name = addslashes($item['name']);
            $itemInsertRows[] = "('$name', '{$item['type']}', {$item['attack']}, {$item['defence']}, {$item['crit_chance']}, {$item['crit_multi']}, {$item['life_steal']}, {$item['armor']}, {$item['speed']}, {$item['health']}, {$item['stamina']}, {$item['gold']})";
        }
    }
}

echo "\n-- ITEM TABLE (truncate, reset PK, insert)\n";
echo "-- " . count($itemInsertRows) . " records\n";
echo "TRUNCATE TABLE items;\n";
echo "ALTER TABLE items AUTO_INCREMENT = 1;\n";
echo "INSERT INTO `items` (`name`, `type`, `attack`, `defence`, `crit_chance`, `crit_multi`, `life_steal`, `armor`, `speed`, `health`, `stamina`, `gold`) VALUES\n";
echo implode(",\n", $itemInsertRows) . ";\n";

// --- MONSTER_ITEM_DROPS INSERTS ---
// Build $dropRows before using it
$dropRows = [];
foreach ($monsterDrops as $monsterIdx => $items) {
    // Monster PK is $monsterIdx+1 (since we reset auto_increment)
    $monsterId = $monsterIdx + 1;
    foreach ($items as $item) {
        // Find item PK (row) by name and type (since we just generated them in order)
        $itemId = null;
        foreach ($itemInsertRows as $i => $row) {
            // $row is like ('name', 'type', ...)
            if (strpos($row, "('" . addslashes($item['name']) . "', '{$item['type']}'") === 0) {
                $itemId = $i + 1;
                break;
            }
        }
        if ($itemId !== null) {
            $dropRows[] = "($monsterId, $itemId, {$item['drop_chance']})";
        }
    }
}

echo "\n-- MONSTER_ITEM_DROPS TABLE (truncate, reset PK, insert)\n";
echo "-- " . count($dropRows) . " records\n";
echo "TRUNCATE TABLE monster_item_drops;\n";
echo "ALTER TABLE monster_item_drops AUTO_INCREMENT = 1;\n";
echo "INSERT INTO `monster_item_drops` (`monster_id`, `item_id`, `drop_chance`) VALUES\n";
echo implode(",\n", $dropRows) . ";\n";
?>
</pre>
<?php
// --- EXECUTE SQL IF REQUESTED ---
if (isset($_POST['do_execute_sql']) && $_POST['do_execute_sql'] == '1') {
    $mysqli = new mysqli("localhost", "root", "", "battlewarz");
    if ($mysqli->connect_errno) {
        echo "<div style='color:red;'>DB Connection failed: " . $mysqli->connect_error . "</div>";
    } else {
        $errors = [];
        // Monster table
        $sqls = [
            "TRUNCATE TABLE monsters",
            "ALTER TABLE monsters AUTO_INCREMENT = 1",
            "INSERT INTO `monsters` (`name`, `level`, `hp`, `attack`, `speed`, `on_death_exp`, `on_death_gold`, `defence`) VALUES " . implode(",\n", $values)
        ];
        // Items table
        $sqls[] = "TRUNCATE TABLE items";
        $sqls[] = "ALTER TABLE items AUTO_INCREMENT = 1";
        if (count($itemInsertRows)) {
            $sqls[] = "INSERT INTO `items` (`name`, `type`, `attack`, `defence`, `crit_chance`, `crit_multi`, `life_steal`, `armor`, `speed`, `health`, `stamina`, `gold`) VALUES " . implode(",\n", $itemInsertRows);
        }
        // Monster item drops
        $sqls[] = "TRUNCATE TABLE monster_item_drops";
        $sqls[] = "ALTER TABLE monster_item_drops AUTO_INCREMENT = 1";
        if (count($dropRows)) {
            $sqls[] = "INSERT INTO `monster_item_drops` (`monster_id`, `item_id`, `drop_chance`) VALUES " . implode(",\n", $dropRows);
        }
        foreach ($sqls as $sql) {
            // Split multi-line INSERTs into single queries if needed
            if (preg_match('/^INSERT INTO/i', $sql) && strpos($sql, "),") !== false) {
                // MySQLi may not allow multi-row INSERTs with multi_query, so split
                $matches = [];
                if (preg_match('/^(INSERT INTO [^(]+ \([^)]+\) VALUES )(.+);?$/is', $sql, $matches)) {
                    $prefix = $matches[1];
                    $rows = explode("),", $matches[2]);
                    foreach ($rows as $row) {
                        $row = trim($row);
                        if ($row === "") continue;
                        if (substr($row, -1) !== ")") $row .= ")";
                        $single = $prefix . $row;
                        if (!$mysqli->query($single)) {
                            $errors[] = $mysqli->error;
                        }
                    }
                    continue;
                }
            }
            if (!$mysqli->query($sql)) {
                $errors[] = $mysqli->error;
            }
        }
        if (empty($errors)) {
            echo "<div style='color:green;font-weight:bold;'>All SQL statements executed successfully.</div>";
        } else {
            echo "<div style='color:red;'><b>SQL Errors:</b><br>" . implode("<br>", $errors) . "</div>";
        }
        $mysqli->close();
    }
}
?>
    <?php endif; ?>
<h1 class="text-x2 py-1 mb-1">
  <span class="text-muted-foreground font-light">Battlewarz /</span>
  <span class="font-bold"> Generate Monsters</span>
</h1>
<div class="w-full overflow-x-auto rpg-panel space-y-4">
  <table class="min-w-full divide-y divide-gray-200 border border-gray-300 rounded-lg shadow-sm bg-white">
        <thead>
        <tr style="background:#f0f0f0;">
            <th>#</th>
            <th>Name</th>
            <th>Level</th>
            <th>HP</th>
            <th>Attack</th>
            <th>Defence</th>
            <th>Speed</th>
            <th>EXP</th>
            <th>Gold</th>
            <th>Possible Drops</th>
        </tr>
        </thead>
        <tbody>
<?php
$idx = 1;
foreach ($monsterList as $mIdx => $monster) {
    echo "<tr>";
    echo "<td>" . $idx . "</td>";
    echo "<td>" . htmlspecialchars($monster['name']) . "</td>";
    echo "<td>" . $monster['level'] . "</td>";
    echo "<td>" . $monster['hp'] . "</td>";
    echo "<td>" . $monster['attack'] . "</td>";
    echo "<td>" . $monster['defence'] . "</td>";
    echo "<td>" . $monster['speed'] . "</td>";
    echo "<td>" . $monster['exp'] . "</td>";
    echo "<td>" . $monster['gold'] . "</td>";
    echo "<td></td>";
    echo "</tr>";

    // Subrows: show only items assigned to this monster (no rarity)
    if (!empty($monsterDrops[$mIdx])) {
        foreach ($monsterDrops[$mIdx] as $item) {
            echo '<tr class="monster-item-subrow" style="font-size:11px;background:#f9f9ff;">';
            echo '<td colspan="2"></td>';
            echo '<td colspan="2"><b>' . htmlspecialchars($item['name']) . '</b> (' . htmlspecialchars($item['type']) . ')</td>';
            echo '<td>' . $item['attack'] . '</td>';
            echo '<td>' . $item['defence'] . '</td>';
            echo '<td>' . $item['speed'] . '</td>';
            echo '<td>' . $item['crit_chance'] . '</td>';
            echo '<td>' . $item['gold'] . '</td>';
            echo '<td><span style="color:#007700;">Drop: ' . $item['drop_chance'] . '%</span></td>';
            echo '</tr>';
        }
    }
    $idx++;
}
?>
        </tbody>
    </table>
    </div>
</div>
<?php
// ...no extra endif here...
?>
