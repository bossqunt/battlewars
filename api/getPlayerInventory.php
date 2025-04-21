<?php
session_start();
require_once '../controller/Database.php';
require_once '../controller/Player.php';
require_once '../controller/authCheck.php';

header('Content-Type: application/json');

$database = new Database();
$conn = $database->getConnection();

$player = new Player($conn, $playerId);

$equipped = $player->getPlayerEquippedItems();
$inventory = $player->getPlayerInventoryItems();

echo json_encode([
  'equipped' => $equipped,
  'inventory' => $inventory
]);
