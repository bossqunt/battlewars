<?php
session_start();
require_once '../controller/Market.php';
require_once '../controller/authCheck.php';

if (!isset($_POST['player_inventory_id'], $_POST['price'])) {
  echo json_encode(['success' => false, 'message' => 'Missing data']);
  exit;
}
$market = new Market();
$result = $market->listItem($_POST['player_inventory_id'], $playerId, $_POST['price']);
echo json_encode([$result]);
