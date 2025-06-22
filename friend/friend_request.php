<?php
include '../backend/auth/auth_check.php';
include '../backend/friend/friend_request_process.php';

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Permintaan Pertemanan - ConnectCircle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-person-plus-fill me-1"></i> Permintaan Pertemanan</h4>
            <a href="../user/dashboard_user.php" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left-circle"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php elseif (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (count($requests) > 0): ?>
                <ul class="list-group">
                    <?php foreach ($requests as $req): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?= htmlspecialchars($req['username']) ?></strong><br>
                                <small class="text-muted">Dari: <?= htmlspecialchars($req['city']) ?> | Profesi: <?= htmlspecialchars($req['profession']) ?></small>
                            </div>
                            <form method="POST" class="d-flex gap-2 mb-0">
                                <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
                                <button type="submit" name="action" value="accept" class="btn btn-sm btn-success">
                                    <i class="bi bi-check-circle"></i> Terima
                                </button>
                                <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger">
                                    <i class="bi bi-x-circle"></i> Tolak
                                </button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="alert alert-info">Tidak ada permintaan pertemanan saat ini.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
