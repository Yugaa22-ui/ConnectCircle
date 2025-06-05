<?php
include '../includes/db.php';

// Ambil beberapa circle publik
$circles = $conn->query("SELECT name, description, created_at FROM circles ORDER BY created_at DESC LIMIT 5");

// Ambil beberapa user aktif (yang punya posting)
$users = $conn->query("
    SELECT u.username, u.profession, u.city, COUNT(p.id) AS total_post
    FROM users u
    JOIN posts p ON u.id = p.user_id
    GROUP BY u.id
    ORDER BY total_post DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Guest Dashboard - ConnectCircle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <h1 class="text-center mb-4">Selamat Datang di ConnectCircle 👋</h1>
        <p class="text-center text-muted mb-5">
            Kamu sedang mengakses sebagai <strong>Guest</strong>. Untuk bergabung ke circle dan berdiskusi, silakan login atau daftar dulu.
        </p>

        <!-- Circle Terbaru -->
        <h4>🔁 Circle Terbaru</h4>
        <div class="list-group mb-5">
            <?php if ($circles->num_rows > 0): ?>
                <?php while ($c = $circles->fetch_assoc()): ?>
                    <div class="list-group-item">
                        <h5><?= htmlspecialchars($c['name']) ?></h5>
                        <p><?= nl2br(htmlspecialchars($c['description'])) ?></p>
                        <small class="text-muted">Dibuat pada: <?= date("d M Y", strtotime($c['created_at'])) ?></small>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-muted">Belum ada circle yang tersedia.</p>
            <?php endif; ?>
        </div>

        <!-- Pengguna Aktif -->
        <h4>🔥 Pengguna Aktif</h4>
        <ul class="list-group">
            <?php while ($u = $users->fetch_assoc()): ?>
                <li class="list-group-item">
                    <strong><?= htmlspecialchars($u['username']) ?></strong> (<?= htmlspecialchars($u['profession']) ?> dari <?= htmlspecialchars($u['city']) ?>)<br>
                    <small>Posting: <?= $u['total_post'] ?> diskusi</small>
                </li>
            <?php endwhile; ?>
        </ul>

        <!-- CTA -->
        <div class="text-center mt-5">
            <a href="../auth/login.php" class="btn btn-primary btn-lg">🔐 Login untuk Bergabung</a>
        </div>
    </div>
</body>
</html>
