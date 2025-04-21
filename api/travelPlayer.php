<?php

session_start();

header('Content-Type: application/json');

require '../controller/Database.php'; // Adjust the path as needed
require '../controller/Player.php'; // Adjust the path as needed
require_once '../controller/authCheck.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['id'])) {
        $playerId = intval($input['id']);

        // Check if cooldown period has passed
        if (isset($_SESSION['last_travel_time']) && (time() - $_SESSION['last_travel_time']) < 10) {
            $remainingCooldown = 10 - (time() - $_SESSION['last_travel_time']);
            echo json_encode(['error' => 'Cooldown period active', 'remainingCooldown' => $remainingCooldown]);
            exit();
        }

        // Create a new database connection
        $database = new Database();
        $conn = $database->getConnection();

        // Check connection
        if ($conn->connect_error) {
            echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
            exit();
        }

        // Create a new Player object
        $player = new Player($conn, $playerId);

        // Generate random x and y coordinates (within a 9x9 grid)
        $x = rand(0, 8);
        $y = rand(0, 8);
        // this will need to be changed to the area id once I implement boss mechanics
        $area_id = 1; // Use the same area_id for now

        // Update the player's location
        $updateSuccess = $player->updateLocation($x, $y, $area_id);

        if ($updateSuccess) {
            $_SESSION['last_travel_time'] = time(); // Update last travel time
            $updatedPlayerDetails = $player->getDetails();
            if ($updatedPlayerDetails) {
                echo json_encode($updatedPlayerDetails);
            } else {
                echo json_encode(['error' => 'Failed to retrieve updated player details']);
            }
        } else {
            echo json_encode(['error' => 'Failed to update player location']);
        }
    } else {
        echo json_encode(['error' => 'Player ID is required']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

