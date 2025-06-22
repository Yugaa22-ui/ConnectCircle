<?php include '../backend/auth/auth_check.php'; ?>
<?php include '../backend/friend/friend_list_process.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Teman - ConnectCircle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-people-fill me-2"></i>Daftar Teman</h4>
            <a href="../user/dashboard_user.php" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left-circle"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <?php if (count($friends) > 0): ?>
                <div class="list-group">
                    <?php foreach ($friends as $friend): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">
                                    <i class="bi bi-person-circle text-primary me-1"></i>
                                    <?= htmlspecialchars($friend['username']) ?>
                                </h6>
                                <small class="text-muted">
                                    <?= htmlspecialchars($friend['profession']) ?> dari <?= htmlspecialchars($friend['city']) ?>
                                </small>
                            </div>
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle-fill"></i> Berteman
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-circle"></i> Belum ada teman yang terhubung.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
