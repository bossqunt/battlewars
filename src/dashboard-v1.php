<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'includes/sidebar.php';
?>

<!-- Include custom CSS for playerStats -->
<link rel="stylesheet" href="assets/css/custom.css">

<!-- Add this style block in the <head> or before </body> -->
<style>
  #grid-container {
    /* Default fallback */
    --grid-cell-size: 45px;
  }
  #grid-container .grid-cell {
    width: var(--grid-cell-size);
    height: var(--grid-cell-size);
    /* ...other cell styles... */
  }
</style>

<h1 class="text-x2 py-1 mb-1 flex items-center justify-between">
  <span>
    <span class="text-muted-foreground font-light">Battlewarz /</span>
    <span class="font-bold"> Overworld</span>
  </span>
  <!-- Online Players Button (inline, styled) -->
  <button id="online-players" class="h-7 px-2 flex items-center gap-1 bg-sidebar text-sidebar-foreground border-sidebar-border border hover:bg-accent hover:text-accent-foreground rounded-md justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0">
    <!-- Lucide Users Icon -->
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users h-3.5 w-3.5">
      <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
      <circle cx="9" cy="7" r="4"></circle>
      <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
      <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
    </svg>
   
    <span class="text-xs">Online (<span id="online-player-count">0</span>)</span>
  </button>
</h1>

<!-- Main Flex Container -->
<div id="main-container" class="flex flex-col h-[80vh] gap-4">
  <div class="flex flex-1 gap-4 min-h-0">
    <!-- Main Area (was col-span-9) -->
    <div id="grid-controller" class="flex-1 rpg-panel flex flex-col min-h-0 ">
      <main class="flex-1 overflow-auto p-0 flex flex-col">
        <div class="space-y-4 flex flex-col h-full">
          <div class="rpg-panel space-y-4 flex flex-col h-full h-[80vh]">
            <div class="flex justify-between items-center">
              <h1 class="text-lg font-bold"></h1>
              <!-- Buttons aligned right -->
              <div class="flex space-x-2 ml-auto">
                <div id="owner-text" class="flex items-center gap-1 text-sm text-black"></div>
                <div class="flex items-center gap-1 text-black bg-white border border-black px-3 py-1 rounded-md w-fit"
                  id="player-location-display">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-black" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path
                      d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0">
                    </path>
                    <circle cx="12" cy="10" r="3"></circle>
                  </svg>
                  <span id="location-text" class="text-sm">Loading...</span>
                </div>
                
                <button class="rpg-button flex items-center gap-1 h-8 border border-black" id="take-ownership-button">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-flag h-3.5 w-3.5">
                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                    <line x1="4" x2="4" y1="22" y2="15"></line>
                  </svg>
                  <span class="text-sm">Claim Territory</span>
                </button>
                <button class="rpg-button rpg-button-primary h-8" id="travel-button">
                  <span class="text-sm">Travel</span>
                </button>
              </div>
            </div>
            <div id="area-sidebar" class="w-full mb-2 mt-2"></div>
            <p class="text-sm text-muted-foreground mt-1" id="travel-details">Loading travel position details</p>
            <div class="w-full flex justify-center flex-1 items-center mt-0">
              <div id="grid-container" class="grid grid-cols-9 gap-2" style="width: max-content;">
                <!-- Grid will be dynamically populated here -->
                <!-- Make sure each cell has class="grid-cell" -->
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
    <!-- Monsters Nearby (was col-span-3) -->
    <div class="flex flex-col w-[320px] min-w-[240px] max-w-[400px] h-full">
      <div class="border rounded-md p-3 bg-card/50 flex-1 overflow-auto">
        <h2 class="text-sm font-medium mb-2">Nearby Monsters</h2>
        <div class="space-y-1" id="monsters-nearby">
          <!-- Monsters will be dynamically populated here -->
        </div>
      </div>
    </div>
    <!-- Online Players List Container (hidden by default, shown by JS) -->
    <div id="online-players-list" class="flex flex-col w-[200px] min-w-[160px] max-w-[240px] h-full" style="display:none;">
      <div class="border rounded-md p-3 bg-card/50 flex-1 overflow-auto">
        <h2 class="text-sm font-medium mb-2">Online Players</h2>
        <div class="space-y-1" id="online-players-cards">
          <!-- Player cards will be dynamically populated here -->
        </div>
      </div>
    </div>
  </div>
  <!-- World Events (was col-span-12) -->
  <div>
    <div class="border rounded-md p-3 bg-card/50 mt-2">
      <h2 class="text-sm font-medium mb-2">World Events</h2>
      <div>
        <div id="world-events" 
             class="overflow-y-auto overflow-x-hidden pr-2"
             style="height:10vh; max-height:20vh;">
          <!-- world events should be populated here -->
          <p class="text-xs text-muted-foreground">Loading...</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Player Battle Modal -->
<div id="player-battle-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
  <div class="bg-white rounded-lg shadow-lg w-96 p-4">
    <h2 class="text-lg font-bold mb-2 text-gray-800">Fight player for ownership?</h2>
    <p id="player-battle-text" class="text-gray-700 mb-4">
      Are you sure you want to battle this player for control of the tile?
    </p>
    <div class="flex justify-end gap-2">
      <button id="cancel-player-battle" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</button>
      <button id="confirm-player-battle" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Battle</button>
    </div>
  </div>
</div>

<!-- Modal Structure -->
<div id="battle-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
<div class="bg-white rounded-lg w-full max-w-3xl p-6 shadow-lg">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold">Battle Result</h2>
      <button id="close-modal" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
    </div>
    <!-- ...existing code for modal content... -->
    <div id="battle-outcome" class="mb-4 text-center"></div>
    <div id="level-up" class="mb-4" style="display: none;">
      <p id="level-up-message" class="text-sm text-green-600 text-center"></p>
    </div>
    <div class="mb-4">
      <h3 class="text-lg font-semibold text-gray-800 mb-2">Battle Log</h3>
      <div id="battle-log-content" class="bg-gray-100 p-3 rounded-md max-h-[300px] overflow-y-auto space-y-1 text-sm text-gray-700">

      </div>
    </div>
    <div class="mb-4">
      <h3 class="text-lg font-semibold text-gray-800 mb-2">Rewards</h3>
      <ul class="space-y-1 text-sm">
        <p class="text-md text-purple-600" id="loot-message" class="flex items-center space-x-2"></p>
        <li id="exp-reward" class="flex items-center space-x-2"></li>
        <li id="gold-reward" class="flex items-center space-x-2"></li>
      </ul>
    </div>
    <div class="mb-4" id="loot-section" style="display: none;">
      <h3 class="text-lg font-semibold text-gray-800 mb-2">Loot</h3>
      <ul id="loot-list" class="space-y-1 text-sm text-gray-700"></ul>
    </div>
    <button id="close-battle" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
      Close
    </button>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="module" src="assets/js/playerStats.js"></script>
<script type="module" src="assets/js/dashboard.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>