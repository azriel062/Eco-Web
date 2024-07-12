<?php
include 'database_connection.php';

// Terima data JSON dari fetch
$data = json_decode(file_get_contents("php://input"), true);

// Debugging output
if ($data === null) {
    echo "Error: No data received";
    exit;
} else {
    echo "Data received: " . json_encode($data) . "<br>";
}

if (isset($data['username']) && isset($data['email']) && isset($data['password'])) {
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];

    // Debug output
    echo "Username: $username, Email: $email, Password: $password<br>";

    // Sanitize inputs to avoid SQL injection
    $username = $conn->real_escape_string($username);
    $email = $conn->real_escape_string($email);
    $password = $conn->real_escape_string($password);

    $sql = "INSERT INTO tb_register (username, email, password) VALUES ('$username', '$email', '$password')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Error: Missing required fields";
}

$conn->close();
