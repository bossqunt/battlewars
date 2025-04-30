<?php
session_start();
require_once '../controller/Market.php';
require_once '../controller/authCheck.php';

if (!isset($_POST['listing_id'])) {
  echo json_encode(['success' => false, 'message' => 'Missing listing id']);
  exit;
}
$market = new Market();
$result = $market->buyListing($_POST['listing_id'], $playerId);
echo json_encode($result);
