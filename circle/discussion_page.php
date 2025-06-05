<?php
include '../auth/auth_check.php';
include '../includes/db.php';

$user_id = $_SESSION['user_id'];

// Pastikan ada ID circle
if (!isset($_GET['circle_id']) || !is_numeric($_GET['circle_id'])) {
    header("Location: view_circle.php");
    exit;
}
$circle_id = intval($_GET['circle_id']);

// Cek apakah user tergabung dalam circle
$check = $conn->prepare("SELECT id FROM circle_members WHERE user_id = ? AND circle_id = ?");
$check->bind_param("ii", $user_id, $circle_id);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    echo "<script>alert('Kamu belum tergabung dalam circle ini.'); window.location='join_circle.php';</script>";
    exit;
}
$check->close();

// Ambil info circle
$circle_info = $conn->prepare("SELECT name FROM circles WHERE id = ?");
$circle_info->bind_param("i", $circle_id);
$circle_info->execute();
$circle_info->bind_result($circle_name);
$circle_info->fetch();
$circle_info->close();

// Simpan pesan jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO posts (circle_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $circle_id, $user_id, $message);
        $stmt->execute();
        $stmt->close();
    }
}

// Ambil semua pesan dari circle
$posts = $conn->prepare("
    SELECT u.username, p.content, p.created_at 
    FROM posts p
    JOIN users u ON p.user_id = u.id
    WHERE p.circle_id = ?
    ORDER BY p.created_at ASC
");
$posts->bind_param("i", $circle_id);
$posts->execute();
$results = $posts->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Diskusi Circle - <?= htmlspecialchars($circle_name) ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Diskusi: <?= htmlspecialchars($circle_name) ?></h2>

    <div style="border:1px solid #ccc; padding:10px; max-height:300px; overflow-y:auto; margin-bottom: 20px;">
        <?php if ($results->num_rows > 0): ?>
            <?php while ($row = $results->fetch_assoc()): ?>
                <p><strong><?= htmlspecialchars($row['username']) ?></strong>: <?= nl2br(htmlspecialchars($row['content'])) ?><br>
                <small><i><?= $row['created_at'] ?></i></small></p>
                <hr>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Belum ada pesan di circle ini. Jadilah yang pertama!</p>
        <?php endif; ?>
    </div>

    <form method="POST" action="">
        <textarea name="message" rows="3" cols="60" placeholder="Tulis pesan..." required></textarea><br><br>
        <button type="submit">Kirim</button>
    </form>

    <br>
    <a href="view_circle.php">Kembali ke Daftar Circle</a>
</body>
</html>

<?php
$posts->close();
?>
