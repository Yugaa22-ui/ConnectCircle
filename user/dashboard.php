<?php
include '../backend/auth/auth_check.php';// memastikan user sudah login
include '../includes/db.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - ConnectCircle</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Selamat datang, <?= htmlspecialchars($username) ?>!</h2>

    <p>Berikut beberapa menu utama:</p>
    <ul>
        <li><a href="profile.php">Lihat Profil</a></li>
        <li><a href="../circle/create_circle.php">Buat Circle</a></li>
        <li><a href="../circle/join_circle.php">Gabung Circle</a></li>
        <li><a href="../circle/view_circle.php">Lihat Circle Saya</a></li>
        <li><a href="../search/search.php">Cari Teman Berdasarkan Minat</a></li>
        <li><a href="../backend/auth/logout.php">Logout</a></li>
    </ul>
</body>
</html>
