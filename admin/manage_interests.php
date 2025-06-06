<?php include '../backend/admin/manage_interests_process.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Minat - Admin | ConnectCircle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script>
        <?php if ($success): ?>alert("<?= $success ?>");<?php endif; ?>
        <?php if ($error): ?>alert("<?= $error ?>");<?php endif; ?>
    </script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4>Kelola Minat Pengguna</h4>
        </div>
        <div class="card-body">
            <form method="POST" class="mb-3">
                <div class="mb-3">
                    <label class="form-label">Tambah Minat Baru:</label>
                    <input type="text" name="new_interest" class="form-control" placeholder="Contoh: Musik, Menulis, Desain" required>
                </div>
                <button type="submit" class="btn btn-success">Tambah</button>
            </form>

            <hr>

            <h5>Daftar Minat Tersedia</h5>
            <?php if ($all->num_rows > 0): ?>
                <ul class="list-group">
                    <?php while ($row = $all->fetch_assoc()): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($row['name']) ?>
                            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus minat ini?')" class="btn btn-sm btn-danger">Hapus</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">Belum ada data minat.</p>
            <?php endif; ?>
        </div>
        <div class="card-footer text-end">
            <a href="dashboard_admin.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>
