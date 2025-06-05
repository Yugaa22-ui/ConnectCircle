<?php
include '../auth/auth_check.php';
include '../includes/db.php';

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Handle Form Submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $circle_name = trim($_POST['circle_name']);
    $description = trim($_POST['description']);

    if (empty($circle_name)) {
        $error = "Tolong isi nama circle terlebih dahulu.";
    } elseif (strlen($circle_name) < 3) {
        $error = "Nama circle minimal 3 karakter.";
    } else {
        // Simpan circle ke DB
        $stmt = $conn->prepare("INSERT INTO circles (name, description, creator_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $circle_name, $description, $user_id);

        if ($stmt->execute()) {
            $circle_id = $stmt->insert_id;

            // Tambahkan pembuat sebagai anggota circle
            $member_stmt = $conn->prepare("INSERT INTO circle_members (user_id, circle_id) VALUES (?, ?)");
            $member_stmt->bind_param("ii", $user_id, $circle_id);
            $member_stmt->execute();
            $member_stmt->close();

            $success = "Circle berhasil dibuat!";
        } else {
            $error = "Terjadi kesalahan saat membuat circle.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buat Circle - ConnectCircle</title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
        // Notifikasi menggunakan alert popup
        <?php if (!empty($error)) : ?>
            alert("<?= $error ?>");
        <?php elseif (!empty($success)) : ?>
            alert("<?= $success ?>");
        <?php endif; ?>
    </script>
</head>
<body>
    <h2>Buat Circle Baru</h2>

    <form method="POST" action="">
        <label>Nama Circle:<span style="color:red">*</span></label><br>
        <input type="text" name="circle_name" value="<?= isset($circle_name) ? htmlspecialchars($circle_name) : '' ?>" required><br><br>

        <label>Deskripsi:</label><br>
        <textarea name="description" rows="4" cols="40" placeholder="Ceritakan tentang circle ini..."><?= isset($description) ? htmlspecialchars($description) : '' ?></textarea><br><br>

        <button type="submit">Buat Circle</button>
    </form>

    <br>
    <a href="../user/dashboard.php">Kembali ke Dashboard</a>
</body>
</html>
