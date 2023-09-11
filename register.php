<?php
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "api-log-php");

    // Check for database connection errors
    if ($conn->connect_error) {
        die(json_encode(["error" => "Database connection failed"]));
    }

    // Retrieve user data from POST request
    $data = json_decode(file_get_contents("php://input"));
    $username = $data->username;
    $password = password_hash($data->password, PASSWORD_BCRYPT);
    $email = $data->email;

    // Insert user data into the database
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $password, $email);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Registration successful"]);
    } else {
        echo json_encode(["error" => "Registration failed"]);
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>
