<?php
session_start();
include '../../includes/db.php';

// Proses hanya jika dari POST form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $password   = trim($_POST['password']);
    $city       = trim($_POST['city']);
    $profession = trim($_POST['profession']);
    $bio        = trim($_POST['bio']);

    // Validasi dasar
    if (empty($username) || empty($email) || empty($password)) {
        header("Location: ../../auth/register.php?error=Harap lengkapi semua field wajib.");
        exit;
    }

    // Cek duplikat email
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $check->close();
        header("Location: ../../auth/register.php?error=Email sudah digunakan.");
        exit;
    }
    $check->close();

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Masukkan data ke DB
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, city, profession, bio) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $email, $hashed_password, $city, $profession, $bio);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: ../../auth/login.php?success=Pendaftaran berhasil. Silakan login.");
        exit;
    } else {
        $stmt->close();
        header("Location: ../../auth/register.php?error=Gagal mendaftar. Silakan coba lagi.");
        exit;
    }
} else {
    header("Location: ../../auth/register.php");
    exit;
}
