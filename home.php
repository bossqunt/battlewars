<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'includes/sidebar.php';
include_once 'controller/Database.php';
include_once 'controller/authCheck.php';
include './controller/Player.php';
$db = new Database();
$conn = $db->getConnection();
$player = new Player($conn,$playerId);
$name = $player->getName();
?>

<!-- Include custom CSS for playerStats and icons if needed -->
<link rel="stylesheet" href="assets/css/custom.css">
<style>
  body, html {
    font-family: var(--default-font-family, ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji") !importantO;
  }
</style>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 pb-4">
  <div class="bg-white border border-slate-200 rounded-lg w-full mb-6">
    <div class="p-6">
      <h2 class="text-2xl font-bold text-slate-800 mb-2">Welcome, <?php echo $name; ?>!</h2>
      <p class="text-slate-600 leading-normal font-light mb-2">
        <strong>Battlewarz</strong> is a persistent online strategy RPG where you progress, loot, and fight other players for control of the world map. This game was heavily inspired by the text-based rpg ultimate dominion (u-dom) of the early 2000's.
        
        <br>
        <span class="block mt-2">
          <strong>What can you do?</strong>
          <ul class="list-disc ml-6 mt-1 text-slate-600">
            <li>Progress your character and collect powerful loot</li>
            <li>Fight players for world territory and resources</li>
            <li>Own grid tiles for passive benefits and income</li>
            <li>Join or create a guild to dominate together</li>
            <li>Trade, complete tasks, and climb the leaderboards</li>
          </ul>
        </span>
      </p>
      <!-- Steps cards -->
      <div class="flex flex-col sm:flex-row gap-3 mt-6">
        <!-- Step 1 -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex-1 flex flex-col items-center text-center">
          <div class="w-16 h-16 bg-gray-200 rounded mb-2 flex items-center justify-center">
            <!-- Placeholder for image/gif -->
            <span class="text-gray-400 text-2xl">🗺️</span>
          </div>
          <div class="font-bold text-sm text-gray-700 mb-1">Step 1</div>
          <div class="text-xs text-gray-600">Click the Overworld</div>
        </div>
        <!-- Step 2 -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex-1 flex flex-col items-center text-center">
          <div class="w-16 h-16 bg-gray-200 rounded mb-2 flex items-center justify-center">
            <!-- Placeholder for image/gif -->
            <span class="text-gray-400 text-2xl">🚶</span>
          </div>
          <div class="font-bold text-sm text-gray-700 mb-1">Step 2</div>
          <div class="text-xs text-gray-600">Travel</div>
        </div>
        <!-- Step 3 -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex-1 flex flex-col items-center text-center">
          <div class="w-16 h-16 bg-gray-200 rounded mb-2 flex items-center justify-center">
            <!-- Placeholder for image/gif -->
            <span class="text-gray-400 text-2xl">⚔️</span>
          </div>
          <div class="font-bold text-sm text-gray-700 mb-1">Step 3</div>
          <div class="text-xs text-gray-600">Battle monsters for loot and items</div>
        </div>
        
      </div>
      <p class="text-slate-500 text-sm mt-2">
        <em>Tip: Use the shortcuts below to quickly access important areas of the game.</em>
      </p>
    </div>
 
  </div>
 
  <!-- Smaller, side-by-side cards -->
  <div class="mt-1 grid grid-cols-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
    <!-- Inventory -->
    <a href="/inventory.php" class="block bg-white border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition group text-center">
      <div class="flex flex-col items-center">
        <svg class="h-6 w-6 text-indigo-500 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <rect x="3" y="3" width="18" height="18" rx="2" />
          <path d="M3 9h18M9 21V9" />
        </svg>
        <div class="font-semibold text-gray-800 text-xs">Inventory</div>
        <div class="text-[10px] text-gray-500 mt-0.5">Manage items</div>
      </div>
    </a>
    <!-- Market -->
    <a href="/market.php" class="block bg-white border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition group text-center">
      <div class="flex flex-col items-center">
        <svg class="h-6 w-6 text-amber-500 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M3 9l9-6 9 6v6a9 9 0 01-18 0z" />
          <path d="M9 22V12h6v10" />
        </svg>
        <div class="font-semibold text-gray-800 text-xs">Market</div>
        <div class="text-[10px] text-gray-500 mt-0.5">Trade items</div>
      </div>
    </a>
    <!-- Arena -->
    <a href="/arena.php" class="block bg-white border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition group text-center">
      <div class="flex flex-col items-center">
        <svg class="h-6 w-6 text-red-500 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10" />
          <path d="M8 12h8M12 8v8" />
        </svg>
        <div class="font-semibold text-gray-800 text-xs">Arena</div>
        <div class="text-[10px] text-gray-500 mt-0.5">Fight players</div>
      </div>
    </a>
    <!-- World Map -->
    <a href="/worldmap.php" class="block bg-white border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition group text-center">
      <div class="flex flex-col items-center">
        <svg class="h-6 w-6 text-green-500 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M2 12l10-8 10 8-10 8-10-8z" />
          <circle cx="12" cy="12" r="3" />
        </svg>
        <div class="font-semibold text-gray-800 text-xs">World Map</div>
        <div class="text-[10px] text-gray-500 mt-0.5">Conquer tiles</div>
      </div>
    </a>
    <!-- Guilds -->
    <a href="/guilds.php" class="block bg-white border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition group text-center">
      <div class="flex flex-col items-center">
        <svg class="h-6 w-6 text-purple-500 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <circle cx="12" cy="7" r="4" />
          <path d="M5.5 21a8.38 8.38 0 0113 0" />
        </svg>
        <div class="font-semibold text-gray-800 text-xs">Guilds</div>
        <div class="text-[10px] text-gray-500 mt-0.5">Join a guild</div>
      </div>
    </a>
    <!-- Library -->
    <a href="/library.php" class="block bg-white border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition group text-center">
      <div class="flex flex-col items-center">
        <svg class="h-6 w-6 text-blue-400 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <rect x="4" y="4" width="16" height="16" rx="2" />
          <path d="M8 4v16M16 4v16" />
        </svg>
        <div class="font-semibold text-gray-800 text-xs">Library</div>
        <div class="text-[10px] text-gray-500 mt-0.5">Game guides</div>
      </div>
    </a>
    <!-- Change Password -->
    <a href="/changepassword.php" class="block bg-white border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition group text-center">
      <div class="flex flex-col items-center">
        <svg class="h-6 w-6 text-gray-500 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <rect x="3" y="11" width="18" height="10" rx="2" />
          <path d="M7 11V7a5 5 0 0110 0v4" />
        </svg>
        <div class="font-semibold text-gray-800 text-xs">Password</div>
        <div class="text-[10px] text-gray-500 mt-0.5">Change password</div>
      </div>
    </a>
    <!-- Online Players -->
    <a href="/userlist/all" class="block bg-white border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition group text-center">
      <div class="flex flex-col items-center">
        <svg class="h-6 w-6 text-teal-500 mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <circle cx="12" cy="7" r="4" />
          <path d="M5.5 21a8.38 8.38 0 0113 0" />
        </svg>
        <div class="font-semibold text-gray-800 text-xs">Players</div>
        <div class="text-[10px] text-gray-500 mt-0.5">Online now</div>
      </div>
    </a>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="module" src="assets/js/playerStats.js"></script>
<script type="module" src="assets/js/dashboard.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>