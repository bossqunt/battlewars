<?php include 'includes/sidebar.php'; ?>


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
                    <span class="text-gray-700 font-semibold">Overworld</span>
                </li>
                <li>
                    <span class="mx-2 text-gray-400 flex items-center" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"></path></svg>
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

      
      <!-- Main Game Content -->
      <div class="flex-grow flex overflow-hidden">
        <!-- Game Main Area -->
        <div class="game-container flex-grow p-3">
          <!-- Top Info Cards - Fixed Height -->
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-3">
            <!-- Location Card -->
            <div class="bg-white shadow rounded-lg p-3 flex items-center justify-between">
              <nav class="text-sm">
                <ol class="flex items-center">
                  <li><a href="#" class="text-blue-600 hover:underline">Battlewarz</a></li>
                  <li class="mx-2">/</li>
                  <li>Overworld</li>
                </ol>
              </nav>
              <div class="flex items-center text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Online (0)</span>
              </div>
            </div>
            
            <!-- Grid Owner Card -->
            <div class="bg-white shadow rounded-lg p-3 flex items-center justify-between">
              <div class="flex items-center">
                <span class="text-gray-600 mr-2">Grid Owner:</span>
                <span class="font-medium">DoinLad</span>
                <span class="ml-1 text-xs bg-blue-100 text-blue-800 rounded px-1">Lv.28</span>
              </div>
              <div class="flex items-center">
                <span class="text-gray-600 mr-2">Coordinates:</span>
                <span class="bg-gray-100 px-2 py-1 rounded">X: 5 Y: 3</span>
              </div>
            </div>
            
            <!-- Actions Card -->
            <div class="bg-white shadow rounded-lg p-3 flex items-center justify-between">
              <div class="flex items-center">
                <span class="text-gray-600 mr-2">Current:</span>
                <span class="font-medium text-indigo-600">Whispering Wilds</span>
              </div>
              <div class="flex space-x-2">
                <button class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                  Claim
                </button>
                <button class="px-3 py-1 bg-gray-800 text-white text-sm rounded hover:bg-gray-900 transition">
                  Travel (8)
                </button>
              </div>
            </div>
          </div>
          
          <!-- Game Grid - Fills Available Space -->
          <div class="bg-white shadow rounded-lg p-3 flex-grow">
            <!-- Travel Dropdown -->
            <div class="flex mb-2">
              <label class="text-gray-600 mr-2 text-sm">Travel to:</label>
              <select class="border rounded px-2 py-1 text-sm bg-white">
                <option>Whispering Wilds (Lv. 1-10)</option>
              </select>
            </div>
            
            <!-- Grid Container with auto-sizing tiles -->
            <div class="grid-container">
              <!-- We'll generate 90 tiles (10x9 grid) for demo -->
              <!-- Grid Items with fixed size to prevent oversizing -->
              <!-- Row 1 -->
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-purple-700 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-purple-700 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-gray-300 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-purple-700 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-gray-300 rounded-sm"></div>
              
              <!-- Row 2 -->
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-purple-700 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-gray-300 rounded-sm"></div>
              <div class="aspect-square bg-gray-300 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-purple-700 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-gray-300 rounded-sm"></div>
              <div class="aspect-square bg-gray-300 rounded-sm"></div>
              
              <!-- Row 3 -->
              <div class="aspect-square bg-purple-700 rounded-sm"></div>
              <div class="aspect-square bg-purple-700 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-purple-700 rounded-sm"></div>
              <div class="aspect-square bg-purple-700 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              
              <!-- Row 4 -->
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-gray-300 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-gray-300 rounded-sm"></div>
              <div class="aspect-square bg-gray-300 rounded-sm"></div>
              <div class="aspect-square bg-yellow-500 rounded-sm"></div>
              <div class="aspect-square bg-purple-700 rounded-sm"></div>
              <div class="aspect-square bg-purple-700 rounded-sm"></div>
              <div class="aspect-square bg-gray-300 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-gray-300 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-gray-300 rounded-sm"></div>
              <div class="aspect-square bg-gray-300 rounded-sm"></div>
              <div class="aspect-square bg-yellow-500 rounded-sm"></div>
              <div class="aspect-square bg-purple-700 rounded-sm"></div>
              
              <!-- Row 5 -->
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-gray-300 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              
              <!-- Row 6 -->
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-purple-700 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-purple-700 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-purple-700 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              
              <!-- Row 7 -->
              <div class="aspect-square bg-gray-300 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-gray-300 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-gray-300 rounded-sm"></div>
              <div class="aspect-square bg-gray-300 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
              <div class="aspect-square bg-gray-300 rounded-sm"></div>
              <div class="aspect-square bg-red-500 rounded-sm"></div>
            </div>
          </div>
        </div>
        
        <!-- Right Sidebar (Fixed Width) -->
        <aside class="w-64 xl:w-80 p-3 flex-shrink-0 flex flex-col overflow-hidden">
          <!-- Nearby Monsters Card -->
          <div class="bg-white shadow rounded-lg mb-3 flex flex-col h-1/2">
            <div class="px-3 py-2 border-b border-gray-200">
              <h3 class="font-medium">Nearby Monsters</h3>
            </div>
            
            <div class="scrollable-area flex-grow">
              <ul class="divide-y divide-gray-100">
                <li class="p-3">
                  <div class="flex justify-between items-center">
                    <div>
                      <h4 class="font-medium">Poisoned Bat <span class="text-xs bg-gray-200 text-gray-700 px-1 rounded">Lv. 5</span></h4>
                      <div class="text-xs text-gray-600">HP: 45 / SPD: 8</div>
                    </div>
                    <button class="px-2 py-1 bg-blue-800 text-white text-xs rounded hover:bg-blue-900">Battle</button>
                  </div>
                </li>
                <li class="p-3">
                  <div class="flex justify-between items-center">
                    <div>
                      <h4 class="font-medium">Basic Rat <span class="text-xs bg-gray-200 text-gray-700 px-1 rounded">Lv. 1</span></h4>
                      <div class="text-xs text-gray-600">HP: 15 / SPD: 2</div>
                    </div>
                    <button class="px-2 py-1 bg-blue-800 text-white text-xs rounded hover:bg-blue-900">Battle</button>
                  </div>
                </li>
                <li class="p-3">
               

<script type="module" src="assets/js/playerStats.js"></script>
<script type="module" src="assets/js/dashboard-v2.js"></script>
<script src="assets/js/main.js"></script>