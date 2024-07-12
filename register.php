<?php
session_start();

$cookie_lifetime = 600; // 600 detik = 10 menit
session_set_cookie_params($cookie_lifetime);

if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), $_COOKIE[session_name()], time() + $cookie_lifetime, "/");
}

include 'database_connection.php'; // Pastikan berkas ini mengandung koneksi database yang valid

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['message'] = "Semua kolom harus diisi!";
        $_SESSION['message_type'] = "error";
        header("Location: login-register.html");
        exit;
    }

    if ($password !== $confirm_password) {
        $_SESSION['message'] = "Password dan konfirmasi password tidak cocok!";
        $_SESSION['message_type'] = "error";
        header("Location: login-register.html");
        exit;
    }

    // Periksa apakah email sudah terdaftar
    $stmt = $conn->prepare("SELECT * FROM tb_register WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['message'] = "Email sudah terdaftar!";
            $_SESSION['message_type'] = "error";
            header("Location: login-register.html");
            exit;
        } else {
            // Tambahkan pengguna baru ke database
            $stmt = $conn->prepare("INSERT INTO tb_register (username, email, password, konfirmasi_password) VALUES (?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("ssss", $username, $email, $password, $confirm_password);
                if ($stmt->execute()) {
                    $_SESSION['user'] = $username;
                    $_SESSION['message'] = "Pendaftaran berhasil!";
                    $_SESSION['message_type'] = "success";
                    header("Location: session_check.php"); // Mengarahkan ke halaman PHP untuk pengecekan sesi
                    exit;
                } else {
                    $_SESSION['message'] = "Terjadi kesalahan saat mendaftar!";
                    $_SESSION['message_type'] = "error";
                }
            } else {
                $_SESSION['message'] = "Kesalahan dalam mempersiapkan pernyataan database!";
                $_SESSION['message_type'] = "error";
            }
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Kesalahan koneksi database!";
        $_SESSION['message_type'] = "error";
    }
    header("Location: login-register.html");
    exit;
} 