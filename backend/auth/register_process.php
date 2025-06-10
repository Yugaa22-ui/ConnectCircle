<?php
session_start();
include '../../includes/db.php';

$errors = [];
$old = [];

// Fungsi validasi email sesuai standar + MX record
function is_valid_email($email) {
    // Validasi struktur
    if (!preg_match('/^(?!.*@.*@)[A-Za-z0-9._%+-]+@(?:[A-Za-z0-9-]+\.)+[A-Za-z]{2,}$/', $email)) {
        return false;
    }

    // Validasi domain
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
    $interests  = $_POST['interests'] ?? [];

    $old = compact('username', 'email', 'city', 'profession', 'bio', 'interests');

    // Validasi kosong
    if (empty($username)) $errors['username'] = "Username harus diisi.";
    if (empty($email)) $errors['email'] = "Email harus diisi.";
    if (empty($password)) $errors['password'] = "Password harus diisi.";
    if (empty($confirm)) $errors['confirm_password'] = "Konfirmasi password harus diisi.";

    // Validasi email
    if (!empty($email) && !is_valid_email($email)) {
        $errors['email'] = "Format email tidak valid atau domain tidak aktif.";
    }

    // Validasi password
    if (!empty($password) && !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        $errors['password'] = "Password minimal 8 karakter, harus mengandung huruf besar, kecil, dan angka.";
    }

    // Validasi konfirmasi password
    if ($password !== $confirm) {
        $errors['confirm_password'] = "Konfirmasi password tidak cocok.";
    }

    // Cek email unik
    if (!isset($errors['email'])) {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $errors['email'] = "Email sudah digunakan.";
        }
        $check->close();
    }

    // Jika ada error
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $old;
        header("Location: ../../auth/register.php");
        exit;
    }

    // Simpan user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, city, profession, bio) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $email, $hashed_password, $city, $profession, $bio);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        // Simpan minat
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
