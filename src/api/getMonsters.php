<?php
header('Content-Type: application/json');
require_once '../controller/Database.php'; // Adjust the path as needed
require_once '../controller/Monster.php'; // Adjust the path as needed
require_once '../controller/authCheck.php'; // Adjust the path as needed
session_start();

function isPlayerOnBossTile($conn, $playerId) {
    $stmt = $conn->prepare("
        select p.x player_x, p.y player_y, a.boss_x, a.boss_y from player_position p
        INNER JOIN areas a ON p.area_id = a.id
        where p.player_id = ?;
    ");
    $stmt->bind_param("i", $playerId);
    $stmt->execute();
    // Declare variables before binding
    $player_x = $player_y = $boss_x = $boss_y = null;
    $stmt->bind_result($player_x, $player_y, $boss_x, $boss_y);
    if ($stmt->fetch()) {
        return ($player_x == $boss_x && $player_y == $boss_y);
    }
    return false;
}

function write_debug_log($message) {
    $logfile = __DIR__ . '/debug.log';
    $date = date('Y-m-d H:i:s');
    file_put_contents($logfile, "[$date] $message\n", FILE_APPEND);
}

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
        // Get the player's ID from the session
        if (isset($playerId)) {
          
            // --- New logic: check if player is on boss tile ---
            if (isPlayerOnBossTile($conn, $playerId)) {
                write_debug_log("Player $playerId is on boss tile, calling getMonsterListwithBoss");
                $monsterList = $monster->getMonsterListwithBoss($playerId);
            } else {
                write_debug_log("Player $playerId is NOT on boss tile, calling getMonsterList");
                $monsterList = $monster->getMonsterList($playerId);
            }
            echo json_encode($monsterList);
        } else {
            echo json_encode(['error' => 'Player ID is required']);
        }
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
