<!-- HTML Structure -->

<!-- Remove the inline toast container if your shared toast handles its own container -->
<!-- <div id="toast-container" class="fixed bottom-4 right-4 z-50"></div> -->

<?php
session_start();
//include('./includes/header.php');
require_once('controller/Player.php'); // Include the Player class
require_once('controller/Database.php'); // Include the Database class
include 'includes/sidebar.php';

// Initialize the Database connection
$db = new Database();
$conn = $db->getConnection();

// Initialize the Player object
$player = new Player($conn, $playerId);

// Get player details
$playerDetails = $player->getDetails();

// Default healing cost
$healing_cost = ($playerDetails['level'] + 10) * 5;

// Prepare toast message and type for JS
$toastMsg = null;
$toastType = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $healing_cost = ($playerDetails['level'] + 10) * 5; 

    if ($playerDetails['c_hp'] == $playerDetails['max_hp']) {
        $toastMsg = 'You are already at full health.';
        $toastType = 'error';
    } else if ($playerDetails['gold'] >= $healing_cost) {
        $playerDetails['gold'] -= $healing_cost;
        $playerDetails['c_hp'] = $playerDetails['max_hp'];

        $sql = "UPDATE players SET gold = ?, c_hp = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dii", $playerDetails['gold'], $playerDetails['c_hp'], $playerId);

        if ($stmt->execute()) {
            $toastMsg = "You have been healed back to full health for $healing_cost gold.";
            $toastType = 'success';
        } else {
            $toastMsg = 'Failed to update player data. Please try again later.';
            $toastType = 'error';
        }
    } else {
        $toastMsg = "You don't have enough gold to be healed.";
        $toastType = 'error';
    }
}
?>

<h1 class="text-x2 py-1 mb-1">
  <span class="text-muted-foreground font-light">Battlewarz /</span>
  <span class="font-bold"> Hospital</span>
</h1>
<div class="w-full overflow-x-hidden rpg-panel space-y-4">
  <p class="text-gray-700">
    Welcome to the fountain! Here you can heal yourself back to full health for a cost of gold. The cost increases as you level up.
  </p>
  <p class="text-sm text-gray-500 mt-2">
    Your current gold: <span class="font-semibold text-yellow-600"><?php echo htmlspecialchars($playerDetails['gold']); ?></span>
  </p>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="mt-4 space-y-4">
    <p>Do you want to heal back to full health for 
      <span class="font-semibold text-indigo-600"><?php echo htmlspecialchars($healing_cost); ?></span> gold?
    </p>
    <input type="hidden" name="healing_cost" value="<?php echo htmlspecialchars($healing_cost); ?>">
    <button class="rpg-button rpg-button-primary h-8" id="heal">
      <span class="text-sm">Heal</span>
    </button>
  </form>
</div>

<script type="module">
import { showToast } from './assets/js/ui.js';

// Show toast if set by PHP
<?php if ($toastMsg): ?>
  showToast(<?= json_encode($toastMsg) ?>, <?= json_encode($toastType) ?>);
<?php endif; ?>
</script>
<script type="module" src="assets/js/playerStats.js"></script>

