<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Redirect relatif terhadap posisi file
    $redirect_path = (isset($_SERVER['REQUEST_URI']) && str_contains($_SERVER['REQUEST_URI'], '/admin/')) ? '../../auth/login.php' : '../auth/login.php';
    header("Location: $redirect_path");
    exit;
}
