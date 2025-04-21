<?php
header('Content-Type: application/json');

require '../controller/Database.php'; // Adjust the path as needed
require '../controller/Player.php'; // Adjust the path as needed
require_once '../controller/authCheck.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read raw body
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['id'])) {
        $playerId = intval($input['id']);

        // Create a new database connection
        $database = new Database();
        $conn = $database->getConnection();

        // Create a new Player object and get the details
        $player = new Player($conn, $playerId);
        $playerDetails = $player->getDetails();

        if ($playerDetails) {
            echo json_encode($playerDetails);
        } else {
            echo json_encode(['error' => 'Player not found']);
        }
    } else {
        echo json_encode(['error' => 'Player ID is required in request body']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method. Use POST.']);
}