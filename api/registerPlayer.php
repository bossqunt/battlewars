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
    $gold = 150;
    // Insert the new player into the database
    $stmt = $conn->prepare("INSERT INTO players (name, password, gold) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $name,  $hashed_password, $gold);
    
    if ($stmt->execute()) {
        // Get the ID of the new player
        $player_id = $stmt->insert_id;

        // Set session variables
        $_SESSION['id'] = $player_id;
        $_SESSION['name'] = $name;

        // Insert initial position for the player
        $area_id = 1; // Example area ID
        $x = 0; // Example starting X coordinate
        $y = 0; // Example starting Y coordinate
        $starter_item = 1;
        $rarity = 1;
        $equipped = 1;

        $stmt = $conn->prepare("INSERT INTO player_position (area_id, x, y, player_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $area_id, $x, $y, $player_id);
        $stmt->execute();

        $stmt2 = $conn->prepare("INSERT INTO player_inventory (player_id, item_id, rarity, equipped) VALUES (?, ?, ?, ?)");
        $stmt2->bind_param("iiii", $player_id, $starter_item, $rarity, $equipped);  
        $stmt2->execute();

        echo json_encode(array("status" => "success", "message" => "Registration successful"));
        exit;
    } else {
        echo json_encode(array("status" => "error", "message" => "Error: " . $stmt->error));
    }

    // Close statements and database connection
    $stmt->close();
 
}
