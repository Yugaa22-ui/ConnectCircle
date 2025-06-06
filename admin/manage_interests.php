<?php
include '../backend/auth/auth_check.php';
include '../includes/db.php';

// Cek jika bukan admin, redirect (opsional)
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'moderator') { 
    echo "<script>alert('Hanya admin dan moderator yang dapat mengakses.'); window.location='../admin/dashboard_admin.php';</script>";
    exit;
}

$success = '';
$error = '';

// Tambah minat baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_interest'])) {
    $new_interest = trim($_POST['new_interest']);

    if (empty($new_interest)) {
        $error = "Nama minat tidak boleh kosong.";
    } else {
        // Cek duplikat
        $check = $conn->prepare("SELECT id FROM interests WHERE name = ?");
        $check->bind_param("s", $new_interest);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Minat tersebut sudah ada.";
        } else {
            $insert = $conn->prepare("INSERT INTO interests (name) VALUES (?)");
            $insert->bind_param("s", $new_interest);
            if ($insert->execute()) {
                $success = "Minat berhasil ditambahkan.";
            } else {
                $error = "Gagal menambahkan minat.";
            }
            $insert->close();
        }

        $check->close();
    }
}

// Hapus minat (opsional)
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    $delete = $conn->prepare("DELETE FROM interests WHERE id = ?");
    $delete->bind_param("i", $del_id);
    $delete->execute();
    $delete->close();
    $success = "Minat berhasil dihapus.";
}

// Ambil semua data minat
$all = $conn->query("SELECT * FROM interests ORDER BY name ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Minat - ConnectCircle (Admin)</title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
        <?php if ($success): ?>alert("<?= $success ?>");<?php endif; ?>
        <?php if ($error): ?>alert("<?= $error ?>");<?php endif; ?>
    </script>
</head>
<body>
    <h2>Kelola Minat - Admin</h2>

    <form method="POST" action="">
        <label>Tambah Minat Baru:</label><br>
        <input type="text" name="new_interest" placeholder="Contoh: Musik, Menulis, Desain" required>
        <button type="submit">Tambah</button>
    </form>

    <hr>

    <h3>Daftar Minat</h3>
    <ul>
        <?php while ($row = $all->fetch_assoc()): ?>
            <li>
                <?= htmlspecialchars($row['name']) ?>
                <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin hapus?')">[hapus]</a>
            </li>
        <?php endwhile; ?>
    </ul>

    <br>
    <a href="../admin/dashboard_admin.php">Kembali ke Dashboard</a>
</body>
</html>
