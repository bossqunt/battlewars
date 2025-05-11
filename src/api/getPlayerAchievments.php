<?php
require_once '../controller/Database.php';
require_once '../controller/Player.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$playerId = $input['player_id'] ?? null;

if (!$playerId) {
    echo json_encode(['status' => 'error', 'message' => 'Player ID is required']);
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();
    $player = new Player($conn, $playerId);

    // Fetch all achievements
    $achievementsQuery = "SELECT id, name, description, stat_key, threshold, icon_path FROM achievements";
    $stmt = $conn->prepare($achievementsQuery);
    $stmt->execute();
    $achievements = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Fetch player stats
    $playerStats = $player->getPlayerStats();

    // Calculate progress for each achievement
    foreach ($achievements as &$achievement) {
        $statKey = $achievement['stat_key'];
        $threshold = $achievement['threshold'];
        $currentValue = $playerStats[$statKey] ?? 0;

        $achievement['progress'] = min(100, round(($currentValue / $threshold) * 100));
        $achievement['current_value'] = $currentValue;
        $achievement['unlocked'] = $currentValue >= $threshold;
    }

    echo json_encode(['status' => 'success', 'achievements' => $achievements]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}