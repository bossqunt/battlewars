<?php
session_start();
include 'includes/sidebar.php';
require_once 'controller/Market.php';
require_once 'controller/Player.php';


$market = new Market();
$listings = $market->getListings($_GET['search'] ?? '');

?>


<h1 class="text-x2 py-1 mb-1">
  <span class="text-muted-foreground font-light">Battlewarz /</span>
  <span class="font-bold"> Marketplace</span>
</h1>

<div class="w-full overflow-x-hidden rpg-panel space-y-4">

  <div class="mb-4">
    <form method="GET" class="flex gap-2">
      <input type="text" name="search" placeholder="Search items..." class="p-2 border rounded"
        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
      <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Search</button>
    </form>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full bg-white border border-gray-200 rounded shadow text-xs">
      <thead>
        <tr class="bg-gray-100">
          <th class="p-2 border-b">Seller</th>
          <th class="p-2 border-b">Item Name</th>
          <th class="p-2 border-b">Type</th>
          <th class="p-2 border-b">Rarity</th>
          <th class="p-2 border-b">Stats</th>
          <th class="p-2 border-b">Price</th>
          <th class="p-2 border-b">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($listings as $listing): ?>
          <tr class="border-b hover:bg-gray-50">
            <td class="p-2"><?= htmlspecialchars($listing['seller_name']) ?></td>
            <td class="p-2"><?= htmlspecialchars($listing['item_name']) ?></td>
            <td class="p-2 capitalize"><?= htmlspecialchars($listing['type'] ?? '') ?></td>
            <td class="p-2">
              <span class="inline-block px-2 py-0.5 rounded-full font-semibold rarity-badge"
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
    <?php if ($listing['defense'] ?? 0): ?>
      <div class="flex justify-start gap-1">
        <span class="text-muted-foreground">Defense</span>
        <span class="text-foreground font-medium"><?= (int)$listing['defense'] ?></span>
      </div>
    <?php endif; ?>
    <?php if ($listing['speed'] ?? 0): ?>
      <div class="flex justify-start gap-1">
        <span class="text-muted-foreground">Speed</span>
        <span class="text-foreground font-medium"><?= (int)$listing['speed'] ?></span>
      </div>
    <?php endif; ?>
    <?php if ($listing['crit_multi'] ?? 0): ?>
      <div class="flex justify-start gap-1">
        <span class="text-muted-foreground">Crit Multi</span>
        <span class="text-foreground font-medium"><?= (int)$listing['crit_multi'] ?></span>
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
  </div>
</td>












            <td class="p-2 font-bold"><?= (int) $listing['price'] ?> gold</td>
            <td class="p-2">
              <?php if (($listing['player_id'] ?? null) == ($playerId ?? null)): ?>
                <span class="text-xs text-gray-400">Your Listing</span>
                <?php if (!empty($listing['offer'])): ?>
                  <form method="POST" action="api/marketAcceptOffer.php" class="inline market-action-form">
                    <input type="hidden" name="listing_id" value="<?= $listing['id'] ?>">
                    <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded text-xs">Accept Offer
                      (<?= (int) ($listing['offer_amount'] ?? $listing['offer']) ?>g)</button>
                  </form>
                <?php endif; ?>
              <?php else: ?>
                <form method="POST" action="api/marketBuy.php" class="inline w-full mb-1 market-action-form">
                  <input type="hidden" name="listing_id" value="<?= $listing['id'] ?>">
                  <button type="submit" class="bg-yellow-500 text-white px-2 py-1 rounded text-xs w-full">Buy</button>
                </form>
                <form method="POST" action="api/marketOffer.php" class="inline w-full mt-1 market-action-form">
                  <input type="hidden" name="listing_id" value="<?= $listing['id'] ?>">
                  <input type="number" name="offer_amount" min="1" placeholder="Offer"
                    class="w-16 p-1 border rounded text-xs">
                  <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded text-xs">Offer</button>
                </form>
              <?php endif; ?>
            </td>
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