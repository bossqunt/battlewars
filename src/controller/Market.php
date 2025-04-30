<?php
require_once 'Database.php';

class Market {
  private $conn;
  public function __construct() {
    $db = new Database();
    $this->conn = $db->getConnection();
  }

  // Fetch active listings from the market with optional search, owner, and status filter
  public function getListings($search = '', $ownerId = null, $status = 'active') {
    $sql = "SELECT ml.id listing_id, ml.player_id, ml.inventory_id, mo.highest_offer, mo.highest_offer_id, ml.price, ml.status, ml.created_at, i.name as item_name, pi.attack + i.attack as attack, pi.defence + i.defence as defence, pi.speed + i.speed as speed, pi.crit_multi + i.crit_multi as crit_multi, pi.crit_chance + i.crit_chance as crit_chance, pi.life_steal + i.life_steal as life_steal, pi.health + i.health as health, pi.stamina + i.stamina as stamina, p.name as seller_name, p.image_path seller_image_path, pi.rarity, i.type
            FROM market_listings ml
            LEFT JOIN (
              SELECT listing_id, MAX(offer_amount) AS highest_offer, 
                     SUBSTRING_INDEX(GROUP_CONCAT(id ORDER BY offer_amount DESC), ',', 1) AS highest_offer_id
              FROM market_offers 
              GROUP BY listing_id
            ) mo ON ml.id = mo.listing_id
            JOIN player_inventory pi ON ml.inventory_id = pi.id
            JOIN items i ON pi.item_id = i.id
            JOIN players p ON ml.player_id = p.id
            WHERE 1=1";
    $params = [];
    $types = '';

    if ($status === 'active' || $status === 'sold' || $status === 'removed') {
      $sql .= " AND ml.status = ?";
      $params[] = $status;
      $types .= 's';
    }
    if ($ownerId) {
      $sql .= " AND ml.player_id = ?";
      $params[] = $ownerId;
      $types .= 'i';
    }
    if ($search) {
      $sql .= " AND (i.name LIKE ? )";
      $params[] = "%$search%";
      $types .= 's';
    }
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
      return [];
    }
    if ($params) $stmt->bind_param($types, ...$params);
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
    // Check if offer already exists for this buyer and listing
    $check = $this->conn->prepare("SELECT id FROM market_offers WHERE listing_id=? AND offer_player_id=? AND status='active'");
    $check->bind_param("ii", $listingId, $buyerId);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
      // Update existing offer
      $stmt = $this->conn->prepare("UPDATE market_offers SET offer_amount=? WHERE listing_id=? AND offer_player_id=? AND status='active'");
      $stmt->bind_param("iii", $amount, $listingId, $buyerId);
      $result = $stmt->execute();
      if ($result) {
        return ['success' => true, 'message' => 'Offer updated successfully.'];
      } else {
        return ['success' => false, 'message' => 'Failed to update offer.'];
      }
    } else {
      // Insert new offer
      $stmt = $this->conn->prepare("INSERT INTO market_offers (listing_id, offer_player_id, offer_amount, status) VALUES (?, ?, ?, 'active')");
      $stmt->bind_param("iii", $listingId, $buyerId, $amount);
      $result = $stmt->execute();
      if ($result) {
        return ['success' => true, 'message' => 'Offer placed successfully.'];
      } else {
        return ['success' => false, 'message' => 'Failed to place offer.'];
      }
    }
  }

  // Accept an offer on a market listing
  public function acceptOffer($listingId, $offerId) {
    // Start transaction
    $this->conn->begin_transaction();
    try {
      // Get offer details
      $offerStmt = $this->conn->prepare("SELECT offer_player_id, offer_amount FROM market_offers WHERE id=? AND listing_id=? AND status='active'");
      $offerStmt->bind_param("ii", $offerId, $listingId);
      $offerStmt->execute();
      $offerResult = $offerStmt->get_result();
      if ($offerResult->num_rows === 0) {
        $this->conn->rollback();
        return ['success' => false, 'message' => 'Offer not found.'];
      }
      $offer = $offerResult->fetch_assoc();
      $buyerId = $offer['offer_player_id'];
      $amount = $offer['offer_amount'];

      // Get listing details
      $listingStmt = $this->conn->prepare("SELECT inventory_id, player_id FROM market_listings WHERE id=? AND status='active'");
      $listingStmt->bind_param("i", $listingId);
      $listingStmt->execute();
      $listingResult = $listingStmt->get_result();
      if ($listingResult->num_rows === 0) {
        $this->conn->rollback();
        return ['success' => false, 'message' => 'Listing not found.'];
      }
      $listing = $listingResult->fetch_assoc();
      $inventoryId = $listing['inventory_id'];
      $sellerId = $listing['player_id'];

      // Transfer gold: subtract from buyer, add to seller
      $updateBuyer = $this->conn->prepare("UPDATE players SET gold = gold - ? WHERE id=? AND gold >= ?");
      $updateBuyer->bind_param("iii", $amount, $buyerId, $amount);
      $updateBuyer->execute();
      if ($updateBuyer->affected_rows === 0) {
        $this->conn->rollback();
        return ['success' => false, 'message' => 'Buyer does not have enough gold.'];
      }
      $updateSeller = $this->conn->prepare("UPDATE players SET gold = gold + ? WHERE id=?");
      $updateSeller->bind_param("ii", $amount, $sellerId);
      $updateSeller->execute();

      // Transfer item: set player_inventory.player_id to buyer and unequip
      $updateItem = $this->conn->prepare("UPDATE player_inventory SET player_id=?, equipped=0 WHERE id=?");
      $updateItem->bind_param("ii", $buyerId, $inventoryId);
      $updateItem->execute();

      // Mark listing as sold
      $updateListing = $this->conn->prepare("UPDATE market_listings SET status='sold' WHERE id=?");
      $updateListing->bind_param("i", $listingId);
      $updateListing->execute();

      // Mark accepted offer as accepted
      $acceptOffer = $this->conn->prepare("UPDATE market_offers SET status='accepted' WHERE id=?");
      $acceptOffer->bind_param("i", $offerId);
      $acceptOffer->execute();

      // Remove all other offers for this listing
      $removeOffers = $this->conn->prepare("UPDATE market_offers SET status='removed' WHERE listing_id=? AND id<>?");
      $removeOffers->bind_param("ii", $listingId, $offerId);
      $removeOffers->execute();

      $this->conn->commit();
      return ['success' => true, 'message' => 'Offer accepted, item and gold transferred.'];
    } catch (\Exception $e) {
      $this->conn->rollback();
      return ['success' => false, 'message' => 'Transaction failed: ' . $e->getMessage()];
    }
  }

  // Buy a market listing
  public function buyListing($listingId, $buyerId) {
    // Start transaction
    $this->conn->begin_transaction();
    try {
      // Get listing details
      $listingStmt = $this->conn->prepare("SELECT inventory_id, player_id, price FROM market_listings WHERE id=? AND status='active'");
      $listingStmt->bind_param("i", $listingId);
      $listingStmt->execute();
      $listingResult = $listingStmt->get_result();
      if ($listingResult->num_rows === 0) {
        $this->conn->rollback();
        return ['success' => false, 'message' => 'Listing not found.'];
      }
      $listing = $listingResult->fetch_assoc();
      $inventoryId = $listing['inventory_id'];
      $sellerId = $listing['player_id'];
      $price = $listing['price'];

      // Transfer gold: subtract from buyer, add to seller
      $updateBuyer = $this->conn->prepare("UPDATE players SET gold = gold - ? WHERE id=? AND gold >= ?");
      $updateBuyer->bind_param("iii", $price, $buyerId, $price);
      $updateBuyer->execute();
      if ($updateBuyer->affected_rows === 0) {
        $this->conn->rollback();
        return ['success' => false, 'message' => 'Buyer does not have enough gold.'];
      }
      $updateSeller = $this->conn->prepare("UPDATE players SET gold = gold + ? WHERE id=?");
      $updateSeller->bind_param("ii", $price, $sellerId);
      $updateSeller->execute();

      // Transfer item: set player_inventory.player_id to buyer
      $updateItem = $this->conn->prepare("UPDATE player_inventory SET player_id=? WHERE id=?");
      $updateItem->bind_param("ii", $buyerId, $inventoryId);
      $updateItem->execute();

      // Mark listing as sold
      $updateListing = $this->conn->prepare("UPDATE market_listings SET status='sold' WHERE id=?");
      $updateListing->bind_param("i", $listingId);
      $updateListing->execute();

      // Remove all offers for this listing
      $removeOffers = $this->conn->prepare("UPDATE market_offers SET status='removed' WHERE listing_id=?");
      $removeOffers->bind_param("i", $listingId);
      $removeOffers->execute();

      $this->conn->commit();
      return ['success' => true, 'message' => 'Listing bought, item and gold transferred.'];
    } catch (\Exception $e) {
      $this->conn->rollback();
      return ['success' => false, 'message' => 'Transaction failed: ' . $e->getMessage()];
    }
  }

  // Remove (deactivate) a listing
  public function removeListing($listingId, $playerId) {
    $stmt = $this->conn->prepare("UPDATE market_listings SET status='removed' WHERE id=? AND player_id=? AND status='active'");
    $stmt->bind_param("ii", $listingId, $playerId);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
      return ['success' => true, 'message' => 'Listing removed.'];
    }
    return ['success' => false, 'message' => 'Failed to remove listing.'];
  }
}
