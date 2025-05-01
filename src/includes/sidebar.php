<?php
require_once 'controller/authCheck.php'; ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <title>BW2 Refactored</title>
    <meta name="description" content="">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css">

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css">
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css">

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css">

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    
    <link rel="stylesheet" href="assets/css/custom.css">

    <style>
        /* Custom styles for improved layout */
        .sidebar-collapsed .nav-text {
            display: none;
        }
        
        .sidebar-collapsed .sidebar-icon {
            margin-right: 0;
        }
        
        .sidebar-collapsed {
            width: 4rem !important;
        }
        
        .sidebar-expanded {
            width: 15rem;
        }
        
        .sidebar-collapsed .battle-log h3,
        .sidebar-collapsed .nav-section-title {
            display: none;
        }
        
        .sidebar-collapsed .battle-log {
            overflow: hidden;
        }
        
        .sidebar-collapsed .inventory-badge {
            position: absolute;
            top: 0;
            right: 0;
            transform: translateY(-30%) translateX(30%);
        }
        
        /* HP Bar gradient transitions */
        #hp-bar {
            transition: background-color 0.5s ease, width 0.5s ease;
        }
        
        /* Refined status bars */
        .status-bar {
            height: 0.5rem;
            border-radius: 9999px;
            overflow: hidden;
        }
        
        .status-value {
            transition: width 0.5s ease;
        }
    </style>
</head>

<?php
function isActivePage($page)
{
    $currentPage = basename($_SERVER['PHP_SELF']);
    return ($currentPage === $page) ? 'rpg-sidebar-nav-item-active' : '';
}
// Main container helpers for consistent layout
if (!function_exists('mainContainer')) {
  function mainContainer($id = 'main-container', $class = 'max-w-6xl mx-auto', $attrs = '', $withBody = false, $bodyClass = 'bg-gray-100 p-6')
  {
      if ($withBody) {
          echo "<body class=\"" . htmlspecialchars($bodyClass) . "\">";
      }
      echo "<div id=\"" . htmlspecialchars($id) . "\" class=\"" . htmlspecialchars($class) . "\" $attrs>";
  }
}

if (!function_exists('mainContainerClose')) {
  function mainContainerClose($withBody = false)
  {
      echo "</div>";
      if ($withBody) {
          echo "</body>";
      }
  }
}

?>


<script>
  const playerId = <?php echo json_encode($decodedUser->id); ?>;
</script>

<body class="min-h-screen flex flex-row bg-gray-50">
    <!-- Sidebar - Full height -->
    <aside id="sidebar" class="sidebar-expanded h-screen bg-sidebar border-r flex flex-col transition-all duration-300 z-10 overflow-y-auto">
        <!-- Logo or Game Title -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between" id="logo">
            <div class="text-lg font-bold text-gray-800">
              <img src="images/logo2.png" style="width: 190px;" class="justify-center center">
            </div>
            <button id="mobile-sidebar-toggle" class="md:hidden text-gray-700 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

    <!-- Navigation Section -->
<div class="p-3">
    <h2 class="font-medium text-xs text-sidebar-foreground uppercase nav-section-title">Navigation</h2>
    <nav class="space-y-1 mt-2">
        <a href="home.php" class="rpg-sidebar-nav-item flex items-center relative <?php echo isActivePage('home.php'); ?>">
            <i class="sidebar-icon menu-icon tf-icons bx bx-home-alt mr-3"></i>
            <span class="text-[14px] nav-text">Home</span>
        </a>
        <a href="dashboard.php" class="rpg-sidebar-nav-item flex items-center relative <?php echo isActivePage('dashboard.php'); ?>">
            <i class="sidebar-icon menu-icon tf-icons bx bx-map mr-3"></i>
            <span class="text-[14px] nav-text">Overworld</span>
        </a>
        <a href="guilds.php" class="rpg-sidebar-nav-item flex items-center relative <?php echo isActivePage('guilds.php'); ?>">
            <i class="sidebar-icon menu-icon tf-icons bx bx-group mr-3"></i>
            <span class="text-[14px] nav-text">Guilds</span>
        </a>
        <a href="fountain.php" class="rpg-sidebar-nav-item flex items-center relative <?php echo isActivePage('fountain.php'); ?>">
            <i class="sidebar-icon menu-icon tf-icons bx bx-book-open mr-3"></i>
            <span class="text-[14px] nav-text">Fountain</span>
        </a>
        <a href="inventory.php" class="rpg-sidebar-nav-item flex items-center relative <?php echo isActivePage('inventory.php'); ?>">
            <i class="sidebar-icon menu-icon tf-icons bx bx-box mr-3"></i>
            <span class="text-[14px] nav-text">Inventory</span>
            <span class="inventory-badge text-[9px] bg-primary px-1.5 py-0.5 rounded-full text-white ml-auto" id="inventorycount">0</span>
        </a>
        <a href="market.php" class="rpg-sidebar-nav-item flex items-center relative <?php echo isActivePage('market.php'); ?>">
            <i class="sidebar-icon menu-icon tf-icons bx bx-store mr-3"></i>
            <span class="text-[14px] nav-text">Market</span>
        </a>
        <a href="profile.php" class="rpg-sidebar-nav-item flex items-center relative <?php echo isActivePage('Profile.php'); ?>">
            <i class="sidebar-icon menu-icon tf-icons bx bx-user mr-3"></i>
            <span class="text-[14px] nav-text">Profile</span>
        </a>
        <a href="players.php" class="rpg-sidebar-nav-item flex items-center relative <?php echo isActivePage('players.php'); ?>">
            <i class="sidebar-icon menu-icon tf-icons bx bx-user-circle mr-3"></i>
            <span class="text-[14px] nav-text">Players</span>
        </a>
        <a href="logout.php" class="rpg-sidebar-nav-item flex items-center relative <?php echo isActivePage('logout.php'); ?>">
            <i class="sidebar-icon menu-icon tf-icons bx bx-log-out mr-3"></i>
            <span class="text-[14px] nav-text">Logout</span>
        </a>
    </nav>
</div>

        <!-- Admin Tools (if admin) -->
        <?php if ($isAdmin == 1) { ?>
        <div class="p-3 pt-0">
            <h2 class="font-medium text-xs text-sidebar-foreground uppercase mt-4 pb-2 border-t border-gray-200 dark:border-gray-700 pt-3 nav-section-title">Admin Tools</h2>
            <nav class="space-y-1 mt-2">
                <a href="admin.php" class="rpg-sidebar-nav-item flex items-center relative <?php echo isActivePage('Admin.php'); ?>">
                    <i class="sidebar-icon menu-icon tf-icons bx bx-cog mr-3"></i>
                    <span class="text-[14px] nav-text">Console</span>
                </a>
                <a href="generate-monster.php" class="rpg-sidebar-nav-item flex items-center relative <?php echo isActivePage('generate-monster.php'); ?>">
                    <i class="sidebar-icon menu-icon tf-icons bx bx-dna mr-3"></i>
                    <span class="text-[14px] nav-text">Monsters SQL</span>
                </a>
                <a href="generate-items.php" class="rpg-sidebar-nav-item flex items-center relative <?php echo isActivePage('generate-items.php'); ?>">
                    <i class="sidebar-icon menu-icon tf-icons bx bx-package mr-3"></i>
                    <span class="text-[14px] nav-text">Items SQL</span>
                </a>
                <a href="adminScheduler.php" class="rpg-sidebar-nav-item flex items-center relative <?php echo isActivePage('adminScheduler.php'); ?>">
                    <i class="sidebar-icon menu-icon tf-icons bx bx-calendar mr-3"></i>
                    <span class="text-[14px] nav-text">Scheduler</span>
                </a>
            </nav>
        </div>
        <?php } ?>

        <!-- Battle Log (at the bottom) -->
        <div class="mt-auto p-3 border-t border-gray-200 dark:border-gray-700" id="battle-log-container">
            <div class="battle-log max-h-36 overflow-y-auto">
                <h3 class="font-medium mb-1 text-[11px] uppercase">Battle Log</h3>
                <div class="space-y-0.5" id="battle-log">
                    <!-- Battle log entries will be inserted here -->
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
        <!-- Header - Now placed to the right of sidebar -->
        <header class="bg-white shadow-sm border-b flex items-center justify-between px-4 py-2 h-16">
            <!-- Left Side: Toggle button and Player info -->
            <div class="flex items-center">
                <button id="sidebarToggle" class="text-gray-700 focus:outline-none mr-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <!-- Player Name and Level -->
                <div class="flex items-center">
                    <span class="font-bold text-lg" id="player-name">Loading...</span>
                    <span class="ml-2 bg-primary/10 px-1.5 rounded-md text-xs font-semibold" id="player-level">Lvl 1</span>
                </div>
            </div>
            
            <!-- Right Side: Status bars and Gold -->
            <div class="flex items-center space-x-6">
                <!-- EXP Bar (now more compact) -->
                <div class="flex flex-col w-48 mr-2">
                    <div class="flex justify-between w-full text-xs">
                        <span class="text-amber-500 font-medium">EXP</span>
                        <span class="text-amber-500 font-medium" id="exp-value">Loading...</span>
                    </div>
                    <div class="status-bar bg-amber-100/90">
                        <div id="exp-bar" class="h-full transition-all bg-gradient-to-r from-amber-400 to-amber-300"></div>
                    </div>
                </div>
                
                <!-- HP Bar (now more compact) -->
                <div class="flex flex-col w-32 mr-2">
                    <div class="flex justify-between w-full text-xs">
                        <span class="font-medium text-green-600">HP</span>
                        <span class="font-medium text-green-600" id="hp-value">Loading...</span>
                    </div>
                    <div class="status-bar bg-green-100/90">
                        <div id="hp-bar" class="h-full w-full flex-1 transition-all" style="width: 75%;"></div>
                    </div>
                </div>
                
                <!-- Stamina Bar (now more compact) -->
                <div class="flex flex-col w-32 mr-2">
                    <div class="flex justify-between w-full text-xs">
                        <span class="font-medium text-blue-500">ST</span>
                        <span class="font-medium text-blue-500" id="st-value">Loading...</span>
                    </div>
                    <div class="status-bar bg-blue-100/90">
                        <div id="st-bar" class="h-full w-full flex-1 bg-primary transition-all bg-gradient-to-r from-blue-500 to-blue-400" style="width: 60%;"></div>
                    </div>
                </div>

                <!-- Gold (more compact) -->
                <div class="flex items-center px-2 py-1 bg-amber-50 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="8"></circle>
                        <path d="M9 10c0-1.1.9-2 2-2h2a2 2 0 1 1 0 4h-2 2a2 2 0 1 1 0 4h-2c-1.1 0-2-.9-2-2"></path>
                    </svg>
                    <span class="font-bold text-amber-500" id="gold-value">Loading...</span>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-4 overflow-y-auto">
            <!-- Your page content goes here -->
            <div class="flex-1 overflow-auto p-0">
         


    <script>
        // Toggle sidebar expanded/collapsed state
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mobileToggle = document.getElementById('mobile-sidebar-toggle');
            const logo = document.getElementById('logo');
            const battleLog = document.getElementById('battle-log-container');
            
            
            // Function to toggle sidebar state
            function toggleSidebar() {
                sidebar.classList.toggle('sidebar-collapsed');
                sidebar.classList.toggle('sidebar-expanded');
                
                // Store preference in localStorage
                if (sidebar.classList.contains('sidebar-collapsed')) {
                    localStorage.setItem('sidebarState', 'collapsed');
                } else {
                    localStorage.setItem('sidebarState', 'expanded');
                }
            }
            
            // Desktop toggle
            toggleBtn.addEventListener('click', toggleSidebar);
            
            // Mobile toggle (close sidebar)
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function() {
                    sidebar.classList.add('sidebar-collapsed');
                    sidebar.classList.remove('sidebar-expanded');
                    localStorage.setItem('sidebarState', 'collapsed');
                    logo.classList.add('hidden');
                    battleLog.classList.add('hidden');
                });
            }
            
            // Check localStorage for saved preference
            const savedState = localStorage.getItem('sidebarState');
            if (savedState === 'collapsed') {
                sidebar.classList.add('sidebar-collapsed');
                sidebar.classList.remove('sidebar-expanded');
                logo.classList.add('hidden');
                console.log('eh')
                battleLog.classList.add('hidden');
            }
            if (savedState === 'expanded') {
                sidebar.classList.add('sidebar-expanded');
                sidebar.classList.remove('sidebar-collapsed');
                logo.classList.remove('hidden');
                battleLog.classList.remove('hidden');
            }
            
            
            // Handle responsive behavior
            function handleResize() {
                if (window.innerWidth < 768) {
                    sidebar.classList.add('sidebar-collapsed');
                    sidebar.classList.remove('sidebar-expanded');
                }
            }
            
            // Initial check and add resize listener
            handleResize();
            window.addEventListener('resize', handleResize);
        });



       

    </script>
</body>
<script type="module" src="assets/js/playerStats.js"></script></html>
