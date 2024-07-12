<?php
session_start();

if (!isset($_SESSION['user'])) {
    // Jika sesi tidak ada atau telah berakhir, arahkan ke halaman login
    header("Location: login-register.html");
    exit;
}