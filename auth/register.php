<?php
include '../includes/db.php';

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $confirm    = $_POST['confirm_password'];
    $city       = trim($_POST['city']);
    $profession = trim($_POST['profession']);

    // Validasi form
    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        $error = "Semua field wajib diisi!";
    } elseif ($password !== $confirm) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        // Cek apakah email sudah terdaftar
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Email sudah terdaftar!";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (username, email, password, city, profession) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $email, $hashedPassword, $city, $profession);

            if ($stmt->execute()) {
                echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
                exit;
            } else {
                $error = "Gagal menyimpan data.";
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - ConnectCircle</title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
        function validateForm() {
            let pass = document.getElementById("password").value;
            let confirm = document.getElementById("confirm_password").value;

            if (pass !== confirm) {
                alert("Password dan konfirmasi tidak cocok!");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <h2>Registrasi Akun</h2>

    <?php if (!empty($error)): ?>
        <script>alert("<?= $error ?>");</script>
    <?php endif; ?>

    <form method="POST" action="" onsubmit="return validateForm()">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" id="password" required><br><br>

        <label>Konfirmasi Password:</label><br>
        <input type="password" name="confirm_password" id="confirm_password" required><br><br>

        <label>Kota:</label><br>
        <input type="text" name="city"><br><br>

        <label>Profesi:</label><br>
        <input type="text" name="profession"><br><br>

        <button type="submit">Daftar</button>
    </form>

    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</body>
</html>
