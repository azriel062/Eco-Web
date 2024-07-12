<?php
session_set_cookie_params(60); // Set waktu sesi dalam detik (10 menit = 600 detik)
session_start();

// Memperbarui waktu kadaluarsa cookie setiap aktivitas
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), $_COOKIE[session_name()], time() + 60, "/");
}

// Cek apakah pengguna sudah login
if (isset($_SESSION['user'])) {
    // Jika sudah login, arahkan ke halaman index.html
    header("Location: index.html");
    exit();
} else {
    // Jika belum login, arahkan ke halaman login-register.html
    header("Location: login-register.html");
    exit();
}
