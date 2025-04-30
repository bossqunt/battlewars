<?php
session_start();
require_once '../controller/Market.php';
require_once '../controller/authCheck.php';

if (!isset($_POST['listing_id'], $_POST['offer_amount'])) {
  echo json_encode(['success' => false, 'message' => 'Missing data']);
  exit;
}
$playerIdToUse = isset($_POST['player_id']) ? (int)$_POST['player_id'] : $playerId;
$market = new Market();
$response = $market->makeOffer($_POST['listing_id'], $playerIdToUse, $_POST['offer_amount']);
echo json_encode($response);
