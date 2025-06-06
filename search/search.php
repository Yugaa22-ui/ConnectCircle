<?php include '../backend/auth/auth_check.php'; ?>
<?php include '../backend/search/search_process.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cari Teman Berdasarkan Minat - ConnectCircle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>Cari Teman Berdasarkan Minat</h4>
        </div>
        <div class="card-body">
            <form method="GET" action="">
                <div class="mb-3">
                    <label class="form-label">Masukkan Kata Kunci Minat:</label>
                    <input type="text" name="minat" class="form-control" value="<?= htmlspecialchars($search_term) ?>" placeholder="Contoh: Coding, Musik, Desain" required>
                </div>
                <button type="submit" class="btn btn-success">Cari</button>
            </form>

            <hr>

            <?php if (isset($_GET['minat'])): ?>
                <h5>Hasil untuk: "<strong><?= htmlspecialchars($search_term) ?></strong>"</h5>

                <?php if ($total_matches > 0): ?>
                    <p><strong><?= $total_matches ?></strong> pengguna ditemukan.</p>
                    <div class="list-group">
                        <?php while ($row = $results->fetch_assoc()): ?>
                            <div class="list-group-item">
                                <h6><?= htmlspecialchars($row['username']) ?></h6>
                                <p class="mb-0"><?= htmlspecialchars($row['profession']) ?> dari <?= htmlspecialchars($row['city']) ?></p>
                                <small class="text-muted">Minat: <?= htmlspecialchars($row['interest']) ?></small>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning mt-3">Tidak ada pengguna dengan minat tersebut.</div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="card-footer text-end">
            <a href="../user/dashboard_user.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>
