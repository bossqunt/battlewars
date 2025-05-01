<?php
session_start();

header('Content-Type: application/json');

require '../controller/Database.php'; // Adjust the path as needed
require '../controller/Player.php'; // Adjust the path as needed

// Function to sanitize input data
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection
    $database = new Database();
    $conn = $database->getConnection();

    // Validate and sanitize input
    $name = sanitize($_POST["name"]);
    // $class = intval($_POST["class"]); // Assuming class is an integer ID
    $password = $_POST["password"]; // Password will be hashed later

    // Validate input fields (add more as needed)
    if (empty($name) || empty($password)) {
        echo json_encode(array("status" => "error", "message" => "Invalid input data"));
        exit;
    }

    // Check if the name is already taken
    $stmt = $conn->prepare("SELECT id FROM players WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo json_encode(array("status" => "error", "message" => "This character name is already in use"));
        exit;
    }

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $gold = 250;
    $class_id = 1;
    // Insert the new player into the database
    $stmt = $conn->prepare("INSERT INTO players (name, password, gold, class_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $name,  $hashed_password, $gold, $class_id);
    
    if ($stmt->execute()) {
        // Get the ID of the new player
        $player_id = $stmt->insert_id;

        // Set session variables
        $_SESSION['id'] = $player_id;
        $_SESSION['name'] = $name;

        // Insert initial position for the player
        $area_id = 1; // Starter area ID
        $x = 0; // Example starting X coordinate
        $y = 0; // Example starting Y coordinate
  

        // Set the player's starting area and position
        $stmt = $conn->prepare("INSERT INTO player_position (area_id, x, y, player_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $area_id, $x, $y, $player_id);
        $stmt->execute();

        // Insert the starter item into the player's inventory
        $stmt2 = $conn->prepare("INSERT INTO player_inventory (player_id, item_id, rarity, equipped) VALUES (?, ?, ?, ?, ?)");
        $stmt2->bind_param("iiii", $player_id, $starter_item, $rarity, $equipped, $quantity);  
        $equipped = 1; // Item is equipped at the start
        $rarity = 1; // Common rarity item
        $starter_item = 1; // Assuming the starter item ID is 1
        $quantity = 1;
        $stmt2->execute();

        //
        $stmt3 = $conn->prepare("INSERT INTO player_area_boss (player_id, area_id, boss_defeated) VALUES (?, ?, ?)");
        $stmt3->bind_param("iii", $player_id, $area_id, $boss_defeated);
        $boss_defeated = 0; // Boss is not defeated at the start
        $stmt3->execute();

        echo json_encode(array("status" => "success", "message" => "Registration successful"));
        exit;
    } else {
        echo json_encode(array("status" => "error", "message" => "Error: " . $stmt->error));
    }

    // Close statements and database connection
    $stmt->close();
 
}
