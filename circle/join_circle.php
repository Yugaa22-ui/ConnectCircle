<?php
include '../auth/auth_check.php';
include '../includes/db.php';

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Saat user klik tombol gabung
if (isset($_GET['join']) && is_numeric($_GET['join'])) {
    $circle_id = intval($_GET['join']);

    // Cek apakah user sudah tergabung
    $check = $conn->prepare("SELECT id FROM circle_members WHERE user_id = ? AND circle_id = ?");
    $check->bind_param("ii", $user_id, $circle_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Kamu sudah tergabung dalam circle ini.";
    } else {
        $join = $conn->prepare("INSERT INTO circle_members (user_id, circle_id) VALUES (?, ?)");
        $join->bind_param("ii", $user_id, $circle_id);
        if ($join->execute()) {
            $success = "Berhasil bergabung dengan circle!";
        } else {
            $error = "Gagal bergabung dengan circle.";
        }
        $join->close();
    }

    $check->close();
}

// Ambil daftar circle yang belum diikuti
$stmt = $conn->prepare("
    SELECT c.id, c.name, c.description 
    FROM circles c
    WHERE c.id NOT IN (
        SELECT circle_id FROM circle_members WHERE user_id = ?
    )
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gabung Circle - ConnectCircle</title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
        <?php if (!empty($error)) : ?>
            alert("<?= $error ?>");
        <?php elseif (!empty($success)) : ?>
            alert("<?= $success ?>");
        <?php endif; ?>
    </script>
</head>
<body>
    <h2>Daftar Circle yang Tersedia</h2>

    <?php if ($result->num_rows > 0): ?>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li style="margin-bottom: 15px;">
                    <strong><?= htmlspecialchars($row['name']) ?></strong><br>
                    <small><?= nl2br(htmlspecialchars($row['description'])) ?></small><br>
                    <a href="?join=<?= $row['id'] ?>" onclick="return confirm('Gabung ke circle ini?')">Gabung</a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Tidak ada circle baru yang bisa kamu ikuti saat ini.</p>
    <?php endif; ?>

    <br>
    <a href="../user/dashboard.php">Kembali ke Dashboard</a>
</body>
</html>

<?php
$stmt->close();
?>
