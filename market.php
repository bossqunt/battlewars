<?php
session_start();
include 'includes/sidebar.php';
require_once 'controller/Market.php';
require_once 'controller/Player.php';

// Determine filter
$filter = $_GET['filter'] ?? 'all';
$status = 'active';
$ownerId = null;
if ($filter === 'my') {
  $ownerId = $playerId ?? null;
} elseif ($filter === 'past') {
  $status = 'sold';
  $ownerId = $playerId ?? null;
}
$market = new Market();
$listings = $market->getListings($_GET['search'] ?? '', $ownerId, $status);
?>


<h1 class="text-x2 py-1 mb-1">
  <span class="text-muted-foreground font-light">Battlewarz /</span>
  <span class="font-bold"> Marketplace</span>
</h1>

<div class="w-full !overflow-x-hidden rpg-panel space-y-4 p-4">

  <div class="mb-4 flex gap-2">
    <form method="GET" class="flex gap-2">
      <input type="text" name="search" placeholder="Search items..." class="p-2 border rounded"
        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
      <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
      <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Search</button>
    </form>
    <a href="?filter=all" class="inline-flex items-center px-3 py-1 rounded bg-blue-500 text-white hover:bg-blue-600 transition <?= $filter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200' ?>">All Listings</a>
    <a href="?filter=my" class="inline-flex items-center px-3 py-1 rounded bg-blue-500 text-white hover:bg-blue-600 transition <?= $filter === 'my' ? 'bg-blue-600 text-white' : 'bg-gray-200' ?>">My Listings</a>
    <a href="?filter=past" class=" inline-flex items-center px-3 py-1 rounded bg-blue-500 text-white hover:bg-blue-600 transition <?= $filter === 'past' ? 'bg-blue-600 text-white' : 'bg-gray-200' ?>">Past Listings</a>
  </div>

  <div class="!overflow-x-none">
    <table class="min-w-full divide-y divide-gray-200 border border-gray-300  rounded-lg shadow-sm bg-white">
      <thead class="bg-gray-100">
        <tr class="bg-gray-100">
          <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase text-center">SELLER</th>
          <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase text-center">ITEM NAME</th>
          <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase text-center">TYPE</th>
          <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase text-center">RARITY</th>
          <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase">STATS</th>
          <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase text-center">PRICE</th>
          <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase text-center">OFFER</th>
          <?php if ($status !== 'sold'): ?>
            <th class="px-3 py-2 text-left text-xs font-medium text-gray-700 uppercase text-center">ACTIONS</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($listings as $listing): ?>
          <tr class="border-b hover:bg-gray-50 text-xs text-gray-600 ">
            <td class="p-2 text-center">
              <?php
                // Show seller name, with [GM] if admin, and image if available
                $sellerName = $listing['seller_name'] ?? '';
                $sellerImage = $listing['seller_image_path'] ?? '';
                if ($sellerImage) {
                  echo '<img src="' . htmlspecialchars($sellerImage) . '" alt="" class="inline w-6 h-6 rounded-full mr-1 align-middle">';
                }
                echo htmlspecialchars($sellerName);
              ?>
            </td>
            <td class="p-2 text-center "><?= htmlspecialchars($listing['item_name']) ?></td>
            <td class="p-2 capitalize text-center"><?= htmlspecialchars($listing['type'] ?? '') ?></td>
            <td class="p-2 text-center">
              <span class="inline-block px-2 py-0.5 rounded-full font-semibold rarity-badge "
                data-rarity="<?= (int) ($listing['rarity'] ?? 0) ?>">
                <!-- JS will fill this in -->
              </span>
            </td>
            <td class="p-2 align-top">
  <div class="grid grid-cols-2 gap-x-8 gap-y-1 text-xs">
    <?php if ($listing['attack'] ?? 0): ?>
      <div class="flex justify-start gap-1">
        <span class="text-muted-foreground">Attack</span>
        <span class="text-foreground font-medium"><?= (int)$listing['attack'] ?></span>
      </div>
    <?php endif; ?>
    <?php if ($listing['defence'] ?? 0): ?>
      <div class="flex justify-start gap-1">
        <span class="text-muted-foreground ">defence</span>
        <span class="text-foreground font-medium "><?= (int)$listing['defence'] ?></span>
      </div>
    <?php endif; ?>
    <?php if ($listing['speed'] ?? 0): ?>
      <div class="flex justify-start gap-1 ">
        <span class="text-muted-foreground">Speed</span>
        <span class="text-foreground font-medium"><?= (int)$listing['speed'] ?></span>
      </div>
    <?php endif; ?>
    <?php if ($listing['crit_multi'] ?? 0): ?>
      <div class="flex justify-start gap-1">
        <span class="text-muted-foreground">Crit Multi</span>
        <span class="text-foreground font-medium"><?= (int)$listing['crit_multi'] ?>%</span>
      </div>
    <?php endif; ?>
    <?php if ($listing['crit_chance'] ?? 0): ?>
      <div class="flex justify-start gap-1">
        <span class="text-muted-foreground">Crit Chance</span>
        <span class="text-foreground font-medium"><?= (int)$listing['crit_chance'] ?>%</span>
      </div>
    <?php endif; ?>
    <?php if ($listing['life_steal'] ?? 0): ?>
      <div class="flex justify-start gap-1">
        <span class="text-muted-foreground">Life Steal</span>
        <span class="text-foreground font-medium"><?= (int)$listing['life_steal'] ?>%</span>
      </div>
    <?php endif; ?>
    <?php if ($listing['health'] ?? 0): ?>
      <div class="flex justify-start gap-1">
        <span class="text-muted-foreground">Health</span>
        <span class="text-foreground font-medium"><?= (int)$listing['health'] ?></span>
      </div>
    <?php endif; ?>
    <?php if ($listing['stamina'] ?? 0): ?>
      <div class="flex justify-start gap-1">
        <span class="text-muted-foreground">Stamina</span>
        <span class="text-foreground font-medium"><?= (int)$listing['stamina'] ?></span>
      </div>
    <?php endif; ?>
  </div>
</td>
            <td class="p-2 text-center"><?= (int) $listing['price'] ?> Gold</td>
            <td class="p-2 text-center">
              <?= (int) $listing['highest_offer'] ?> Gold
              <?php if ($status !== 'sold' && ($listing['player_id'] ?? null) != ($playerId ?? null)): ?>
                <form method="POST" action="api/marketOffer.php" class="market-action-form flex flex-col items-center gap-1 mt-1">
                  <div class="flex items-center gap-2 justify-center">
                    <input type="hidden" name="listing_id" value="<?= $listing['listing_id'] ?>">
                    <input type="hidden" name="player_id" value="<?= htmlspecialchars($playerId ?? '') ?>">
                    <input type="number" name="offer_amount" min="1" placeholder="Offer"
                      class="w-20 p-1 border border-gray-300 rounded text-xs text-center" />
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs font-medium shadow">
                      Offer
                    </button>
                  </div>
                </form>
              <?php endif; ?>
            </td>
            <?php if ($status !== 'sold'): ?>
            <td class="p-2 text-center">
  <?php if ($status === 'sold' || $status === 'past'): ?>
    <!-- No actions for past listings -->
  <?php elseif (($listing['player_id'] ?? null) == ($playerId ?? null) && $status === 'active'): ?>
    <?php if (!empty($listing['highest_offer']) && !empty($listing['highest_offer_id'])): ?>
      <form method="POST" action="api/marketAcceptOffer.php" class="inline market-action-form">
        <input type="hidden" name="listing_id" value="<?= $listing['listing_id'] ?>">
        <input type="hidden" name="offer_id" value="<?= (int) $listing['highest_offer_id'] ?>">
        <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded text-xs">
          Accept Offer (<?= (int) $listing['highest_offer'] ?>g)
        </button>
      </form>
    <?php endif; ?>
    <form method="POST" action="api/marketRemove.php" class="inline market-action-form ml-2">
      <input type="hidden" name="listing_id" value="<?= $listing['listing_id'] ?>">
      <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded text-xs">Remove</button>
    </form>
    <?php if (empty($listing['highest_offer'])): ?>
      <!-- <span class="text-xs text-gray-400 ">No offers</span> -->
    <?php endif; ?>
  <?php else: ?>
    <form method="POST" action="api/marketBuy.php" class="market-action-form">
      <input type="hidden" name="listing_id" value="<?= $listing['listing_id'] ?>">
      <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded text-xs">
        Buy item
      </button>
    </form>
  <?php endif; ?>
</td>
            <?php endif; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<script type="module">
  import { getRarityLabel, getRarityBadgeClass, getRarityBorderClass } from './assets/js/rarity.js';
  import { showToast } from './assets/js/ui.js';

  document.querySelectorAll('.rarity-badge').forEach(el => {
    const rarity = parseInt(el.dataset.rarity, 10);
    el.textContent = getRarityLabel(rarity);
    el.className = 'inline-block px-2 py-0.5 rounded-full font-semibold ' +
      getRarityBadgeClass(rarity) + ' ' + getRarityBorderClass(rarity);
  });

  // Intercept market action forms (Buy/Offer/Accept Offer)
  document.querySelectorAll('.market-action-form').forEach(form => {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(form);
      fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
        .then(res => res.json())
        .then(data => {
          if (Array.isArray(data)) data = data[0];
          showToast(data.message || 'Unknown response', !!data.success);
          if (data.success) setTimeout(() => window.location.reload(), 1200);
        })
        .catch(() => showToast('An error occurred', false));
    });
  });
</script>
<script type="module" src="assets/js/playerStats.js"></script>
<?php include 'includes/footer.php'; ?>