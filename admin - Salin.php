<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$database = "ecommerce";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data dari tabel tb_register
$sql = "SELECT id, username, email, password FROM tb_register";
$result = $conn->query($sql);

$data = array(); // Array untuk menyimpan data

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row; // Tambahkan data ke array
    }
}

// Mengembalikan data dalam format JSON
header('Content-Type: application/json');
echo json_encode($data);

// Tutup koneksi database
$conn->close();
