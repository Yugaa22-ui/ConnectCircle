<?php
include '../auth/auth_check.php';
include '../includes/db.php';

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT c.id, c.name, c.description 
    FROM circle_members cm
    JOIN circles c ON cm.circle_id = c.id
    WHERE cm.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Circle Saya - ConnectCircle</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Circle yang Kamu Ikuti</h2>

    <?php if ($result->num_rows > 0): ?>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li style="margin-bottom: 15px;">
                    <strong><?= htmlspecialchars($row['name']) ?></strong><br>
                    <small><?= nl2br(htmlspecialchars($row['description'])) ?></small><br>
                    <a href="discussion_page.php?circle_id=<?= $row['id'] ?>">Masuk Diskusi</a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Kamu belum tergabung dalam circle manapun.</p>
        <a href="create_circle.php">Buat Circle Baru</a> atau 
        <a href="join_circle.php">Gabung Circle</a>
    <?php endif; ?>

    <br>
    <a href="../user/dashboard.php">Kembali ke Dashboard</a>
</body>
</html>

<?php
$stmt->close();
?>
