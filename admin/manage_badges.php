<?php
include '../backend/auth/auth_check.php';
include '../includes/db.php';

// Pastikan hanya admin
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'moderator') {
    echo "<script>alert('Akses ditolak. Halaman ini hanya untuk admin dan moderator.'); window.location='../user/dashboard.php';</script>";
    exit;
}

$success = '';
$error = '';

// Tambah badge baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['description'], $_POST['icon'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $icon = trim($_POST['icon']);

    if (empty($name)) {
        $error = "Nama badge tidak boleh kosong.";
    } else {
        $stmt = $conn->prepare("INSERT INTO badges (name, description, icon) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $description, $icon);
        if ($stmt->execute()) {
            $success = "Badge berhasil ditambahkan.";
        } else {
            $error = "Gagal menambahkan badge.";
        }
        $stmt->close();
    }
}

// Hapus badge
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $badge_id = intval($_GET['delete']);
    $del = $conn->prepare("DELETE FROM badges WHERE id = ?");
    $del->bind_param("i", $badge_id);
    if ($del->execute()) {
        $success = "Badge berhasil dihapus.";
    }
    $del->close();
}

// Ambil semua badge
$badges = $conn->query("SELECT * FROM badges ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Badge - Admin | ConnectCircle</title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
        <?php if ($success): ?>alert("<?= $success ?>");<?php endif; ?>
        <?php if ($error): ?>alert("<?= $error ?>");<?php endif; ?>
    </script>
</head>
<body>
    <h2>Kelola Badge Pengguna</h2>

    <form method="POST" action="">
        <label>Nama Badge:</label><br>
        <input type="text" name="name" required><br><br>

        <label>Deskripsi:</label><br>
        <textarea name="description" rows="3" cols="40"></textarea><br><br>

        <label>Icon (Emoji atau URL Gambar):</label><br>
        <input type="text" name="icon"><br><br>

        <button type="submit">Tambah Badge</button>
    </form>

    <hr>

    <h3>Daftar Badge</h3>
    <ul>
        <?php while ($row = $badges->fetch_assoc()): ?>
            <li>
                <?= $row['icon'] ? $row['icon'] . ' ' : '' ?>
                <strong><?= htmlspecialchars($row['name']) ?></strong><br>
                <small><?= htmlspecialchars($row['description']) ?></small><br>
                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus badge ini?')">[hapus]</a>
            </li>
            <hr>
        <?php endwhile; ?>
    </ul>

    <br>
    <a href="dashboard_admin.php">Kembali ke Dashboard Admin</a>
</body>
</html>
