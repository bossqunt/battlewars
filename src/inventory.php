<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'includes/sidebar.php';
require 'controller/Player.php';

require_once 'controller/Database.php';

function compareStat($invValue, $eqValue)
{
  if ($invValue > $eqValue) {
    return ['+' . ($invValue - $eqValue), 'text-rpg-success', '↑']; // Green for positive
  } elseif ($invValue < $eqValue) {
    return ['-' . ($eqValue - $invValue), 'text-rpg-danger', '↓']; // Red for negative
  } else {
    return ['0', 'text-muted-foreground', '–']; // Neutral for equal
  }
}

?>

<?php mainContainer('main-container', 'max-w-6xl mx-auto', '', true); ?>
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
          <span class="text-gray-700 font-semibold">Inventory</span>
        </li>
      </ol>
    </nav>
    <!-- End Breadcrumb -->

    <div class="bg-white border border-slate-200 rounded-lg w-full mb-6">
      <div class="p-6">
        <div class="w-full overflow-x-hidden space-y-4">
          <style>
            /* Override active tab color to white background and black text */
            [data-state="active"] {
              background-color: #fff !important;
              color: #000 !important;
            }
          </style>
          <div role="tablist"
            class="h-10 items-center justify-center rounded-md bg-muted p-1 text-muted-foreground grid w-full grid-cols-2">
            <button id="equippedBtn" data-state="active"
              class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm">
              Equipped
            </button>
            <button id="inventoryBtn"
              class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm">
              Inventory
            </button>
          </div>

          <div id="inventorySection" class="grid grid-cols-2 gap-4"></div>
          <div id="equippedSection" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>

        </div>
       
      </div>
    </div>
  <?php mainContainerClose(true); ?>

  <!-- Core JS -->
  <script src="assets/vendor/libs/jquery/jquery.js"></script>
  <script src="assets/vendor/libs/popper/popper.js"></script>
  <script src="assets/vendor/js/bootstrap.js"></script>
  <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="assets/vendor/js/menu.js"></script>
  <script src="assets/js/main.js"></script>
  <!-- / Core JS -->

  <script>
  </script>

</body>

</html>

<script type="module">
  import { showToast } from './assets/js/ui.js';

  // Add a list of all equipment types
  const EQUIP_TYPES = ['Weapon', 'Shield', 'Helmet', 'Armor', 'Legs', 'Boots', 'Amulet', 'Ring'];

  function renderItemCard(item, isEquipped = false, equippedItems = {}) {
    const stats = ['attack', 'defence', 'crit_multi', 'crit_chance', 'speed', 'health', 'stamina', 'life_steal'];

    const equippedItem = equippedItems[item.type]; // Match equipped item by type

    console.log('Equipped Item:', equippedItem); // Debugging line
    const statHtml = stats.map(stat => {
      const value = item[stat];
      if (value != 0) {
        const label = stat.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());

        let comparison = '';
        if (!isEquipped && equippedItem && equippedItem[stat] != null) {
          const diff = value - equippedItem[stat];
          const diffClass = diff > 0 ? 'text-green-600' : diff < 0 ? 'text-red-600' : 'text-gray-400';
          const arrow = diff > 0 ? '↑' : diff < 0 ? '↓' : '–';
          comparison = `<span class="min-w-[30px] text-right ${diffClass}">${arrow} ${Math.abs(diff)}</span>`;
        } else {
          comparison = `<span class="min-w-[30px]"></span>`;
        }

        return `
      <div class="flex justify-between items-center gap-2">
        <span class="text-muted-foreground flex-1">${label}</span>
        ${comparison}
        <span class="text-foreground font-medium min-w-[30px] text-right">${value}</span>
      </div>`;
      }
      return '';
    }).join('');


    const badge = `<span class="rounded-full px-2 py-0.5 text-xs font-semibold ${getRarityBadgeClass(item.rarity)}">
    ${item.rarity_text}
  </span>`;

    const action = isEquipped ? 'Unequip' : 'Equip';
    const form = `
    <form class="equip-form mt-4" data-id="${item.id}">
      <button type="submit" class="w-full text-xs py-1.5 rounded-md bg-muted hover:bg-muted/80 border text-foreground transition">
        ${action}
      </button>
    </form>`;

    const sellButtons = !isEquipped && item.gold_value ? `
    <div class="mt-3 flex justify-between items-center">
      <button class="sell-btn text-xs px-3 py-1 rounded-md bg-red-100 text-red-700 hover:bg-red-200 transition" data-id="${item.id}" data-gold="${item.gold_value}">
        Sell
      </button>
      <div class="flex items-center gap-1 text-amber-600 text-xs font-semibold">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="8"></circle>
          <path d="M9 10c0-1.1.9-2 2-2h2a2 2 0 1 1 0 4h-2 2a2 2 0 1 1 0 4h-2c-1.1 0-2-.9-2-2"></path>
        </svg>
        ${item.gold_value}
      </div>
      <button class="market-list-btn text-xs px-3 py-1 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300 transition" data-id="${item.id}">
        List
      </button>
    </div>` : '';

    const rarityClass = getRarityClasses(item.rarity);
    // Add rarity name color class to item name
    return `
    <div class="bg-white/80 rounded-xl p-3 text-sm space-y-2 transition hover:shadow-md ${getRarityClasses(item.rarity)}">
      <div class="flex justify-between items-start">
        <div>
          <div class="font-semibold text-foreground ${getRarityNameClass(item.rarity)}">${item.name}</div>
          <div class="text-xs text-muted-foreground capitalize">${item.type}</div>
        </div>
        ${badge}
      </div>
      <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs">
        ${statHtml}
      </div>
      ${form}
      ${sellButtons}
    </div>`;
  }

  // Add this function for rarity name color
  function getRarityNameClass(rarity) {
    switch (rarity) {
      case 1: return 'text-gray-700';      // Common
      case 2: return 'text-green-600';     // Uncommon
      case 3: return 'text-blue-600';      // Rare
      case 4: return 'text-purple-600';    // Epic
      case 5: return 'text-orange-500';    // Legendary
      case 6: return 'text-red-500';       // Godly
      default: return 'text-gray-700';
    }
  }

  function getRarityClasses(rarity) {
    switch (rarity) {
      case 1: return 'border border-gray-300 hover:border-gray-600 hover:shadow-md hover:shadow-gray-400';
      case 2: return 'border border-gray-300 hover:border-green-600 hover:shadow-md hover:shadow-green-400';
      case 3: return 'border border-gray-300 hover:border-blue-600 hover:shadow-md hover:shadow-blue-400';
      case 4: return 'border border-gray-300 hover:border-purple-600 hover:shadow-md hover:shadow-purple-400';
      case 5: return 'border border-gray-300 hover:border-orange-500 hover:shadow-md hover:shadow-orange-400';
      case 6: return 'border border-gray-300 hover:border-red-500 hover:shadow-md hover:shadow-red-400';
      default: return 'border border-gray-300 hover:shadow-md hover:shadow-gray-300';
    }
  }

  function getRarityBadgeClass(rarity) {
    switch (rarity) {
      case 1: return 'bg-black text-white';
      case 2: return 'bg-green-600 text-white shadow-md shadow-green-400 m-2';
      case 3: return 'bg-blue-600 text-white shadow-md shadow-blue-400 m-2';
      case 4: return 'bg-purple-600 text-white text-white shadow-md shadow-purple-400 m-2';
      case 5: return 'bg-orange-500 text-white text-white shadow-md shadow-orange-400 m-2';
      case 6: return 'bg-red-400 text-white text-white shadow-md shadow-red-400 m-2';
      default: return 'bg-gray-300 text-black';
    }
  }

  function getRarityLabel(rarity) {
    switch (rarity) {
      case 1: return 'Common';
      case 2: return 'Uncommon';
      case 3: return 'Rare';
      case 4: return 'Epic';
      case 5: return 'Legendary';
      case 6: return 'Godly';
    }
  }

  // Add a function to render an empty placeholder card for a given type
  function renderEmptyEquipCard(type) {
    return `
    <div class="bg-white/60 rounded-xl p-3 text-sm space-y-2 border-2 border-dotted border-gray-200 flex flex-col justify-between min-h-[140px]">
      <div class="flex justify-between items-start">
        <div>
          <div class="font-semibold text-gray-400">Empty</div>
          <div class="text-xs text-muted-foreground capitalize">${type}</div>
        </div>
      </div>
      <div class="text-xs text-gray-300 italic pt-4">No item equipped</div>
    </div>
  `;
  }

  function loadInventory() {
    $.get('api/getPlayerInventory.php', data => {
      $('#equippedSection').html('');
      $('#inventorySection').html('');

      const equippedItems = {};
      data.equipped.forEach(item => {
        item.rarity_text = getRarityLabel(item.rarity);
        equippedItems[item.type] = item;
      });

      // Render equipped items or empty slots for each type
      EQUIP_TYPES.forEach(type => {
        if (equippedItems.hasOwnProperty(type)) {
          $('#equippedSection').append(renderItemCard(equippedItems[type], true));
        } else {
          $('#equippedSection').append(renderEmptyEquipCard(type));
        }
      });

      data.inventory.forEach(item => {
        item.rarity_text = getRarityLabel(item.rarity);
        $('#inventorySection').append(renderItemCard(item, false, equippedItems));
      });
    });
  }


  $(document).ready(function () {
    loadInventory();

    // Default view setup
    $('#inventorySection').hide(); // Hide inventory section initially
    $('#equippedBtn').attr('data-state', 'active'); // Mark Equipped as active

    $('#equippedBtn').click(() => {
      $('#equippedSection').show();
      $('#inventorySection').hide();

      $('#equippedBtn').attr('data-state', 'active');
      $('#inventoryBtn').removeAttr('data-state');
    });

    $('#inventoryBtn').click(() => {
      $('#inventorySection').show();
      $('#equippedSection').hide();

      $('#inventoryBtn').attr('data-state', 'active');
      $('#equippedBtn').removeAttr('data-state');
    });


    // Delegate form submission
    $(document).on('submit', '.equip-form', function (e) {
      e.preventDefault();
      const itemId = $(this).data('id');
      $.post('api/playerEquipItem.php', { item_id: itemId }, response => {
        showToast(response.message, response.success);
        loadInventory();
      });
    });

    // Handle sell button click
    $(document).on('click', '.sell-btn', function (e) {
      e.preventDefault();
      const itemId = $(this).data('id');
      const gold = $(this).data('gold');
      if (!confirm(`Sell this item for ${gold} gold?`)) return;
      $.post('api/playerSellItem.php', { item_id: itemId }, response => {
        showToast(response.message, response.success);
        loadInventory();
      });
    });

    // Handle "List on Market" button click
    $(document).on('click', '.market-list-btn', function (e) {
      e.preventDefault();
      const itemId = $(this).data('id');
      const price = prompt('Enter the price (gold) to list this item for:');
      if (!price || isNaN(price) || price <= 0) return;
      $.post('api/marketListItem.php', { player_inventory_id: itemId, price: price }, response => {
        let resp = response;
        if (Array.isArray(response)) {
          resp = response[0];
        }
        showToast(resp.message, resp.success);
        loadInventory();
      }, 'json');
    });
  });
</script>
<script type="module" src="assets/js/playerStats.js"></script>