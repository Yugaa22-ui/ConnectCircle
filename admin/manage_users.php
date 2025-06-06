<?php
include '../backend/auth/auth_check.php';
include '../includes/db.php';

// Pastikan hanya admin yang bisa akses
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'moderator') {
    echo "<script>alert('Akses hanya untuk admin dan moderator.'); window.location='../admin/dashboard_admin.php';</script>";
    exit;
}

$result = $conn->query("SELECT id, username, email, city, profession, role, created_at FROM users ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Pengguna - ConnectCircle</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Daftar Pengguna</h2>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr style="background-color:#eee;">
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Kota</th>
            <th>Profesi</th>
            <th>Role</th>
            <th>Terdaftar Sejak</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['city']) ?></td>
                    <td><?= htmlspecialchars($row['profession']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td><?= $row['created_at'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">Belum ada pengguna terdaftar.</td>
            </tr>
        <?php endif; ?>
    </table>

    <br>
    <a href="dashboard_admin.php">Kembali ke Dashboard Admin</a>
</body>
</html>
