<?php
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "api-log-php");

    // Check for database connection errors
    if ($conn->connect_error) {
        die(json_encode(["error" => "Database connection failed"]));
    }

    // dd($conn);
    // var_dump($conn);

    // Retrieve user data from POST request
    $data = json_decode(file_get_contents("php://input"));
    $username = $data->username;
    $password = $data->password;

    // Retrieve user data from the database
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            echo json_encode(["message" => "Login successful"]);
        } else {
            echo json_encode(["error" => "Invalid password"]);
        }
    } else {
        echo json_encode(["error" => "User not found"]);
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>
