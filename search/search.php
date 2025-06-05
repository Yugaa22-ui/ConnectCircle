<?php
include '../auth/auth_check.php';
include '../includes/db.php';

$search_term = '';
$results = [];

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['minat'])) {
    $search_term = trim($_GET['minat']);

    if (!empty($search_term)) {
        $stmt = $conn->prepare("
            SELECT u.username, u.city, u.profession, i.name AS interest
            FROM users u
            JOIN user_interests ui ON u.id = ui.user_id
            JOIN interests i ON ui.interest_id = i.id
            WHERE i.name LIKE CONCAT('%', ?, '%')
            GROUP BY u.id
        ");
        $stmt->bind_param("s", $search_term);
        $stmt->execute();
        $results = $stmt->get_result();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cari Teman Berdasarkan Minat - ConnectCircle</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Cari Teman Berdasarkan Minat</h2>

    <form method="GET" action="">
        <label>Masukkan Kata Kunci Minat:</label><br>
        <input type="text" name="minat" value="<?= htmlspecialchars($search_term) ?>" placeholder="Contoh: Coding, Musik, Desain" required>
        <button type="submit">Cari</button>
    </form>

    <br>

    <?php if (isset($_GET['minat'])): ?>
        <h3>Hasil Pencarian untuk: "<?= htmlspecialchars($search_term) ?>"</h3>

        <?php if ($results && $results->num_rows > 0): ?>
            <ul>
                <?php while ($row = $results->fetch_assoc()): ?>
                    <li>
                        <strong><?= htmlspecialchars($row['username']) ?></strong><br>
                        <small><?= htmlspecialchars($row['profession']) ?> dari <?= htmlspecialchars($row['city']) ?></small><br>
                        <em>Minat: <?= htmlspecialchars($row['interest']) ?></em>
                    </li>
                    <hr>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Tidak ditemukan pengguna dengan minat tersebut.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br>
    <a href="../user/dashboard.php">Kembali ke Dashboard</a>
</body>
</html>
