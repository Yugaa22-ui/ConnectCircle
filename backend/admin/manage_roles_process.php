<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once '../backend/auth/auth_check.php';
include_once '../includes/db.php';

// Batasi akses hanya untuk admin & moderator
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'moderator') {
    echo "<script>alert('Akses hanya untuk admin dan moderator.'); window.location='../../admin/dashboard_admin.php';</script>";
    exit;
}

$success = '';
$error = '';

// Update role pengguna
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['role'])) {
    $user_id = intval($_POST['user_id']);
    $new_role = $_POST['role'];

    $valid_roles = ['admin', 'user', 'moderator'];
    if (in_array($new_role, $valid_roles)) {
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $new_role, $user_id);
        if ($stmt->execute()) {
            $success = "Role pengguna berhasil diperbarui.";
        } else {
            $error = "Gagal memperbarui role.";
        }
        $stmt->close();
    } else {
        $error = "Role tidak valid.";
    }
}

// Ambil semua pengguna
$users = $conn->query("SELECT id, username, email, role FROM users ORDER BY username ASC");
