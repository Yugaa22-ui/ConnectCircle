<?php
// Koneksi database
require_once __DIR__ . '/../../includes/db.php';

// Hitung total pengguna
$result = $conn->query("SELECT COUNT(*) FROM users");
$totalUsers = ($result) ? (int)$result->fetch_row()[0] : 0;

// Hitung total minat
$result = $conn->query("SELECT COUNT(*) FROM interests");
$totalInterests = ($result) ? (int)$result->fetch_row()[0] : 0;

// Hitung total circle
$result = $conn->query("SELECT COUNT(*) FROM circles");
$totalCircles = ($result) ? (int)$result->fetch_row()[0] : 0;

// Hitung total badges
$result = $conn->query("SELECT COUNT(*) FROM badges");
$totalBadges = ($result) ? (int)$result->fetch_row()[0] : 0;
?>
