<?php
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

header('Content-Type: application/json');
require '../controller/Database.php';
require '../controller/Player.php';

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Your secret key for signing JWTs
$secretKey = 'fuckoffdog'; // Keep this safe and consistent

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $conn = $database->getConnection();

    $name = sanitize($_POST["name"]);
    $password = $_POST["password"];

    if (empty($name) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Please enter both username and password"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM players WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $player = $result->fetch_assoc();
        if (password_verify($password, $player['password'])) {
            // Set player as online and update last_active
            $updateStmt = $conn->prepare("UPDATE players SET online = 1, token_expire = DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 3600 SECOND) WHERE id = ?");
            $updateStmt->bind_param("i", $player['id']);
            $updateStmt->execute();
            $updateStmt->close();

            // Create the JWT payload
            $payload = [
                'id' => $player['id'],
                'name' => $player['name'],
                'admin' => $player['admin'],
                'exp' => time() + 3600 // Token valid for 1 hour
            ];
            

            $jwt = JWT::encode($payload, $secretKey, 'HS256');

            echo json_encode([
                "status" => "success",
                "message" => "Login successful",
                "token" => $jwt
            ]);
            exit;
        } else {
            echo json_encode(["status" => "error", "message" => "Incorrect password"]);
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
        exit;
    }

}
