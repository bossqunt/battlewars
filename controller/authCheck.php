<?php
require __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$decodedUser = authenticateOrRedirect(); // defaults to redirect to index.php
$playerId = $decodedUser->id;
$isAdmin = $decodedUser->admin;

function authenticateOrRedirect($redirectPath = 'index.php') {
    $authHeader = $_COOKIE['token'] ?? null;

    if ($authHeader && preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $token = $matches[1];
        try {
            $decoded = JWT::decode($token, new Key('fuckoffdog', 'HS256'));
            return $decoded;
        } catch (Exception $e) {
            header("Location: $redirectPath");
            exit;
        }
    }

    header("Location: $redirectPath");
    exit;
}
