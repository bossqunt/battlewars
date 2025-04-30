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

$secretKey = 'fuckoffdog'; // Replace with a secure value in production

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["status" => "error", "message" => "Only POST method is allowed"]);
    exit;
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    $name = sanitize($_POST["name"] ?? '');
    $password = $_POST["password"] ?? '';

    if (empty($name) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Please enter both username and password"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM players WHERE name = ?");
    if (!$stmt) {
        throw new Exception("Table or query error: " . $conn->error);
    }

    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $player = $result->fetch_assoc();
        if (password_verify($password, $player['password'])) {
            // Update player status
            $updateStmt = $conn->prepare("UPDATE players SET online = 1, token_expire = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = ?");
            $updateStmt->bind_param("i", $player['id']);
            $updateStmt->execute();
            $updateStmt->close();

            $payload = [
                'id' => $player['id'],
                'name' => $player['name'],
                'admin' => $player['admin'],
                'exp' => time() + 3600
            ];

            $jwt = JWT::encode($payload, $secretKey, 'HS256');

            echo json_encode([
                "status" => "success",
                "message" => "Login successful",
                "token" => $jwt
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Incorrect password"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }

    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Server error", "details" => $e->getMessage()]);
}
