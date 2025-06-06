<?php
include '../backend/auth/auth_check.php';

// Izinkan hanya admin dan moderator
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'moderator') {
    echo "<script>alert('Akses ditolak. Halaman ini hanya untuk admin atau moderator.'); window.location='../user/dashboard.php';</script>";
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin - ConnectCircle</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Selamat datang Admin, <?= htmlspecialchars($username) ?>!</h2>

    <p>Silakan pilih menu administrasi:</p>
    <ul>
        <li><a href="manage_interests.php">Kelola Minat</a></li>
        <li><a href="manage_users.php">Kelola Pengguna</a></li>
        <li><a href="manage_roles.php">Kelola Role Pengguna</a></li>
        <li><a href="../backend/auth/logout.php">Logout</a></li>
    </ul>
</body>
</html>
