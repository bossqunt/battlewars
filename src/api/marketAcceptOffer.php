<?php
session_start();
require_once '../controller/Market.php';
require_once '../controller/authCheck.php';

if (!isset($_POST['listing_id']) || !isset($_POST['offer_id'])) {
  echo json_encode(['success' => false, 'message' => 'Missing listing id or offer id']);
  exit;
}
$market = new Market();
$result = $market->acceptOffer($_POST['listing_id'], $_POST['offer_id']);
echo json_encode($result);
