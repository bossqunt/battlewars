<?php
ob_start();
require './includes/sidebar.php';
require_once './controller/Database.php';
require_once './controller/AuthCheck.php';

if ($isAdmin != 1) {
    exit;
}

$conn = new Database();

function fetchAll($conn, $query) {
    $result = $conn->getConnection()->query($query);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

$monsters = fetchAll($conn, "SELECT * FROM monsters");
$items = fetchAll($conn, "SELECT * FROM items");
$monsterItemDrops = fetchAll($conn, "SELECT * FROM monster_item_drops");
$itemModifiers = fetchAll($conn, "SELECT * FROM battlewarz.item_rarity_modifiers i
INNER JOIN item_rarities ir ON ir.id = i.rarity_id");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_monster'])) {
        $id = $_POST['monster_id'];
        $name = $_POST['monster_name'];
        $level = $_POST['monster_level'];
        $health = $_POST['monster_health'];
        $damage = $_POST['monster_damage'];
        $speed = $_POST['monster_speed'];
        $exp = $_POST['on_death_exp'];
        $gold = $_POST['on_death_gold'];

        $conn->getConnection()->query("UPDATE monsters SET name='$name', level=$level, hp=$health, attack=$damage, speed=$speed, on_death_exp=$exp, on_death_gold=$gold WHERE id=$id");
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } elseif (isset($_POST['add_item_to_drop'])) {
        $monsterId = $_POST['monster_id'];
        $itemId = $_POST['item_id'];

        $conn->getConnection()->query("INSERT INTO monster_item_drops (monster_id, item_id) VALUES ($monsterId, $itemId)");
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } elseif (isset($_POST['remove_item_from_drop'])) {
        $monsterId = $_POST['monster_id'];
        $itemId = $_POST['item_id'];

        $conn->getConnection()->query("DELETE FROM monster_item_drops WHERE monster_id=$monsterId AND item_id=$itemId");
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } elseif (isset($_POST['add_monster'])) {
        $name = $_POST['new_monster_name'];
        $conn->getConnection()->query("INSERT INTO monsters (name, level, hp, attack, speed, on_death_exp, on_death_gold) VALUES ('$name', 1, 100, 10, 5, 50, 10)");
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } elseif (isset($_POST['update_item'])) {
    $id = $_POST['item_id'];
    $name = $_POST['item_name'];
    $type = $_POST['item_type'];
    $attack = $_POST['attack'];
    $defense = $_POST['defense'];
    $critChance = $_POST['crit_chance'];
    $critMulti = $_POST['crit_multi'];
    $lifeSteal = $_POST['life_steal'];
    $armor = $_POST['armor'];
    $speed = $_POST['speed'];
    $health = $_POST['health'];
    $stamina = $_POST['stamina'];
    $quantity = $_POST['quantity'];

    $conn->getConnection()->query("UPDATE items SET 
        name='$name', 
        type='$type', 
        attack=$attack, 
        defense=$defense, 
        crit_chance=$critChance, 
        crit_multi=$critMulti, 
        life_steal=$lifeSteal, 
        armor=$armor, 
        speed=$speed, 
        health=$health, 
        stamina=$stamina, 
        quantity=$quantity 
        WHERE id=$id");
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
} elseif (isset($_POST['add_item'])) {
    $name = $_POST['item_name'];
    $type = $_POST['item_type'];
    $conn->getConnection()->query("INSERT INTO items (name, type, attack, defense, crit_chance, crit_multi, life_steal, armor, speed, health, stamina, quantity) VALUES 
        ('$name', '$type', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}
} elseif (isset($_POST['update_modifier'])) {
  $id = $_POST['modifier_id'];
  $name = $_POST['modifier_name'];
  $property = $_POST['modifier_property'];
  $minValue = $_POST['min_value'];
  $maxValue = $_POST['max_value'];
  $rarity = $_POST['rarity'];

  $conn->getConnection()->query("UPDATE item_modifiers SET 
      name='$name', 
      property='$property', 
      min_value=$minValue, 
      max_value=$maxValue, 
      rarity='$rarity' 
      WHERE id=$id");

  header("Location: " . $_SERVER['REQUEST_URI']);
  exit;
}

?>

<div class="mb-6 bg-secondary/30 p-4 rounded-lg">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-3xl font-semibold">Admin Panel</h2>
  </div>
  <div class="inline-flex rounded-md shadow-sm mb-4" role="group">
    <button onclick="switchTab('monsters')" type="button" class="tab-button px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-gray-200 rounded-s-lg">Monsters</button>
    <button onclick="switchTab('items')" type="button" class="tab-button px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200">Items</button>
    <button onclick="switchTab('item_modifiers')" type="button" class="tab-button px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-e-lg">Item Modifiers</button>
  </div>
</div>

<div id="monsters" class="tab-content">
  <div class="p-4">
    <h3 class="text-xl font-semibold mb-2">Add New Monster</h3>
    <form action="" method="POST" class="mb-6 flex items-center gap-4">
      <input type="text" name="new_monster_name" class="bg-gray-100 p-2 rounded border" placeholder="Monster Name" required>
      <button type="submit" name="add_monster" class="bg-green-500 text-white px-4 py-2 rounded">Add Monster</button>
    </form>

    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
          <th class="border-b p-2">Monster</th>
          <th class="border-b p-2">Level</th>
          <th class="border-b p-2">Health</th>
          <th class="border-b p-2">Damage</th>
          <th class="border-b p-2">Speed</th>
          <th class="border-b p-2">EXP</th>
          <th class="border-b p-2">Gold</th>
          <th class="border-b p-2">Items</th>
          <th class="border-b p-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($monsters as $monster): ?>
        <tr>
          <form action="" method="POST">
            <td class="border-b p-2">
              <input type="text" class="w-full bg-gray-100 p-1 border rounded" name="monster_name" value="<?= $monster['name'] ?>" />
              <input type="hidden" name="monster_id" value="<?= $monster['id'] ?>" />
            </td>
            <td class="border-b p-2"><input type="number" name="monster_level" class="w-full bg-gray-100 p-1 border rounded" value="<?= $monster['level'] ?>"></td>
            <td class="border-b p-2"><input type="number" name="monster_health" class="w-full bg-gray-100 p-1 border rounded" value="<?= $monster['hp'] ?>"></td>
            <td class="border-b p-2"><input type="number" name="monster_damage" class="w-full bg-gray-100 p-1 border rounded" value="<?= $monster['attack'] ?>"></td>
            <td class="border-b p-2"><input type="number" name="monster_speed" class="w-full bg-gray-100 p-1 border rounded" value="<?= $monster['speed'] ?>"></td>
            <td class="border-b p-2"><input type="number" name="on_death_exp" class="w-full bg-gray-100 p-1 border rounded" value="<?= $monster['on_death_exp'] ?>"></td>
            <td class="border-b p-2"><input type="number" name="on_death_gold" class="w-full bg-gray-100 p-1 border rounded" value="<?= $monster['on_death_gold'] ?>">
              <button type="submit" name="update_monster" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
            </td>
          </form>
          <td class="border-b p-2">
            <ul>
              <?php foreach ($monsterItemDrops as $drop): ?>
                <?php if ($drop['monster_id'] == $monster['id']): ?>
                  <?php 
                    $item = array_values(array_filter($items, fn($i) => $i['id'] == $drop['item_id']));
                    if (!empty($item)) {
                      $item = $item[0];
                      echo "<li>{$item['name']} <form action='' method='POST' style='display:inline;'><input type='hidden' name='monster_id' value='{$monster['id']}' /><input type='hidden' name='item_id' value='{$item['id']}' /><button type='submit' name='remove_item_from_drop' class='text-red-500'>Remove</button></form></li>";
                    }
                  ?>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
            <form action="" method="POST" class="mt-2">
              <select name="item_id" class="w-full bg-gray-100 p-1 border rounded">
                <?php foreach ($items as $item): ?>
                  <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                <?php endforeach; ?>
              </select>
              <input type="hidden" name="monster_id" value="<?= $monster['id'] ?>">
              <button type="submit" name="add_item_to_drop" class="bg-green-500 text-white px-4 py-2 rounded">Add Item</button>
            </form>
          </td>
          <td class="border-b p-2">
            <button class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<div id="items" class="tab-content hidden p-4">
  <h3 class="text-xl font-semibold mb-4">Add New Item</h3>
  <form method="POST" class="mb-6 flex items-center gap-4">
    <input type="text" name="item_name" class="bg-gray-100 p-2 rounded border" placeholder="Item Name" required>
    <select name="item_type" class="bg-gray-100 p-2 rounded border">
    <?php
$itemTypes = ['weapon', 'helmet', 'accessory', 'legs', 'ring', 'shield', 'boots'];
?>

  <?php foreach ($itemTypes as $type): ?>
    <option value="<?= $type ?>"><?= $type ?></option>
  <?php endforeach; ?>
</select>
    <button type="submit" name="add_item" class="bg-green-500 text-white px-4 py-2 rounded">Add Item</button>
  </form>

  <table class="w-full text-sm text-left text-gray-500">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
      <tr>
        <th class="p-2">Name</th>
        <th class="p-2">Type</th>
        <th class="p-2">Atk</th>
        <th class="p-2">Def</th>
        <th class="p-2">Crit%</th>
        <th class="p-2">Crit D</th>
        <th class="p-2">Lifesteal</th>
        <th class="p-2">Armor</th>
        <th class="p-2">Spd</th>
        <th class="p-2">HP</th>
        <th class="p-2">Stamina</th>
        <th class="p-2">Qty</th>
        <th class="p-2">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $item): ?>
        <tr>
          <form method="POST">
            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
            <td class="p-2"><input type="text" name="item_name" class="w-full bg-gray-100 p-1 border rounded" value="<?= $item['name'] ?>"></td>
            <td class="p-2"><input type="text" name="item_type" class="w-full bg-gray-100 p-1 border rounded" value="<?= $item['type'] ?>"></td>
            <td class="p-2"><input type="number" name="attack" class="w-full bg-gray-100 p-1 border rounded" value="<?= $item['attack'] ?>"></td>
            <td class="p-2"><input type="number" name="defense" class="w-full bg-gray-100 p-1 border rounded" value="<?= $item['defense'] ?>"></td>
            <td class="p-2"><input type="number" name="crit_chance" step="0.1" class="w-full bg-gray-100 p-1 border rounded" value="<?= $item['crit_chance'] ?>"></td>
            <td class="p-2"><input type="number" name="crit_multi" step="0.1" class="w-full bg-gray-100 p-1 border rounded" value="<?= $item['crit_multi'] ?>"></td>
            <td class="p-2"><input type="number" name="life_steal" step="0.1" class="w-full bg-gray-100 p-1 border rounded" value="<?= $item['life_steal'] ?>"></td>
            <td class="p-2"><input type="number" name="armor" class="w-full bg-gray-100 p-1 border rounded" value="<?= $item['armor'] ?>"></td>
            <td class="p-2"><input type="number" name="speed" class="w-full bg-gray-100 p-1 border rounded" value="<?= $item['speed'] ?>"></td>
            <td class="p-2"><input type="number" name="health" class="w-full bg-gray-100 p-1 border rounded" value="<?= $item['health'] ?>"></td>
            <td class="p-2"><input type="number" name="stamina" class="w-full bg-gray-100 p-1 border rounded" value="<?= $item['stamina'] ?>"></td>
            <td class="p-2"><input type="number" name="quantity" class="w-full bg-gray-100 p-1 border rounded" value="<?= $item['quantity'] ?>"></td>
            <td class="p-2">
              <button type="submit" name="update_item" class="bg-blue-500 text-white px-4 py-1 rounded">Save</button>
            </td>
          </form>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<div id="item_modifiers" class="tab-content hidden p-4">
  <h3 class="text-xl font-semibold mb-4">Item Modifiers</h3>
  <table class="w-full text-sm text-left text-gray-500">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
      <tr>
        <th class="border-b p-2">Rarity Id</th>
        <th class="border-b p-2">Rarity Name</th>
        <th class="border-b p-2">Stat Name</th>
        <th class="border-b p-2">Modifier Type</th>
        <th class="border-b p-2">Min Value</th>
        <th class="border-b p-2">Max Value</th>
 
        <th class="border-b p-2">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($itemModifiers as $mod): ?>
      <tr>
        <form method="POST">
          <td class="border-b p-2">
            <input type="text" name="modifier_name" value="<?= $mod['rarity_id'] ?>" class="w-full bg-gray-100 p-1 border rounded">
            <input type="hidden" name="modifier_id" value="<?= $mod['rarity_id'] ?>">
          </td>
          <td class="border-b p-2">
            <input type="text" name="name" value="<?= $mod['name'] ?>" class="w-full bg-gray-100 p-1 border rounded">
          </td>
          <td class="border-b p-2">
            <input type="text" name="stat_name" value="<?= $mod['stat_name'] ?>" class="w-full bg-gray-100 p-1 border rounded">
          </td>
          <td class="border-b p-2">
            <input type="text" name="modifier_property" value="<?= $mod['modifier_type'] ?>" class="w-full bg-gray-100 p-1 border rounded">
          </td>
          <td class="border-b p-2">
            <input type="number" name="min_value" value="<?= $mod['min_value'] ?>" class="w-full bg-gray-100 p-1 border rounded">
          </td>
          <td class="border-b p-2">
            <input type="number" name="max_value" value="<?= $mod['max_value'] ?>" class="w-full bg-gray-100 p-1 border rounded">
          </td>

          <td class="border-b p-2">
            <button type="submit" name="update_modifier" class="bg-blue-500 text-white px-3 py-1 rounded">Save</button>
          </td>
        </form>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>




<div id="loot" class="tab-content hidden p-4">
  <h3 class="text-xl font-semibold">Loot (Coming Soon)</h3>
</div>

<script>
function switchTab(tab) {
  document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
  document.getElementById(tab).classList.remove('hidden');
  document.querySelectorAll('.tab-button').forEach(btn => {
    btn.classList.remove('bg-blue-600', 'text-white');
    btn.classList.add('bg-white', 'text-gray-900');
  });
  event.target.classList.add('bg-blue-600', 'text-white');
  event.target.classList.remove('bg-white', 'text-gray-900');
}
</script>
<script type="module" src="assets/js/playerStats.js"></script>