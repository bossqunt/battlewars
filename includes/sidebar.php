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

</head>

<?php
require_once 'controller/authCheck.php';




?>
<script>
  const playerId = <?php echo json_encode($decodedUser->id); ?>;
  </script>
 <?php
function isActivePage($page)
{
    $currentPage = basename($_SERVER['PHP_SELF']);
    return ($currentPage === $page) ? 'rpg-sidebar-nav-item-active' : '';
}  ?> 


<body class="min-h-screen flex flex-col">
    <header class="flex items-center justify-between px-4 py-3 bg-card shadow-md border-b">
    <!-- Left Side: Player name and EXP -->
    <div class="flex items-center space-x-3">
      <div class="flex flex-col items-start">
        <div class="flex items-center">
          <span class="font-bold text-lg" id="player-name">Loading...</span>
          <span class="ml-2 bg-primary/10 px-1.5 rounded-md text-xs font-semibold" id="player-level">Lvl
            1</span>
        </div>
        <!-- EXP Section inside Left Side -->
        <div class="w-64 mt-1">
          <div class="flex justify-between w-full text-xs">
            <span class="text-muted-foreground">EXP</span>
            <span class="text-amber-500 font-medium" id="exp-value">20 / 100</span>
          </div>
          <div class="relative w-full overflow-hidden rounded-full h-2 mt-0.5 bg-amber-100/20">
            <div id="exp-bar" class="h-full transition-all bg-gradient-to-r from-amber-400 to-amber-300"
              style="width: 20%;"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Side: HP, Stamina, Gold -->
    <div class="flex items-center space-x-8">
      <!-- HP -->
      <div class="flex flex-col items-start space-y-1 w-36">
        <div class="flex justify-between w-full text-xs">
          <span class="font-medium text-destructive">HP</span>
          <span class="font-medium text-destructive" id="hp-value">Loading...</span>
        </div>
        <div class="relative w-full overflow-hidden rounded-full h-3 bg-red-100/20">
          <div
            class="h-full w-full flex-1 bg-primary transition-all bg-gradient-to-r from-red-500 to-red-400 animate-pulse"
            id="hp-bar"></div>
        </div>
      </div>

      <!-- Stamina -->
      <div class="flex flex-col items-start space-y-1 w-36">
        <div class="flex justify-between w-full text-xs">
          <span class="font-medium text-blue-500">Stamina</span>
          <span class="font-medium text-blue-500" id="st-value">Loading...</span>
        </div>
        <div class="relative w-full overflow-hidden rounded-full h-3 bg-blue-100/20">
          <div class="h-full w-full flex-1 bg-primary transition-all bg-gradient-to-r from-blue-500 to-blue-400"
            id="st-bar"></div>
        </div>
      </div>


<!-- Gold -->
<div class="flex items-center">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500 mr-1" viewBox="0 0 24 24" fill="none"
    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="12" cy="12" r="8"></circle>
    <path d="M9 10c0-1.1.9-2 2-2h2a2 2 0 1 1 0 4h-2 2a2 2 0 1 1 0 4h-2c-1.1 0-2-.9-2-2"></path>
  </svg>
  <span class="font-bold text-amber-500" id="gold-value">55</span>
</div>
  </header>

  <!-- Main Layout: Sidebar + Page Content -->
  <div class="flex flex-1 min-h-0">
    <!-- Sidebar -->
    <aside class="w-56 border-r bg-sidebar p-3 flex flex-col h-full">
    <div class="mb-3">
        <h2 class="font-medium text-xs text-sidebar-foreground">Navigation</h2>
      </div>

      <nav class="space-y-1">
        <a href="dashboard.php" class="rpg-sidebar-nav-item <?php echo isActivePage('dashboard.php'); ?>">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <span class="text-[14px]">Overworld</span>
        </a>

        <a href="Fountain.php" class="rpg-sidebar-nav-item <?php echo isActivePage('Fountain.php'); ?>">
          <i class="menu-icon tf-icons bx bx-bible"></i>
          <span class="text-[14px]">Fountain</span>
        </a>

        <a href="inventory.php" class="rpg-sidebar-nav-item <?php echo isActivePage('inventory.php'); ?>">
          <i class="menu-icon tf-icons bx bx-archive"></i>
          <span class="text-[14px]">Inventory</span>
          <span class="ml-auto text-[9px] bg-primary px-1.5 py-0.5 rounded-full text-white" id="inventorycount">0</span>
        </a>

        <a href="market.php" class="rpg-sidebar-nav-item <?php echo isActivePage('market.php'); ?>">
          <i class="menu-icon tf-icons bx bx-cart"></i>
          <span class="text-[14px]">Market</span>
        </a>

        <a href="profile.php" class="rpg-sidebar-nav-item <?php echo isActivePage('Profile.php'); ?>">
          <i class="menu-icon tf-icons bx bx-id-card"></i>
          <span class="text-[14px]">Profile</span>
        </a>

        <a href="logout.php" class="rpg-sidebar-nav-item <?php echo isActivePage('Logout.php'); ?>">
          <i class="menu-icon tf-icons bx bx-log-out"></i>
          <span class="text-[14px]">Logout</span>
        </a>
        <ul class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-200 dark:border-gray-700">
        <div class="mb-3">
        <h2 class="font-medium text-xs text-sidebar-foreground">Admin Tools</h2>
      </div>
        <?php if ($isAdmin == 1) { ?>
          <a href="admin.php" class="rpg-sidebar-nav-item <?php echo isActivePage('Admin.php'); ?>">
            <i class="menu-icon tf-icons bx bx-cog"></i>
            <span class="text-[14px]">Console</span>
          </a>
        <?php } ?>
        <?php if ($isAdmin == 1) { ?>
          <a href="generate-monster.php" class="rpg-sidebar-nav-item <?php echo isActivePage('Admin.php'); ?>">
            <i class="menu-icon tf-icons bx bx-dna"></i>
            <span class="text-[14px]">Monsters SQL</span>
          </a>
        <?php } ?>
        <?php if ($isAdmin == 1) { ?>
          <a href="generate-items.php" class="rpg-sidebar-nav-item <?php echo isActivePage('Admin.php'); ?>">
            <i class="menu-icon tf-icons bx bx-package"></i>
            <span class="text-[14px]">Items SQL</span>
          </a>
        <?php } ?>
        <?php if ($isAdmin == 1) { ?>
          <a href="adminScheduler.php" class="rpg-sidebar-nav-item <?php echo isActivePage('adminScheduler.php'); ?>">
            <i class="menu-icon tf-icons bx bx-calendar"></i>
            <span class="text-[14px]">Scheduler</span>
          </a>
        <?php } ?>
      </nav>

            <!-- Battle Log -->
            <div class="mt-auto pt-3 border-t">
                <div class="battle-log h-36 overflow-y-auto max-h-15">
                    <h3 class="font-medium mb-1 text-[11px]">Battle Log</h3>
                    <div class="space-y-0.5 " id="battle-log">   
                        <!-- <div class="rpg-battle-log-entry rpg-battle-log-info text-[10px]">No recent battles</div> -->
                    </div>
                </div>
            </div>
        </aside>


        <!-- Main Page Content -->
        <main class="flex-1 p-4 overflow-y-auto">
            <!-- Replace this with your actual page content -->
            <div class="flex-1 overflow-auto p-0">
                <!-- Your content goes here -->

