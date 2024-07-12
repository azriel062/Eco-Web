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

if (isset($data['id'])) {
    $id = $data['id'];

    // Debug output
    echo "ID: $id<br>";

    // Sanitize inputs to avoid SQL injection
    $id = $conn->real_escape_string($id);

    $sql = "DELETE FROM tb_register WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Error: Missing user ID";
}

$conn->close();
