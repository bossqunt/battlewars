<?php
session_start();
require_once '../controller/Database.php';
require_once '../controller/Player.php';
require_once '../controller/authCheck.php';

header('Content-Type: application/json');

if (!isset($_POST['item_id'])) {
  echo json_encode(['success' => false, 'message' => 'Missing item ID']);
  exit;
}

$database = new Database();
$conn = $database->getConnection();
$player = new Player($conn, $playerId);

$result = $player->sellItem($_POST['item_id']);

echo json_encode([
  'success' => true,
  'message' => $result
]);
