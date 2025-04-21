<?php

header('Content-Type: application/json');
require_once '../controller/authCheck.php';

if (!isset($playerId)) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$x = $data['x'];
$y = $data['y'];
$areaId = $data['area_id']; // Get the area_id from the request

// Database connection
require '../controller/Database.php'; // Adjust the path as needed

$database = new Database();
$db = $database->getConnection();

try {
    // Check if the tile is already owned
    $query = $db->prepare('SELECT * FROM area_owner WHERE x = ? AND y = ? AND area_id = ?');
    $query->execute([$x, $y, $areaId]);
    $owner = $query->fetch();

    if ($owner) {
        echo json_encode(['error' => 'Tile already owned, will implement takeover functionality later']);
        exit;
    }

    // Take ownership of the tile
    $query = $db->prepare('INSERT INTO area_owner (player_id, x, y, area_id) VALUES (?, ?, ?, ?)');
    $query->execute([$playerId, $x, $y, $areaId]);

    echo json_encode(['success' => true, 'message' => 'Tile ownership taken successfully']);
} catch (Exception $e) {
    error_log($e->getMessage()); // Log the error message
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
