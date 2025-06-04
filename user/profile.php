<?php
include '../auth/auth_check.php';
include '../includes/db.php';

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

    <br>
    <a href="edit_profile.php">Edit Profil</a> | 
    <a href="dashboard.php">Kembali ke Dashboard</a>
    <a href="change_password.php">Ubah Password</a>
</body>
</html>
