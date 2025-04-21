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
    "91-100" => [91, 100]
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
        <label>Monster Name (base):</label><br>
        <input type="text" name="base_name" required><br><br>

        <label>Category:</label><br>
        <select name="category" required>
            <option value="weak">Weak</option>
            <option value="mid">Mid</option>
            <option value="strong">Strong</option>
            <option value="boss">Boss</option>
        </select><br><br>

        <label>Level Range:</label><br>
        <select name="level_range" required>
            <?php foreach ($levelRanges as $label => $range): ?>
                <option value="<?= $label ?>"><?= $label ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Number of Monsters to Generate:</label><br>
        <input type="number" name="count" value="1" min="1" max="100"><br><br>

        <button type="submit" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 mb-4">Generate SQL</button>
    </form>
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <h2>Generated SQL:</h2>
    <pre>
<?php
$baseName = $_POST['base_name'];
$category = $_POST['category'];
$rangeKey = $_POST['level_range'];
$count = intval($_POST['count']);

[$minLevel, $maxLevel] = $levelRanges[$rangeKey];
$insertSQL = "INSERT INTO `monsters` (`name`, `level`, `hp`, `attack`, `speed`, `on_death_exp`, `on_death_gold`, `defence`) VALUES\n";

$values = [];

function getBoundedLevel($min, $max, $segment, $category) {
    $range = $max - $min + 1;
    $third = max(1, floor($range / 3));

    switch ($category) {
        case 'weak':
            $low = $min;
            $high = $min + $third - 1;
            break;
        case 'mid':
            $low = $min + $third;
            $high = $min + 2 * $third - 1;
            break;
        case 'strong':
            $low = $min + 2 * $third;
            $high = $max;
            break;
        default:
            $low = $min;
            $high = $max;
    }

    return rand($low, $high);
}

if ($category === 'boss') {
    // Generate one boss only
    $level = rand($minLevel, $maxLevel);
    $stats = generateStats($level, 'boss', $categoryMultipliers);
    $name = addslashes(generateMonsterName($baseName, $prefixes, $suffixes, 'boss'));
    $values[] = "('$name', $level, {$stats['hp']}, {$stats['attack']}, {$stats['speed']}, {$stats['exp']}, {$stats['gold']}, {$stats['defence']})";
} else {
    $categories = ['weak', 'mid', 'strong'];
    for ($i = 0; $i < $count; $i++) {
        foreach ($categories as $cat) {
            $level = getBoundedLevel($minLevel, $maxLevel, 3, $cat);
            $stats = generateStats($level, $cat, $categoryMultipliers);
            $name = addslashes(generateMonsterName($baseName, $prefixes, $suffixes, $cat));
            $values[] = "('$name', $level, {$stats['hp']}, {$stats['attack']}, {$stats['speed']}, {$stats['exp']}, {$stats['gold']}, {$stats['defence']})";
        }
    }
}

echo $insertSQL . implode(",\n", $values) . ";";
?>
    </pre>
<?php endif; ?>
