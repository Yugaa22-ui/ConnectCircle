<?php
include '../auth/auth_check.php';
include '../includes/db.php';

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Ambil data user sekarang
$query = $conn->prepare("SELECT username, city, profession, bio FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$query->bind_result($username, $city, $profession, $bio);
$query->fetch();
$query->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username   = trim($_POST['username']);
    $new_city       = trim($_POST['city']);
    $new_profession = trim($_POST['profession']);
    $new_bio        = trim($_POST['bio']);

    if (empty($new_username)) {
        $error = "Username tidak boleh kosong.";
    } else {
        $stmt = $conn->prepare("UPDATE users SET username = ?, city = ?, profession = ?, bio = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $new_username, $new_city, $new_profession, $new_bio, $user_id);

        if ($stmt->execute()) {
            $success = "Profil berhasil diperbarui.";
            $username = $new_username;
            $city = $new_city;
            $profession = $new_profession;
            $bio = $new_bio;
        } else {
            $error = "Gagal memperbarui profil.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profil - ConnectCircle</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Edit Profil Saya</h2>

    <?php if ($success): ?>
        <p style="color: green"><?= $success ?></p>
    <?php elseif ($error): ?>
        <p style="color: red"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Username:</label><br>
        <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" required><br><br>

        <label>Kota:</label><br>
        <input type="text" name="city" value="<?= htmlspecialchars($city) ?>"><br><br>

        <label>Profesi:</label><br>
        <input type="text" name="profession" value="<?= htmlspecialchars($profession) ?>"><br><br>

        <label>Bio:</label><br>
        <textarea name="bio" rows="4" cols="40"><?= htmlspecialchars($bio) ?></textarea><br><br>

        <button type="submit">Simpan Perubahan</button>
    </form>

    <br>
    <a href="profile.php">Kembali ke Profil</a> | 
    <a href="dashboard.php">Dashboard</a>
</body>
</html>
