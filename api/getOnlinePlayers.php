<?php
require '../controller/Database.php';

$database = new Database();
$conn = $database->getConnection();

$result = $conn->query("SELECT id, name FROM players WHERE online = 1");
$players = [];
while ($row = $result->fetch_assoc()) {
    $players[] = $row;
}
echo json_encode([
    'count' => count($players),
    'players' => $players
]);
