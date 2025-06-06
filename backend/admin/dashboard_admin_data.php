<?php
include_once '../backend/auth/auth_check.php'; // ini sudah termasuk session_start()

// Izinkan hanya admin dan moderator
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'moderator') {
    echo "<script>alert('Akses ditolak. Halaman ini hanya untuk admin atau moderator.'); window.location='../../user/dashboard.php';</script>";
    exit;
}

$username = $_SESSION['username'];
