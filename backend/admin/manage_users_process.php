<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once '../backend/auth/auth_check.php';
include_once '../includes/db.php';

// Pastikan hanya admin dan moderator yang bisa akses
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'moderator') {
    echo "<script>alert('Akses hanya untuk admin dan moderator.'); window.location='../../admin/dashboard_admin.php';</script>";
    exit;
}

// Ambil daftar user
$result = $conn->query("SELECT id, username, email, city, profession, role, created_at FROM users ORDER BY created_at DESC");
