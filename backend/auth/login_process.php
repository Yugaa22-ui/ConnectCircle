<?php
session_start();
include '../../includes/db.php';

// Proses hanya jika dari POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // Validasi dasar
    if (empty($email) || empty($password)) {
        header("Location: ../../auth/login.php?error=Email dan password wajib diisi!");
        exit;
    }

    // Cek akun berdasarkan email
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
            header("Location: ../../auth/login.php?error=Password salah!");
            exit;
        }
    } else {
        header("Location: ../../auth/login.php?error=Akun dengan email tersebut tidak ditemukan!");
        exit;
    }

    $stmt->close();
} else {
    header("Location: ../../auth/login.php");
    exit;
}
