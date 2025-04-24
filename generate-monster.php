<?php
ob_start();
require './includes/sidebar.php';
require_once './controller/Database.php';
require_once './controller/AuthCheck.php';

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
        <button type="submit" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 mb-4">Generate All Monsters</button>
    </form>
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <h2>Generated SQL:</h2>
    <pre>
<?php
$insertSQL = "INSERT INTO `monsters` (`name`, `level`, `hp`, `attack`, `speed`, `on_death_exp`, `on_death_gold`, `defence`) VALUES\n";
$values = [];

function generateMonsterNameV2($baseName, $prefixesByCategory, $category, $isBoss, $bossSuffixes) {
    $prefixList = $prefixesByCategory[$category];
    $prefix = $prefixList[array_rand($prefixList)];
    $suffix = $isBoss ? ' ' . $bossSuffixes[array_rand($bossSuffixes)] : '';
    return trim("$prefix $baseName$suffix");
}

function getRandomLevelInRange($min, $max) {
    return rand($min, $max);
}

foreach ($levelRanges as $rangeKey => [$minLevel, $maxLevel]) {
    $baseNames = $monsterBaseNames[$rangeKey];
    // Shuffle for random picks
    $shuffledNames = $baseNames;
    shuffle($shuffledNames);

    // 5 weak
    for ($i = 0; $i < 5; $i++) {
        $baseName = $shuffledNames[array_rand($shuffledNames)];
        $level = getRandomLevelInRange($minLevel, $minLevel + max(0, floor(($maxLevel - $minLevel) / 3)));
        $stats = generateStats($level, 'weak', $categoryMultipliers);
        $name = addslashes(generateMonsterNameV2($baseName, $prefixes, 'weak', false, $bossSuffixes));
        $values[] = "('$name', $level, {$stats['hp']}, {$stats['attack']}, {$stats['speed']}, {$stats['exp']}, {$stats['gold']}, {$stats['defence']})";
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
    }
    // 3 strong
    for ($i = 0; $i < 3; $i++) {
        $baseName = $shuffledNames[array_rand($shuffledNames)];
        $low = $minLevel + max(1, floor(2 * ($maxLevel - $minLevel) / 3));
        $level = getRandomLevelInRange($low, $maxLevel - 1);
        $stats = generateStats($level, 'strong', $categoryMultipliers);
        $name = addslashes(generateMonsterNameV2($baseName, $prefixes, 'strong', false, $bossSuffixes));
        $values[] = "('$name', $level, {$stats['hp']}, {$stats['attack']}, {$stats['speed']}, {$stats['exp']}, {$stats['gold']}, {$stats['defence']})";
    }
    // 1 boss
    $baseName = $shuffledNames[array_rand($shuffledNames)];
    $level = $maxLevel;
    $stats = generateStats($level, 'boss', $categoryMultipliers);
    $name = addslashes(generateMonsterNameV2($baseName, $prefixes, 'boss', true, $bossSuffixes));
    $values[] = "('$name', $level, {$stats['hp']}, {$stats['attack']}, {$stats['speed']}, {$stats['exp']}, {$stats['gold']}, {$stats['defence']})";
}

echo $insertSQL . implode(",\n", $values) . ";";
?>
    </pre>
    <h2>Monster Table</h2>
    <div style="overflow-x:auto;">
    <table border="1" cellpadding="4" cellspacing="0" style="min-width:900px;">
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
        </tr>
        </thead>
        <tbody>
<?php
$idx = 1;
foreach ($values as $row) {
    preg_match("/\('(.*?)',\s*(\d+),\s*(\d+),\s*(\d+),\s*(\d+),\s*(\d+),\s*(\d+),\s*(\d+)\)/", $row, $matches);
    if ($matches) {
        echo "<tr>";
        echo "<td>" . $idx++ . "</td>";
        echo "<td>" . htmlspecialchars(stripslashes($matches[1])) . "</td>";
        echo "<td>" . $matches[2] . "</td>";
        echo "<td>" . $matches[3] . "</td>";
        echo "<td>" . $matches[4] . "</td>";
        echo "<td>" . $matches[8] . "</td>"; // defence
        echo "<td>" . $matches[5] . "</td>";
        echo "<td>" . $matches[6] . "</td>";
        echo "<td>" . $matches[7] . "</td>";
        echo "</tr>";
    }
}
?>
        </tbody>
    </table>
    </div>
<?php endif; ?>
</div>
