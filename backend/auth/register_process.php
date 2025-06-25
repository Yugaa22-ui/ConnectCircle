<?php
session_start();
include '../../includes/db.php';

$errors = [];
$old = [];

// Validasi format email
function is_valid_email_format($email) {
    // Cek format email valid dan TLD minimal 2 huruf
    return preg_match('/^[A-Za-z0-9._%+-]+@(?:[A-Za-z0-9-]+\.)+[A-Za-z]{2,}$/', $email);
}

// Validasi domain email aktif
function is_valid_email_domain($email) {
    // Cek domain MX aktif
    $domain = substr(strrchr($email, "@"), 1);
    return checkdnsrr($domain, "MX");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username   = trim($_POST['username'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $password   = $_POST['password'] ?? '';
    $confirm    = $_POST['confirm_password'] ?? '';
    $city       = trim($_POST['city'] ?? '');
    $profession = trim($_POST['profession'] ?? '');
    $bio        = trim($_POST['bio'] ?? '');
    $interests  = isset($_POST['interests']) && is_array($_POST['interests']) ? $_POST['interests'] : [];

    $old = compact('username', 'email', 'city', 'profession', 'bio', 'interests');

    // Validasi form kosong
    if (empty($username)) $errors['username'] = "Username harus diisi.";
    if (empty($email))    $errors['email'] = "Email harus diisi.";
    if (empty($password)) $errors['password'] = "Password harus diisi.";
    if (empty($confirm))  $errors['confirm_password'] = "Konfirmasi password harus diisi.";

    // Validasi format email
    if (!empty($email)) {
        if (!is_valid_email_format($email)) {
            $errors['email'] = "Format email tidak valid.";
        } elseif (!is_valid_email_domain($email)) {
            $errors['email'] = "Domain email tidak aktif.";
        }
    }

    // Validasi password format
    if (!empty($password) && !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        $errors['password'] = "Password minimal 8 karakter, harus mengandung huruf besar, kecil, dan angka.";
    }

    // Validasi konfirmasi password
    if (!empty($password) && !empty($confirm) && $password !== $confirm) {
        $errors['confirm_password'] = "Konfirmasi password tidak cocok.";
    }

    // Validasi minat
    if (count($interests) < 1) {
        $errors['interests'] = "Pilih minimal 1 minat.";
    } elseif (count($interests) > 3) {
        $errors['interests'] = "Maksimal pilih 3 minat.";
    }

    // Validasi email unik (hanya jika format dan domain sudah valid)
    if (!isset($errors['email']) && !empty($email)) {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $errors['email'] = "Email sudah digunakan.";
        }
        $check->close();
    }

    // Jika ada error, redirect kembali dengan error & old input
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $old;
        header("Location: ../../auth/register.php");
        exit;
    }

    // Simpan data ke tabel users
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, city, profession, bio) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $email, $hashed_password, $city, $profession, $bio);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        // Simpan minat ke user_interests
        if (!empty($interests)) {
            $insert = $conn->prepare("INSERT INTO user_interests (user_id, interest_id) VALUES (?, ?)");
            foreach ($interests as $iid) {
                $iid = intval($iid);
                $insert->bind_param("ii", $user_id, $iid);
                $insert->execute();
            }
            $insert->close();
        }

        $stmt->close();
        unset($_SESSION['old']);
        header("Location: ../../auth/login.php?success=Pendaftaran berhasil. Silakan login.");
        exit;
    } else {
        $stmt->close();
        $_SESSION['errors']['global'] = "Gagal mendaftar. Silakan coba lagi.";
        $_SESSION['old'] = $old;
        header("Location: ../../auth/register.php");
        exit;
    }
} else {
    header("Location: ../../auth/register.php");
    exit;
}
