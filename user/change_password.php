<?php
include '../auth/auth_check.php';
include '../includes/db.php';

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password     = $_POST['old_password'];
    $new_password     = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        $error = "Semua field wajib diisi.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        // Ambil password lama dari DB
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();

        // Verifikasi password lama
        if (password_verify($old_password, $hashed_password)) {
            $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->bind_param("si", $new_hashed, $user_id);

            if ($update->execute()) {
                $success = "Password berhasil diperbarui.";
            } else {
                $error = "Gagal memperbarui password.";
            }

            $update->close();
        } else {
            $error = "Password lama tidak sesuai.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ubah Password - ConnectCircle</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Ubah Password</h2>

    <?php if ($success): ?>
        <p style="color: green"><?= $success ?></p>
    <?php elseif ($error): ?>
        <p style="color: red"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Password Lama:</label><br>
        <input type="password" name="old_password" required><br><br>

        <label>Password Baru:</label><br>
        <input type="password" name="new_password" required><br><br>

        <label>Konfirmasi Password Baru:</label><br>
        <input type="password" name="confirm_password" required><br><br>

        <button type="submit">Simpan Password Baru</button>
    </form>

    <br>
    <a href="profile.php">Kembali ke Profil</a> |
    <a href="dashboard.php">Dashboard</a>
</body>
</html>
