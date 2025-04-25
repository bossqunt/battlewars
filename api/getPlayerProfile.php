<?php
header('Content-Type: application/json'); // Set the response type to JSON

require '../controller/Database.php'; // Include the database connection class
require '../controller/Player.php';   // Include the Player class
require_once '../controller/authCheck.php'; // Include authentication check

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read the raw POST body and decode it from JSON to an associative array
    $input = json_decode(file_get_contents('php://input'), true);

        // Create a new database connection
        $database = new Database();
        $conn = $database->getConnection();

        // Create a new Player object with the connection and player ID
        $player = new Player($conn, $playerId);

        // Fetch player details using the Player object
        $playerDetails = $player->getProfile();

        // If player details are found, return them as JSON
        if ($playerDetails) {
            echo json_encode($playerDetails);
        } else {
            // If not found, return an error message
            echo json_encode(['error' => 'Player not found']);
        }
    } 
