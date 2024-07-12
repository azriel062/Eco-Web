<?php
// Ambil data produk dari request
$data = json_decode(file_get_contents('php://input'), true);

// Simpan data produk ke dalam database (contoh menggunakan MySQLi)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$title = $conn->real_escape_string($data['title']);
$price = $conn->real_escape_string($data['price']);
$img = $conn->real_escape_string($data['img']);
$quantity = $conn->real_escape_string($data['quantity']);

$sql = "INSERT INTO cart (title, price, img, quantity) VALUES ('$title', '$price', '$img', '$quantity')";

if ($conn->query($sql) === TRUE) {
    $response = array('success' => true);
} else {
    $response = array('success' => false, 'message' => 'Failed to add product to cart: ' . $conn->error);
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
