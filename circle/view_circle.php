<?php
include '../backend/auth/auth_check.php';
include '../backend/circle/view_circle_data.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Circle Saya - ConnectCircle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-info"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Circle yang Kamu Ikuti</h4>
            <form method="GET" class="d-flex" action="">
                <input type="text" name="search" class="form-control form-control-sm me-2"
                       placeholder="Cari nama circle" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button class="btn btn-light btn-sm" type="submit">🔍 Cari</button>
            </form>
        </div>
        <div class="card-body">

            <?php if (count($circles) > 0): ?>
                <div class="list-group mb-4">
                    <?php foreach ($circles as $circle): ?>
                        <div class="list-group-item">
                            <h5 class="mb-1"><?= htmlspecialchars($circle['name']) ?></h5>
                            <p class="mb-1"><?= nl2br(htmlspecialchars($circle['description'])) ?></p>
                            <small class="text-muted">👥 <?= $circle['member_count'] ?> anggota</small><br>
                            <a href="discussion_page.php?circle_id=<?= $circle['id'] ?>" class="btn btn-outline-success btn-sm mt-2">Masuk Diskusi</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($pending_requests)): ?>
                <h5 class="text-muted mb-3">Permintaan Gabung yang Menunggu</h5>
                <div class="list-group">
                    <?php foreach ($pending_requests as $req): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1"><?= htmlspecialchars($req['name']) ?></h6>
                                <p class="mb-1"><?= nl2br(htmlspecialchars($req['description'])) ?></p>
                                <small class="text-muted">⏳ Menunggu Persetujuan</small>
                            </div>
                            <form method="POST" class="ms-3">
                                <input type="hidden" name="cancel_request_id" value="<?= $req['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Batalkan Permintaan</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (count($circles) === 0 && count($pending_requests) === 0): ?>
                <div class="alert alert-warning mt-4">
                    Kamu belum bergabung di circle manapun.
                    <br>
                    <a href="create_circle.php" class="btn btn-sm btn-primary mt-2">Buat Circle Baru</a>
                    <a href="join_circle.php" class="btn btn-sm btn-outline-primary mt-2">Gabung Circle</a>
                </div>
            <?php endif; ?>

        </div>
        <div class="card-footer text-end">
            <a href="../user/dashboard_user.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>
