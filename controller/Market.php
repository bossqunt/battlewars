<?php
require_once 'Database.php';

class Market {
  private $conn;
  public function __construct() {
    $db = new Database();
    $this->conn = $db->getConnection();
  }

  // Fetch active listings from the market with optional search
  public function getListings($search = '') {
    $sql = "SELECT ml.*, i.name as item_name, CONCAT(i.attack, '/', i.defense, '/', i.speed) as stats, p.name as seller_name, pi.rarity, i.type
            FROM market_listings ml
            JOIN player_inventory pi ON ml.inventory_id = pi.id
            JOIN items i ON pi.item_id = i.id
            JOIN players p ON ml.player_id = p.id
            WHERE ml.status = 'active'";
    $params = [];
    if ($search) {
      $sql .= " AND (i.name LIKE ? )";
      $params[] = "%$search%";
    
    }
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
      // Prepare failed, return empty array or handle error as needed
      return [];
    }
    if ($params) $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
      return [];
    }
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  // List an item on the market
  public function listItem($playerInventoryId, $sellerId, $price) {
    // Check if item is already listed
    $check = $this->conn->prepare("SELECT id FROM market_listings WHERE inventory_id = ? AND status = 'active'");
    $check->bind_param("i", $playerInventoryId);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        return ['success' => false, 'message' => 'Item already listed.'];
    }
    $stmt = $this->conn->prepare("INSERT INTO market_listings (inventory_id, player_id, price, status) VALUES (?, ?, ?, 'active')");
    $stmt->bind_param("iii", $playerInventoryId, $sellerId, $price);
    $result = $stmt->execute();
    if ($result) {
        return ['success' => true, 'message' => 'Item listed successfully.'];
    } else {
        return ['success' => false, 'message' => 'Failed to list item.'];
    }
  }

  // Make an offer on a market listing
  public function makeOffer($listingId, $buyerId, $amount) {
    $stmt = $this->conn->prepare("UPDATE market_listings SET offer=?, offer_buyer_id=? WHERE id=? AND status='active'");
    $stmt->bind_param("iii", $amount, $buyerId, $listingId);
    return $stmt->execute();
  }

  // Accept an offer on a market listing
  public function acceptOffer($listingId) {
    // Mark as sold, transfer item, gold, etc. (to be implemented)
  }

  // Buy a market listing
  public function buy($listingId, $buyerId) {
    // Mark as sold, transfer item, gold, etc. (to be implemented)
  }

  // TODO: Implement acceptOffer and buy logic (transfer item, gold, update statuses)
}
