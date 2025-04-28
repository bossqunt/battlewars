<?php include 'includes/sidebar.php'; ?>


<?php mainContainer('main-container', 'max-w-6xl mx-auto', '', true); ?>
<!-- Breadcrumb -->
<nav class="flex mb-4 text-sm text-gray-500" aria-label="Breadcrumb">
  <ol class="inline-flex items-center space-x-1 md:space-x-2">
    <li class="inline-flex items-center">
      <a href="dashboard.php" class="text-gray-500 hover:text-gray-700 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd"
            d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z"
            clip-rule="evenodd"></path>
        </svg>
        <span class="sr-only">Home</span>
      </a>
    </li>
    <li>
      <span class="mx-2 text-gray-400 flex items-center" aria-hidden="true">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
          <path fill-rule="evenodd"
            d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
            clip-rule="evenodd"></path>
        </svg>
      </span>
    </li>
    <li class="inline-flex items-center">
      <span class="text-gray-500 hover:text-gray-700">Overworld</span>
    </li>
    <li>
      <span class="mx-2 text-gray-400 flex items-center" aria-hidden="true">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
          <path fill-rule="evenodd"
            d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
            clip-rule="evenodd"></path>
        </svg>
      </span>
    </li>
    <li class="inline-flex items-center">
      <span class="text-gray-700 font-semibold" id="current-location-breadcrumbs">
        <!-- Location will be set by dashboard-v2.js -->
      </span>
    </li>
  </ol>
</nav>
<!-- End Breadcrumb -->


<!-- TESTING -->
<div class="relative mx-auto w-full max-w-8xl mb-3 rounded-lg border-gray-200 border">

  <div class="flex flex-col bg-white rounded-md p-4">

    <!-- Top row: Location + Owner -->
    <div class="flex flex-col md:flex-row justify-between items-start mb-2 gap-4">
      
      <!-- Left: Player Location + Travel -->
      <div class="flex flex-col gap-2 text-black text-sm ">
        <div class="flex items-center gap-1">
          <span class="font-semibold text-gray-700">Current Location:</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
            <circle cx="12" cy="10" r="3"></circle>
          </svg>
          <span id="player-grid-location" class="text-gray-800">X: 6 Y: 2 Z: 2</span>
        </div>
        
        <!-- Travel To -->
        <div class="flex items-center gap-1" id="travel-container">
          <label for="area-select" class="font-semibold text-gray-700">Travel to:</label>
          <select id="area-select" class="border rounded px-2 py-1 text-xs">
            <!-- etc... -->
          </select>
        </div>
      </div>

      <!-- Right: Owner Info -->
      <div class="flex items-center gap-2 text-sm">
        <img id="owner-image" class="w-8 h-8 rounded-full object-cover" alt="Owner" src="uploads/default_image.jpg">
        <div class="flex flex-col leading-tight">
          <span id="owner-text" class="text-gray-700 font-semibold text-xs">
            DoinLad <span class="text-gray-500 font-normal">(Level 28)</span>
          </span>
          <span id="owner-guild-text" class="text-gray-500 text-xs"></span>
        </div>
      </div>

    </div>

    <!-- Divider -->
    <div class="border-t border-gray-200 my-2"></div>

    <!-- Actions Row -->
    <div class="flex justify-end gap-2 mt-2">
    
      <button id="take-ownership-button" class="rpg-button border border-yellow-400 bg-white-200 text-black-800 h-7 px-3 text-xs">
        Take Ownership
      </button>
      <button id="travel-button" class="rpg-button rpg-button-primary h-7 px-3 text-xs">
        Travel
      </button>
    </div>

  </div>
</div>




<!-- Main Game Content -->
<div class="flex-grow flex overflow-hidden ">
  <!-- Game Main Area -->
  <div class="game-container flex-grow p-0 bg-white border border-gray-200 rounded-lg p-4 flex-1 flex flex-col">

    <!-- Grid Container with auto-sizing tiles -->
    <div class="grid-container" id="grid-container">
      <!-- Grid will be dynamically populated here -->
      <!-- Make sure each cell has class="grid-cell" -->



    </div>
  </div>

  <!-- Right Sidebar (Fixed Width) -->
  <aside class="xl:w-80 mt-0 flex-shrink-0 flex flex-col overflow-hidden pl-5">
    <!-- Nearby Monsters Card -->
    <div class="bg-white rounded-lg mb-3 flex flex-col border border-gray-200">
      <h2 class="text-sm font-medium mb-2 p-4 !pb-0">Nearby Monsters</h2>
      <div class="space-y-1 p-4 !pt-0" id="monsters-nearby">
        <!-- Monsters will be dynamically populated here -->
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


            <script type="module" src="assets/js/playerStats.js"></script>
            <script type="module" src="assets/js/dashboard-v2.js"></script>
            <script src="assets/js/main.js"></script>