<?php
include '../backend/auth/auth_check.php';
include '../backend/circle/create_circle_process.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Circle - ConnectCircle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Buat Circle Baru</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php elseif (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Nama Circle <span class="text-danger">*</span></label>
                    <input type="text" name="circle_name" class="form-control" value="<?= htmlspecialchars($circle_name) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Ceritakan tentang circle ini..."><?= htmlspecialchars($description) ?></textarea>
                </div>

                <button type="submit" class="btn btn-success">Buat Circle</button>
                <a href="../user/dashboard_user.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
