<?php include '../backend/auth/auth_check.php'; ?>
<?php include '../backend/friend/friend_list_process.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Teman - ConnectCircle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h4>Daftar Teman</h4>
        </div>
        <div class="card-body">
            <?php if (count($friends) > 0): ?>
                <div class="list-group">
                    <?php foreach ($friends as $friend): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0"><?= htmlspecialchars($friend['username']) ?></h6>
                                <small class="text-muted"><?= htmlspecialchars($friend['profession']) ?> dari <?= htmlspecialchars($friend['city']) ?></small>
                            </div>
                            <span class="badge bg-success">✅ Berteman</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">Belum ada teman yang terhubung.</div>
            <?php endif; ?>
        </div>
        <div class="card-footer text-end">
            <a href="../user/dashboard_user.php" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>

</body>
</html>
