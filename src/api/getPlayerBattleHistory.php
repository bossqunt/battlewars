<?php
header('Content-Type: application/json');

require '../controller/Database.php'; // Adjust the path as needed
require '../controller/Player.php'; // Adjust the path as needed
require_once '../controller/authCheck.php'; // Adjust the path as needed

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id']) && $_GET['id'] == $playerId) {
        $playerId = intval($_GET['id']);

        // Create a new database connection
        $database = new Database();
        $conn = $database->getConnection();

        // Create a new Player object and get the details
        $player = new Player($conn, $playerId);
        $playerBattleHistory = $player->getBattleHistory();

        if ($playerBattleHistory) {
            echo json_encode($playerBattleHistory);
        } else {
            echo json_encode(['error' => 'There is no battle history...']);
        }
    } else {
        echo json_encode(['error' => 'You naughty mother fucker... You were trying to access someone else\'s battle history!']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
