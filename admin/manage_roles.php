<?php
include '../backend/auth/auth_check.php';
include '../includes/db.php';

// Pastikan hanya admin
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'moderator')  {
    echo "<script>alert('Akses hanya untuk admin dan moderator.'); window.location='../admin/dashboard_admin.php';</script>";
    exit;
}

// Update role jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['role'])) {
    $user_id = intval($_POST['user_id']);
    $new_role = $_POST['role'];

    $valid_roles = ['admin', 'user', 'moderator'];
    if (in_array($new_role, $valid_roles)) {
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $new_role, $user_id);
        if ($stmt->execute()) {
            echo "<script>alert('Role berhasil diperbarui.');</script>";
        } else {
            echo "<script>alert('Gagal memperbarui role.');</script>";
        }
        $stmt->close();
    }
}

// Ambil semua user
$users = $conn->query("SELECT id, username, email, role FROM users ORDER BY username ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Role Pengguna - ConnectCircle</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Kelola Role Pengguna</h2>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr style="background-color:#eee;">
            <th>Username</th>
            <th>Email</th>
            <th>Role Sekarang</th>
            <th>Ubah Role</th>
        </tr>

        <?php while ($user = $users->fetch_assoc()): ?>
            <tr>
                <form method="POST" action="">
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td>
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <select name="role">
                            <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="moderator" <?= $user['role'] == 'moderator' ? 'selected' : '' ?>>Moderator</option>
                        </select>
                        <button type="submit">Update</button>
                    </td>
                </form>
            </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <a href="dashboard_admin.php">Kembali ke Dashboard Admin</a>
</body>
</html>
