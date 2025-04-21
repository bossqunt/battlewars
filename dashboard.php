<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'includes/sidebar.php';
?>

<!-- Include custom CSS for playerStats -->
<link rel="stylesheet" href="assets/css/custom.css">

<h1 class="text-x2 py-1 mb-1">
  <span class="text-muted-foreground font-light">Battlewarz /</span>
  <span class="font-bold"> Overworld</span>
</h1>

<!-- FIX THE FUCKING COL-SPAN SO I HAVE MORE CONTROL OVER THE GRID+MONSTER CARD LAYOUT -->
<main class="flex-1 overflow-auto p-0">
  <div class="space-y-4 flex flex-col h-full">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 flex-grow">
      <div class="md:col-span-2">
        <div class="rpg-panel space-y-4">
          <div class="flex justify-between items-center">
            <h1 class="text-lg font-bold"></h1>


            <div class="flex space-x-2">
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
          <p class="text-sm text-muted-foreground" id="travel-details">Loading travel position details</p>
          <div class="w-full flex justify-center">
            <div id="grid-container" class="grid grid-cols-9 gap-1" style="width: max-content;">


              <!-- Grid will be dynamically populated here -->

            </div>
          </div>
          <!-- grid end -->
        </div>
      </div>
      <div class="md:col-span-1">
  <div class="border rounded-md p-2 bg-card/50">
    <h2 class="text-m font-medium mb-2">Monsters nearby</h2>
    <div class="space-y-1" id="monsters-nearby">
      <!-- Monsters will be dynamically populated here -->
       
    </div>
  </div>
</div></div>
    <div class="sticky bottom-0 pb-3 pt-1">
      <div class="border rounded-md p-3 bg-card/50">
        <h2 class="text-sm font-medium mb-2">World Events</h2>
          <div
            class="position: relative; --radix-scroll-area-corner-width: 0px; --radix-scroll-area-corner-height: 0px;">
            <div id="world-events" class="max-h-[40vh] overflow-y-auto overflow-x-hidden pr-2">
            <!-- world events should be populated here -->
              <p class="text-xs text-muted-foreground">fuckoff</p>
            </div>
          </div>
        </div>
      </div>
    </div>
</main>

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
  <div class="bg-white rounded-lg w-1/3 p-6 shadow-lg max-w-xl w-full">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold">Battle Result</h2>
      <button id="close-modal" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
    </div>

    <!-- Outcome -->
    <div id="battle-outcome" class="mb-4 text-center"></div>

    <!-- Level Up -->
    <div id="level-up" class="mb-4" style="display: none;">
      <p id="level-up-message" class="text-sm text-green-600 text-center"></p>
      <!-- Level message will be displayed here -->
    </div>
    <!-- Battle Log -->
    <div class="mb-4">
      <h3 class="text-lg font-semibold text-gray-800 mb-2">Battle Log</h3>
      <div id="battle-log-content"
        class="bg-gray-100 p-3 rounded-md max-h-40 overflow-y-auto space-y-1 text-sm text-gray-700">
        <!-- Dynamic log lines go here -->
      </div>
    </div>

    <!-- Rewards -->
    <div class="mb-4">
      <h3 class="text-lg font-semibold text-gray-800 mb-2">Rewards</h3>
      <ul class="space-y-1 text-sm">
        <p class="text-md text-purple-600" id="loot-message" class="flex items-center space-x-2"></>
        <li id="exp-reward" class="flex items-center space-x-2"></li>
        <li id="gold-reward" class="flex items-center space-x-2"></li>
      </ul>
    </div>
    <!-- Loot -->
    <div class="mb-4" id="loot-section" style="display: none;">
      <h3 class="text-lg font-semibold text-gray-800 mb-2">Loot</h3>
      <ul id="loot-list" class="space-y-1 text-sm text-gray-700">
        <!-- Loot items will be appended here -->
      </ul>
    </div>

    <!-- Close Button -->
    <button id="close-battle" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
      Close
    </button>
  </div>
</div>


<!-- <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div> -->

<!-- Place this tag in your head or just before your close body tag. -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="module" src="assets/js/playerStats.js"></script>
<script type="module" src="assets/js/dashboard.js"></script>
<script src="assets/js/main.js"></script>

</body>

</html>