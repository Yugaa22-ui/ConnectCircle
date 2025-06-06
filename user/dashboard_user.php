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
                <ul class="list-group">
                    <li class="list-group-item"><a href="profile.php">👤 Lihat Profil</a></li>
                    <li class="list-group-item"><a href="../circle/create_circle.php">➕ Buat Circle</a></li>
                    <li class="list-group-item"><a href="../circle/join_circle.php">🔍 Gabung Circle</a></li>
                    <li class="list-group-item"><a href="../circle/view_circle.php">📂 Lihat Circle Saya</a></li>
                    <li class="list-group-item"><a href="../search/search.php">🔎 Cari Teman Berdasarkan Minat</a></li>
                    <li class="list-group-item"><a href="../backend/auth/logout.php" class="text-danger">🚪 Logout</a></li>
                </ul>
            </div>
        </div>

        <footer class="text-center mt-4 text-muted">
            &copy; <?= date('Y') ?> ConnectCircle
        </footer>
    </div>

    <!-- Bootstrap JS Bundle (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
