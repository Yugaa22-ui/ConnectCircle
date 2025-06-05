<?php
include '../auth/auth_check.php';
include '../includes/db.php';
include '../badges/check_and_assign_badges.php';
assign_badges($conn, $_SESSION['user_id']);

$user_id = $_SESSION['user_id'];

$query = $conn->prepare("SELECT username, email, city, profession, bio FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$query->bind_result($username, $email, $city, $profession, $bio);
$query->fetch();
$query->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil Saya - ConnectCircle</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Profil Saya</h2>

    <table>
        <tr>
            <td><strong>Username:</strong></td>
            <td><?= htmlspecialchars($username) ?></td>
        </tr>
        <tr>
            <td><strong>Email:</strong></td>
            <td><?= htmlspecialchars($email) ?></td>
        </tr>
        <tr>
            <td><strong>Kota:</strong></td>
            <td><?= htmlspecialchars($city) ?></td>
        </tr>
        <tr>
            <td><strong>Profesi:</strong></td>
            <td><?= htmlspecialchars($profession) ?></td>
        </tr>
        <tr>
            <td><strong>Bio:</strong></td>
            <td><?= nl2br(htmlspecialchars($bio)) ?></td>
        </tr>
    </table>
    <h3>Badge yang Dimiliki</h3>

    <?php
    $stmt = $conn->prepare("
        SELECT b.name, b.description, b.icon
        FROM user_badges ub
        JOIN badges b ON ub.badge_id = b.id
        WHERE ub.user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0):
    ?>
        <ul>
            <?php while ($badge = $result->fetch_assoc()): ?>
                <li>
                    <?= $badge['icon'] ? $badge['icon'] . ' ' : '' ?>
                    <strong><?= htmlspecialchars($badge['name']) ?></strong><br>
                    <small><?= htmlspecialchars($badge['description']) ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Belum ada badge. Yuk mulai aktif di circle!</p>
    <?php endif;

    $stmt->close();
    ?>

    <br>
    <a href="edit_profile.php">Edit Profil</a> | 
    <a href="dashboard.php">Kembali ke Dashboard</a>
    <a href="change_password.php">Ubah Password</a>
</body>
</html>
