<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../includes/db.php';

$success = '';
$error = '';

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password     = $_POST['old_password'];
    $new_password     = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        $error = "Semua field wajib diisi.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        // Ambil password dari DB
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();

        // Verifikasi
        if (password_verify($old_password, $hashed_password)) {

            // ❗ Tambahan: Cek apakah password baru sama dengan yang lama
            if (password_verify($new_password, $hashed_password)) {
                $error = "Password baru tidak boleh sama dengan password lama.";
            } else {
                $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update->bind_param("si", $new_hashed, $user_id);
        
                if ($update->execute()) {
                    $success = "Password berhasil diperbarui.";
                } else {
                    $error = "Gagal memperbarui password.";
                }
        
                $update->close();
            }
        
        } else {
            $error = "Password lama tidak sesuai.";
        }        
    }
}
