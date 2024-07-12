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

if (isset($data['id']) && isset($data['username']) && isset($data['email']) && isset($data['password'])) {
    $id = $data['id'];
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];

    // Debug output
    echo "ID: $id, Username: $username, Email: $email, Password: $password<br>";

    // Sanitize inputs to avoid SQL injection
    $id = $conn->real_escape_string($id);
    $username = $conn->real_escape_string($username);
    $email = $conn->real_escape_string($email);
    $password = $conn->real_escape_string($password);

    $sql = "UPDATE tb_register SET username='$username', email='$email', password='$password' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Error: Missing required fields";
}

$conn->close();
