<?php
require '../controller/Database.php';

header('Content-Type: application/json');

$database = new Database();
$conn = $database->getConnection();

$result = $conn->query("SELECT id, (CASE WHEN admin = 1 then CONCAT('[GM] ', name) else name END) name, level FROM players WHERE online = 1");

$players = [];
while ($row = $result->fetch_assoc()) {
    $players[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'level' => $row['level']
    ];
}

echo json_encode([
    'count' => count($players),
    'players' => $players
]);
