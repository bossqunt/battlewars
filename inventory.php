<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'includes/sidebar.php';
require 'controller/Player.php';
require 'controller/Database.php';

function compareStat($invValue, $eqValue) {
  if ($invValue > $eqValue) {
    return ['+'.($invValue - $eqValue), 'text-rpg-success', '↑']; // Green for positive
  } elseif ($invValue < $eqValue) {
    return ['-'.($eqValue - $invValue), 'text-rpg-danger', '↓']; // Red for negative
  } else {
    return ['0', 'text-muted-foreground', '–']; // Neutral for equal
  }
}
?>

<h1 class="text-x2 py-1 mb-1">
  <span class="text-muted-foreground font-light">Battlewarz /</span>
  <span class="font-bold"> Inventory</span>
</h1>

<div class="w-full overflow-x-hidden rpg-panel space-y-4">
<div role="tablist" class="h-10 items-center justify-center rounded-md bg-muted p-1 text-muted-foreground grid w-full grid-cols-2">
  <button id="equippedBtn" data-state="active" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm">
    Equipped
  </button>
  <button id="inventoryBtn" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm">
    Inventory
  </button>
</div>

<div id="toast" class="fixed top-4 right-4 hidden bg-green-600 text-white px-4 py-2 rounded shadow-md z-50"></div>

<div id="inventorySection" class="grid grid-cols-2 md:grid-cols-6 gap-4"></div>
<div id="equippedSection" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>




</div>
  <?php include 'includes/footer.php';?>

<!-- Core JS -->
<script src="assets/vendor/libs/jquery/jquery.js"></script>
<script src="assets/vendor/libs/popper/popper.js"></script>
<script src="assets/vendor/js/bootstrap.js"></script>
<script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="assets/vendor/js/menu.js"></script>
<script src="assets/js/main.js"></script>
<!-- / Core JS -->


</body>

</html>
<script>
function showToast(message, success = true) {
  const toast = $('#toast');
  toast.removeClass().addClass('fixed top-4 right-4 z-50 px-4 py-2 rounded shadow-md text-white')
       .addClass(success ? 'bg-green-600' : 'bg-red-600')
       .text(message).fadeIn();

  setTimeout(() => toast.fadeOut(), 3000);
}

function renderItemCard(item, isEquipped = false) {
  const stats = ['attack', 'defense', 'crit_multi', 'crit_chance', 'speed', 'health', 'stamina' ,'life_steal'];
  const statHtml = stats.map(stat => {
    return item[stat] != 0 ? 
      `<div class="flex justify-between text-black text-xs"><span>${stat.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}:</span><span class="font-medium">${item[stat]}</span></div>` 
      : '';
  }).join('');

  const action = isEquipped ? 'Unequip' : 'Equip';
  const form = `
    <form class="mt-3 equip-form" data-id="${item.id}">
      <button type="submit" class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-700 w-full">${action}</button>
    </form>`;

  // Add sell button and gold value for inventory items only
  const sellSection = !isEquipped && item.gold_value
    ? `<div class="mt-2 flex items-center justify-between">
        <span class="text-xs text-yellow-700 font-semibold">Gold Price: <span class="font-bold">${item.gold_value}</span></span>
        <button class="sell-btn bg-yellow-500 hover:bg-yellow-600 text-white text-xs px-2 py-1 rounded" data-id="${item.id}" data-gold="${item.gold_value}">Sell</button>
      </div>`
    : '';

  const badge = `<div class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold ${getRarityBadgeClass(item.rarity)}">
      ${item.rarity_text}
    </div>`;

  return `
    <div class="border rounded-md p-3 ${getRarityClasses(item.rarity)} bg-muted/40">
      <div class="flex justify-between items-start">
        <div>
          <div class="text-sm font-bold">${item.name}</div>
          <div class="text-xs text-muted-foreground capitalize">${item.type}</div>
        </div>
        ${badge}
      </div>
      <div class="mt-2 grid grid-cols-2 gap-x-2 gap-y-1 text-xs">${statHtml}</div>
      ${form}
      ${sellSection}
    </div>`;
}

function getRarityClasses(rarity) {
  switch (rarity) {
    case 1: return '!border-[2px] !border-grey-600 text-black';
    case 2: return '!border-[2px] !border-green-600 text-green-600';
    case 3: return '!border-[2px] !border-blue-600 text-blue-600';
    case 4: return '!border-[2px] !border-purple-600 text-purple-600';
    case 5: return '!border-[2px] !border-orange-500 text-white-500';
    case 6: return '!border-[2px] !border-red-500 text-red-500';
    default: return '!border-[2px] !border-gray-300 text-gray-700';
  }
}

function getRarityBadgeClass(rarity) {
  switch (rarity) {
    case 1: return 'bg-black text-white';
    case 2: return 'bg-green-600 text-white';
    case 3: return 'bg-blue-600 text-white';
    case 4: return 'bg-purple-600 text-white';
    case 5: return 'bg-orange-500 text-white';
    case 6: return 'bg-red-400 text-white';
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


function loadInventory() {
  $.get('api/getPlayerInventory.php', data => {
    $('#equippedSection').html('');
    $('#inventorySection').html('');

    data.equipped.forEach(item => {
      item.rarity_text = getRarityLabel(item.rarity);
      $('#equippedSection').append(renderItemCard(item, true));
    });

    data.inventory.forEach(item => {
      item.rarity_text = getRarityLabel(item.rarity);
      $('#inventorySection').append(renderItemCard(item, false));
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
});
</script>
<script type="module" src="assets/js/playerStats.js"></script>
