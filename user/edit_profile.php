<?php
include '../backend/auth/auth_check.php';
include '../backend/user/edit_profile_process.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil - ConnectCircle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3>Edit Profil</h3>
        </div>
        <div class="card-body">

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST" action="" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Username *</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($username) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kota</label>
                    <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($city) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Profesi</label>
                    <input type="text" name="profession" class="form-control" value="<?= htmlspecialchars($profession) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Bio</label>
                    <textarea name="bio" class="form-control" rows="3"><?= htmlspecialchars($bio) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto Profil (Opsional)</label><br>
                    <?php if ($profile_picture): ?>
                        <img src="../assets/uploads/img/<?= htmlspecialchars($profile_picture) ?>" class="rounded mb-2" width="100">
                    <?php endif; ?>
                    <input type="file" name="profile_picture" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="profile.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
