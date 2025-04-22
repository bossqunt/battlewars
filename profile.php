<?php
session_start();
include 'includes/sidebar.php';
require 'controller/Player.php';
require_once 'controller/Database.php';
require_once 'controller/authCheck.php';

// Handle profile update

$database = new Database();
$conn = $database->getConnection();
$player = new Player($conn, $playerId);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!empty($_FILES['profile_image']['name'])) {
      $uploadDir = 'uploads/';
      $fileName = basename($_FILES['profile_image']['name']);
      $imagePath = $uploadDir . $fileName;

      if (!is_dir($uploadDir)) {
          mkdir($uploadDir, 0755, true);
      }

      if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $imagePath)) {
          $player->updateProfileImage($imagePath);
      }
  }

  if (!empty($_POST['new_password'])) {
      $hashedPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
      $player->updatePassword($hashedPassword);
  }

  $success = "Profile updated successfully!";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Player Profile</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 ">
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
  <h2 class="text-2xl font-bold text-center mb-4">Player Profile</h2>

  <?php if (isset($success)): ?>
    <div class="bg-green-100 text-green-800 p-2 rounded mb-4 text-center"><?= $success ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <div class="flex justify-center mb-4">
      <label class="relative cursor-pointer">
      <?php $details = $player->fetchProfileDetails(); ?>
      <img class="rounded-full h-24 w-24 object-cover" src="<?= $details['image_path'] ?? 'default.png' ?>" alt="Profile Image">
      <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 rounded-full opacity-0 hover:opacity-100 transition-opacity">
          <span class="text-xs text-white">Change</span>
        </div>
        <input type="file" name="profile_image" class="hidden" accept="image/*">
      </label>
    </div>

    <div class="text-center">
      <h3 class="text-lg font-medium"><?= htmlspecialchars($player->getName()) ?></h3>
      <p class="text-sm text-gray-500">Level <?= $player->getLevel(); ?></p>
    </div>



    <div class="mt-4">
      <label class="block text-sm font-medium mb-1" for="new_password">Change Password</label>
      <input type="password" name="new_password" id="new_password" class="w-full p-2 border rounded" placeholder="New password">
    </div>

    <button type="submit" class="mt-4 w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Save Profile</button>
  </form>
</div>

<script type="module" src="assets/js/playerStats.js"></script>