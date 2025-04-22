<?php
session_start();
require_once '../controller/Market.php';
require_once '../controller/authCheck.php';

if (!isset($_POST['listing_id'], $_POST['offer_amount'])) {
  echo json_encode(['success' => false, 'message' => 'Missing data']);
  exit;
}
$market = new Market();
$success = $market->makeOffer($_POST['listing_id'], $playerId, $_POST['offer_amount']);
echo json_encode(['success' => $success]);
