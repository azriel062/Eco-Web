<?php
session_start();
include 'database_connection.php'; // Pastikan berkas ini mengandung koneksi database yang valid

// Waktu sesi dalam detik (10 menit = 600 detik)
$inactive = 600;

// Cek apakah user sudah login dan ada waktu terakhir aktivitas
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive) {
    // Jika sudah tidak aktif, unset session dan redirect ke halaman login
    session_unset();
    session_destroy();
    header("Location: login-register.html");
    exit();
}

// Update waktu terakhir aktivitas
$_SESSION['last_activity'] = time();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Email dan password khusus untuk admin
    $admin_email = 'admin@example.com';
    $admin_password = 'admin123';

    if ($email === $admin_email && $password === $admin_password) {
        $_SESSION['user_id'] = 0; // ID khusus untuk admin, bisa disesuaikan
        $_SESSION['user'] = 'admin';
        $_SESSION['message'] = "Login berhasil sebagai admin!";
        $_SESSION['message_type'] = "success";
        header("Location: admin.html");
        exit();
    }

    if (empty($email) || empty($password)) {
        $_SESSION['message'] = "Email dan password harus diisi!";
        $_SESSION['message_type'] = "error";
        header("Location: login-register.html");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM tb_register WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verifikasi password teks biasa
            if ($password === $row['password']) {
                $_SESSION['user_id'] = $row['id']; // Menyimpan user_id ke dalam session
                $_SESSION['user'] = $row['username'];
                $_SESSION['message'] = "Login berhasil!";
                $_SESSION['message_type'] = "success";
                header("Location: index.html"); // Mengarahkan ke halaman index atau halaman lain yang sesuai
                exit();
            } else {
                $_SESSION['message'] = "Password salah!";
                $_SESSION['message_type'] = "error";
            }
        } else {
            $_SESSION['message'] = "Email tidak terdaftar!";
            $_SESSION['message_type'] = "error";
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Kesalahan koneksi database!";
        $_SESSION['message_type'] = "error";
    }
    header("Location: login-register.html");
    exit();
}
