<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once '../includes/db.php';

$errors = [];
$success = '';
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    $errors['global'] = "Akses ditolak.";
    return;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $old_password     = trim($_POST['old_password'] ?? '');
    $new_password     = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Validasi kosong
    if ($old_password === '')     $errors['old_password'] = "Password lama wajib diisi.";
    if ($new_password === '')     $errors['new_password'] = "Password baru wajib diisi.";
    if ($confirm_password === '') $errors['confirm_password'] = "Konfirmasi password wajib diisi.";

    // Validasi isi hanya dilakukan jika tidak kosong
    if (empty($errors)) {
        if ($new_password !== $confirm_password) {
            $errors['confirm_password'] = "Konfirmasi password tidak cocok.";
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $new_password)) {
            $errors['new_password'] = "Minimal 8 karakter, harus ada huruf besar, kecil, dan angka.";
        } else {
            // Ambil password lama dari DB
            $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($hashed_password);
            $stmt->fetch();
            $stmt->close();

            if (!$hashed_password || !password_verify($old_password, $hashed_password)) {
                $errors['old_password'] = "Password lama tidak sesuai.";
            } elseif (password_verify($new_password, $hashed_password)) {
                $errors['new_password'] = "Password baru tidak boleh sama dengan password lama.";
            } else {
                $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update->bind_param("si", $new_hashed, $user_id);
                if ($update->execute()) {
                    $success = "Password berhasil diperbarui.";
                } else {
                    $errors['global'] = "Gagal memperbarui password.";
                }
                $update->close();
            }
        }
    }
}
