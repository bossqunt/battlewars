<?php
require __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$decodedUser = authenticateOrRedirect(); // defaults to redirect to index.php
$playerId = $decodedUser->id;
$isAdmin = $decodedUser->admin;

function authenticateOrRedirect($redirectPath = null) {
    $authHeader = $_COOKIE['token'] ?? null;

    // Default to the index.php in the current directory if not provided
    if ($redirectPath === null) {
        $redirectPath = dirname($_SERVER['PHP_SELF']) . '/index.php';
    }

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
