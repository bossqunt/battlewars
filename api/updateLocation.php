<?php
header('Content-Type: application/json');
session_start();

require_once '../controller/Database.php';
require_once '../controller/Player.php';
require_once '../controller/authCheck.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['id'], $input['x'], $input['y'], $input['area_id'])) {
        $playerId = intval($input['id']);
        $x = intval($input['x']);
        $y = intval($input['y']);
        $area_id = intval($input['area_id']);

        $database = new Database();
        $conn = $database->getConnection();
        $player = new Player($conn, $playerId);

        if ($player->updateLocation($x, $y, $area_id)) {
            $updatedPlayerDetails = $player->getDetails();
            echo json_encode($updatedPlayerDetails);
        } else {
            echo json_encode(['error' => 'Failed to update player location']);
        }
    } else {
        echo json_encode(['error' => 'Missing parameters']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
