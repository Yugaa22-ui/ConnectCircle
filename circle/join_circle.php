<?php
include '../backend/auth/auth_check.php';
include '../backend/circle/join_circle_process.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Gabung Circle - ConnectCircle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h4>Daftar Circle yang Tersedia</h4>
        </div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php elseif (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <?php if (count($available_circles) > 0): ?>
                <div class="list-group">
                    <?php foreach ($available_circles as $circle): ?>
                        <div class="list-group-item">
                            <h5><?= htmlspecialchars($circle['name']) ?></h5>
                            <p><?= nl2br(htmlspecialchars($circle['description'])) ?></p>
                            <a href="?join=<?= $circle['id'] ?>" class="btn btn-outline-primary btn-sm"
                               onclick="return confirm('Gabung ke circle ini?')">Gabung</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted">Tidak ada circle baru yang bisa kamu ikuti saat ini.</p>
            <?php endif; ?>
        </div>
        <div class="card-footer text-end">
            <a href="../user/dashboard_user.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>
