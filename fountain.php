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

<?php mainContainer('main-container', 'max-w-6xl mx-auto', '', true); ?>
        <!-- Breadcrumb -->
        <nav class="flex mb-4 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="dashboard.php" class="text-gray-500 hover:text-gray-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Home</span>
                    </a>
                </li>
                <li>
                    <span class="mx-2 text-gray-400 flex items-center" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"></path></svg>
                    </span>
                </li>
                <li class="inline-flex items-center">
                    <span class="text-gray-700 font-semibold">Fountain</span>
                </li>
            </ol>
        </nav>
        <!-- End Breadcrumb -->
        <div class="bg-white border border-slate-200 rounded-lg w-full mb-6">
      <div class="p-6">
        <div class="w-full overflow-x-hidden space-y-4">
        <p class="text-gray-700">
          Welcome to the fountain! Here you can heal yourself back to full health for a cost of gold. The cost increases
          as you level up.
        </p>
        <p class="text-sm text-gray-500 mt-2">
          Your current gold: <span
            class="font-semibold text-yellow-600"><?php echo htmlspecialchars($playerDetails['gold']); ?></span>
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