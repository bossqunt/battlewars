<?php
require_once '../controller/Database.php'; // Adjust the path as needed
require_once '../controller/authCheck.php'; // Adjust the path as needed
class SaveHandler {
  private $conn;

  public function __construct($conn) {
    $this->conn = $conn;
  }

  // MONSTER SECTION
  public function getMonsters() {
    $result = $this->conn->query("SELECT * FROM monsters");
    return json_encode($result->fetch_all(MYSQLI_ASSOC));
  }

  public function saveMonster($data) {
    if (!isset($data['id'])) return $this->errorResponse('Missing ID');

    $stmt = $this->conn->prepare("UPDATE monsters SET name=?, level=?, hp=?, attack=?, speed=?, on_death_exp=?, on_death_gold=?, defence=? WHERE id=?");
    $stmt->bind_param("siiiiiiii",
      $data['name'], $data['level'], $data['hp'], $data['attack'],
      $data['speed'], $data['on_death_exp'], $data['on_death_gold'],
      $data['defence'], $data['id']
    );

    return $this->executeStatement($stmt);
  }

  public function addMonster($data) {
    $stmt = $this->conn->prepare("INSERT INTO monsters (name, level, hp, attack, speed, on_death_exp, on_death_gold, defence) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiiiiii",
      $data['name'], $data['level'], $data['hp'], $data['attack'],
      $data['speed'], $data['on_death_exp'], $data['on_death_gold'], $data['defence']
    );

    return $this->executeStatement($stmt);
  }

  public function deleteMonster($id) {
    $stmt = $this->conn->prepare("DELETE FROM monsters WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $this->executeStatement($stmt);
  }

  // ITEM SECTION
  public function getItems() {
    $result = $this->conn->query("SELECT * FROM items");
    return json_encode($result->fetch_all(MYSQLI_ASSOC));
  }

  public function saveItem($data) {
    if (!isset($data['id'])) return $this->errorResponse('Missing ID');

    $stmt = $this->conn->prepare("UPDATE items SET name=?, type=?, attack=?, defense=?, crit_chance=?, crit_multi=?, life_steal=?, armor=?, speed=?, health=?, stamina=?, quantity=? WHERE id=?");
    $stmt->bind_param("ssiiiiiiiiiii",
      $data['name'], $data['type'], $data['attack'], $data['defense'],
      $data['crit_chance'], $data['crit_multi'], $data['life_steal'],
      $data['armor'], $data['speed'], $data['health'],
      $data['stamina'], $data['quantity'], $data['id']
    );

    return $this->executeStatement($stmt);
  }

  public function deleteItem($id) {
    $stmt = $this->conn->prepare("DELETE FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $this->executeStatement($stmt);
  }

  public function addItem($data) {
    $stmt = $this->conn->prepare("INSERT INTO items (name, type, attack, defense, crit_chance, crit_multi, life_steal, armor, speed, health, stamina, quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiiiiiiiiii",
      $data['name'], $data['type'], $data['attack'], $data['defense'],
      $data['crit_chance'], $data['crit_multi'], $data['life_steal'],
      $data['armor'], $data['speed'], $data['health'],
      $data['stamina'], $data['quantity']
    );

    return $this->executeStatement($stmt);
  }

  // DROP SECTION
  public function getDrops() {
    $result = $this->conn->query("SELECT * FROM monster_item_drops");
    return json_encode($result->fetch_all(MYSQLI_ASSOC));
  }

  public function saveDrop($data) {
    if (!isset($data['id'])) return $this->errorResponse('Missing ID');

    $stmt = $this->conn->prepare("UPDATE monster_item_drops SET monster_id=?, item_id=?, drop_chance=?, quantity=? WHERE id=?");
    $stmt->bind_param("iiiii",
      $data['monster_id'], $data['item_id'], $data['drop_chance'],
      $data['quantity'], $data['id']
    );

    return $this->executeStatement($stmt);
  }

  public function addDrop($data) {
    $stmt = $this->conn->prepare("INSERT INTO monster_item_drops (monster_id, item_id, drop_chance, quantity) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiii", $data['monster_id'], $data['item_id'], $data['drop_chance'], $data['quantity']);
    return $this->executeStatement($stmt);
  }

  public function deleteDrop($id) {
    $stmt = $this->conn->prepare("DELETE FROM monster_item_drops WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $this->executeStatement($stmt);
  }

  private function executeStatement($stmt) {
    if ($stmt->execute()) {
      return json_encode(['success' => true]);
    } else {
      http_response_code(500);
      return json_encode(['error' => $stmt->error]);
    }
  }

  private function errorResponse($message) {
    http_response_code(400);
    return json_encode(['error' => $message]);
  }
}

// Router
$data = json_decode(file_get_contents("php://input"), true);
$type = $_GET['type'] ?? '';
$action = $_GET['action'] ?? 'get';

$handler = new SaveHandler($conn);

switch ($type) {
  case 'monster':
    switch ($action) {
      case 'get': echo $handler->getMonsters(); break;
      case 'add': echo $handler->addMonster($data); break;
      case 'save': echo $handler->saveMonster($data); break;
      case 'delete': echo $handler->deleteMonster($data['id']); break;
    }
    break;
  case 'item':
    switch ($action) {
      case 'get': echo $handler->getItems(); break;
      case 'add': echo $handler->addItem($data); break;
      case 'save': echo $handler->saveItem($data); break;
      case 'delete': echo $handler->deleteItem($data['id']); break;
    }
    break;
  case 'drop':
    switch ($action) {
      case 'get': echo $handler->getDrops(); break;
      case 'add': echo $handler->addDrop($data); break;
      case 'save': echo $handler->saveDrop($data); break;
      case 'delete': echo $handler->deleteDrop($data['id']); break;
    }
    break;
  default:
    http_response_code(400);
    echo json_encode(['error' => 'Invalid type or action']);
    break;
}