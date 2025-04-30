<?php
session_start();
require_once '../controller/Database.php';
require_once '../controller/Player.php';
require_once '../controller/authCheck.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $database = new Database();
    $conn = $database->getConnection();

    $stmt = $conn->prepare("SELECT id, player_id, event, timestamp FROM world_events ORDER BY timestamp DESC");
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $events = [];

        while ($row = $result->fetch_assoc()) {
            $events[] = [
                "id" => $row['id'],
                "player_id" => $row['player_id'],
                "event" => $row['event'],
                "timestamp" => $row['timestamp']
            ];
        }

        echo json_encode(["events" => $events]);
    } else {
        echo json_encode(["error" => "Failed to fetch world events"]);
    }

    $stmt->close();
    exit;
} else {
    echo json_encode(["error" => "Invalid request method. Use POST."]);
    http_response_code(405);
    exit;
}
