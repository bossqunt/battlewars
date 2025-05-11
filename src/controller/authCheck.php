<?php
require __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Dynamically determine app root for redirect
//$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
$redirectPath = '/index.php';

$decodedUser = authenticateOrRedirect($redirectPath); // dynamic redirect
$playerId = $decodedUser->id;
$isAdmin = $decodedUser->admin;

// Update last_active and online status
try {
    $db = new PDO('mysql:host=localhost;dbname=bw2;charset=utf8', 'root', ''); // adjust credentials as needed
    $stmt = $db->prepare("UPDATE players SET last_active = NOW(), online = 1 WHERE id = ?");
    $stmt->execute([$playerId]);
} catch (Exception $e) {
    // Optionally log error
}

function refreshToken($refreshToken) {
    try {
        $decoded = JWT::decode($refreshToken, new Key('refresh_secret_key', 'HS256'));
        $newToken = JWT::encode([
            'id' => $decoded->id,
            'admin' => $decoded->admin,
            'exp' => time() + 3600 // 1 hour expiration
        ], 'fuckoffdog', 'HS256');

        return $newToken;
    } catch (Exception $e) {
        return null;
    }
}

function authenticateOrRedirect($redirectPath = '/index.php', $asJson = false) {
    $authHeader = $_COOKIE['token'] ?? null;
    $refreshToken = $_COOKIE['refresh_token'] ?? null;

    if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $token = $matches[1];
        try {
            $decoded = JWT::decode($token, new Key('fuckoffdog', 'HS256'));
            return $decoded;
        } catch (Exception $e) {
            if ($refreshToken) {
                $newToken = refreshToken($refreshToken);
                if ($newToken) {
                    setcookie('token', "Bearer $newToken", time() + 3600, '/');
                    return JWT::decode($newToken, new Key('fuckoffdog', 'HS256'));
                }
            }
            if ($asJson) {
                http_response_code(401);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Unauthorized: Invalid or expired token']);
                exit;
            }
            header("Location: $redirectPath");
            exit;
        }
    }

    if ($asJson) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized: No token provided']);
        exit;
    }
    header("Location: $redirectPath");
    exit;
}
