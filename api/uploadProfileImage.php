<?php
session_start();
require_once '../controller/authCheck.php';
require_once '../controller/Database.php';
require_once '../controller/Player.php';

header('Content-Type: application/json');

// Check if user is authenticated
if (!$playerId) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

// Check if file is uploaded
if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['status' => 'error', 'message' => 'No file uploaded']);
    exit;
}

$file = $_FILES['profile_image'];
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$maxSize = 2 * 1024 * 1024; // 2MB

// Validate file type
if (!in_array($file['type'], $allowedTypes)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid file type']);
    exit;
}

// Validate file size
if ($file['size'] > $maxSize) {
    echo json_encode(['status' => 'error', 'message' => 'File too large (max 2MB)']);
    exit;
}

// Prepare upload directory
$uploadDir = '../uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Generate filename using the original file name (basename to avoid path traversal)
$filename = basename($file['name']);
$targetPath = $uploadDir . $filename;

// Move uploaded file
if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file']);
    exit;
}

// Update player's image_path in DB
$db = new Database();
$conn = $db->getConnection();
$player = new Player($conn, $playerId);

// Save relative path for web access
$imagePath = 'uploads/' . $filename;
if ($player->updateProfileImage($imagePath)) {
    echo json_encode(['status' => 'success', 'image_path' => $imagePath]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update profile image']);
}
