<?php
include '../backend/auth/auth_check.php';
include '../backend/user/change_password_process.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ubah Password - ConnectCircle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark">
            <h4>Ubah Password</h4>
        </div>
        <div class="card-body">
            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Password Lama</label>
                    <input type="password" name="old_password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Password Baru</button>
                <a href="profile.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
