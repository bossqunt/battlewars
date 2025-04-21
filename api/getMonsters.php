<?php
header('Content-Type: application/json');
require_once '../controller/Database.php'; // Adjust the path as needed
require_once '../controller/Monster.php'; // Adjust the path as needed
require_once '../controller/authCheck.php'; // Adjust the path as needed
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Create a new database connection
    $database = new Database();
    $conn = $database->getConnection();

    // Create a new Monster object
    $monster = new Monster($conn);

    if (isset($_GET['id'])) {
        $monsterId = intval($_GET['id']);
        $monsterDetails = $monster->getMonster($monsterId);
        if ($monsterDetails) {
            echo json_encode($monsterDetails);
        } else {
            echo json_encode(['error' => 'Monster not found']);
        }
    } else {
        // this neds to be fixed to use the players session id (once login and registeration is fixed, should uncomment out this line and remove hard coded playerId)
        // Get the player's ID from the session
        // if (isset($_SESSION['id'])) {
            //$playerId = intval($_SESSION['id']);

            $monsterList = $monster->getMonsterList($playerId);
            echo json_encode($monsterList);
        // } else {
        //     echo json_encode(['error' => 'Player ID is required']);
        // }
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
