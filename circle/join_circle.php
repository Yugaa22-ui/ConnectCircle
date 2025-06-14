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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Gabung Circle Baru</h4>
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
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="mb-1"><?= htmlspecialchars($circle['name']) ?>
                                        <?= $circle['is_private'] ? '<span class="badge bg-secondary">Private</span>' : '<span class="badge bg-success">Public</span>' ?>
                                    </h5>
                                    <p class="mb-1"><?= nl2br(htmlspecialchars($circle['description'])) ?></p>
                                    <small class="text-muted">👥 <?= $circle['member_count'] ?> anggota</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <a href="?join=<?= $circle['id'] ?>" class="btn btn-sm <?= $circle['is_private'] ? 'btn-outline-warning' : 'btn-outline-primary' ?>"
                                       onclick="return confirm('Yakin ingin <?= $circle['is_private'] ? 'mengajukan bergabung' : 'bergabung' ?> ke circle ini?')">
                                        <?= $circle['is_private'] ? 'Ajukan Bergabung' : 'Gabung' ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Tidak ada circle yang tersedia untuk saat ini.</div>
            <?php endif; ?>
        </div>
        <div class="card-footer text-end">
            <a href="../user/dashboard_user.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>
