<?php
session_start();
include '../../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['login_email'] ?? '');
    $password = $_POST['login_password'] ?? '';

    $errors = [];

    if (empty($email)) {
        $errors['email'] = "Email harus diisi.";
    }
    if (empty($password)) {
        $errors['password'] = "Password harus diisi.";
    }

    if (!empty($errors)) {
        $_SESSION['login_errors'] = $errors;
        $_SESSION['old_email'] = $email; // supaya email tetap terisi di form
        header("Location: ../../auth/login.php");
        exit;
    }

    // Cek akun
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $username, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Simpan data ke session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            // Redirect berdasarkan role 
            if ($role === 'admin' || $role === 'moderator') {
                header("Location: ../../admin/dashboard_admin.php");
            } else {
                header("Location: ../../user/dashboard_user.php");
            }
            exit;
        } else {
            $_SESSION['login_errors'] = ['password' => 'Password salah.'];
            $_SESSION['old_email'] = $email;
            header("Location: ../../auth/login.php");
            exit;
        }
    } else {
        $_SESSION['login_errors'] = ['email' => 'Email tidak ditemukan.'];
        $_SESSION['old_email'] = $email;
        header("Location: ../../auth/login.php");
        exit;
    }

    $stmt->close();
} else {
    header("Location: ../../auth/login.php");
    exit;
}
