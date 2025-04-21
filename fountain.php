<!-- HTML Structure -->

<!-- Add this toast container at the bottom -->
<div id="toast-container" class="fixed bottom-4 right-4 z-50">
  
</div>
<script>
  function showToast(message, type) {
  const toastContainer = document.getElementById('toast-container');

  const toast = document.createElement('div');
  toast.classList.add(
    'p-4', 'pl-5', 'rounded-md', 'shadow-md', 'bg-white', 'text-sm',
    'w-72', 'max-w-full', 'relative', 'border-1-4', 'flex', 'items-start', 'gap-2'
  );

  // Color based on type
  let borderColor = 'border-blue-500';
  let iconColor = 'text-blue-500';

  if (type === 'success') {
    borderColor = 'border-green-500';
    iconColor = 'text-green-500';
  } else if (type === 'error') {
    borderColor = 'border-red-500';
    iconColor = 'text-red-500';
  }

  toast.classList.add(borderColor);

  toast.innerHTML = `
    <div class="${iconColor} pt-0.5">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
      </svg>
    </div>
    <div class="flex-1 text-gray-800">${message}</div>
    <button onclick="this.closest('div').remove();" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  `;

  toastContainer.appendChild(toast);

  // Auto-remove after 4 seconds
  setTimeout(() => {
    toast.remove();
  }, 4000);
}



</script>

<!-- Your existing PHP code that sends toasts -->
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
$healing_cost = 10 * 5;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $healing_cost = 10 + $playerDetails['level'] * 5; // Calculate the cost based on the player's level
    
    if ($playerDetails['c_hp'] == $playerDetails['max_hp']) {
        // If the player is already at full health, send a message and do nothing
        echo "<script>showToast('You are already at full health.', 'info');</script>";
    } else if ($playerDetails['gold'] >= $healing_cost) {
        // Deduct the gold and update the player's HP
        $playerDetails['gold'] -= $healing_cost;
        $playerDetails['c_hp'] = $playerDetails['max_hp'];

        // Prepare the SQL query to update the player's data
        $sql = "UPDATE players SET gold = ?, c_hp = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dii", $playerDetails['gold'], $playerDetails['c_hp'], $playerId);

        if ($stmt->execute()) {
            // Send a success toast
            echo "<script>showToast('You have been healed back to full health for $healing_cost gold.', 'success');</script>";
        } else {
            // Handle save failure
            echo "<script>showToast('Failed to update player data. Please try again later.', 'error');</script>";
        }
    } else {
        // Send a failure toast if the player doesn't have enough gold
        echo "<script>showToast('You don\'t have enough gold to be healed.', 'error');</script>";
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

<script type="module" src="assets/js/playerStats.js"></script>

