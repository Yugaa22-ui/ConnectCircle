<?php include '../backend/user/dashboard_user_data.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pengguna - ConnectCircle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Selamat Datang, <?= htmlspecialchars($username) ?>!</h3>
            </div>
            <div class="card-body">
                <p class="mb-3">Pilih salah satu menu berikut:</p>
                <div class="list-group">
                    <a href="profile.php" class="list-group-item list-group-item-action">👤 Lihat Profil</a>
                    <a href="../circle/create_circle.php" class="list-group-item list-group-item-action">➕ Buat Circle</a>
                    <a href="../circle/join_circle.php" class="list-group-item list-group-item-action">🔍 Gabung Circle</a>
                    <a href="../circle/view_circle.php" class="list-group-item list-group-item-action">📂 Lihat Circle Saya</a>
                    <a href="../search/search.php" class="list-group-item list-group-item-action">🔎 Cari Teman Berdasarkan Minat</a>
                    <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#logoutModal">🚪 Logout</a>
                </div>
            </div>
        </div>

        <footer class="text-center mt-4 text-muted">
            &copy; <?= date('Y') ?> ConnectCircle
        </footer>
    </div>

    <!-- Bootstrap JS Bundle (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Modal Konfirmasi Logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header bg-warning">
            <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
            Apakah kamu yakin ingin keluar dari ConnectCircle?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
            <a href="../backend/auth/logout.php" class="btn btn-danger">Ya, Logout</a>
        </div>
        </div>
    </div>
    </div>
</body>
</html>
