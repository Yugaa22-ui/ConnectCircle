<?php include '../backend/user/profile_data.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya - ConnectCircle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Profil Saya</h3>
        </div>
        <div class="card-body">
        <div class="text-center mb-4">
            <img
                src="<?= $profile_picture ? '../assets/uploads/img/' . htmlspecialchars($profile_picture) : '../assets/img/default.png' ?>"
                class="rounded-circle"
                width="120"
                height="120"
                alt="Foto Profil">
        </div>


            <table class="table">
                <tr><th>Username</th><td><?= htmlspecialchars($username) ?></td></tr>
                <tr><th>Email</th><td><?= htmlspecialchars($email) ?></td></tr>
                <tr><th>Kota</th><td><?= htmlspecialchars($city) ?></td></tr>
                <tr><th>Profesi</th><td><?= htmlspecialchars($profession) ?></td></tr>
                <tr><th>Bio</th><td><?= nl2br(htmlspecialchars($bio)) ?></td></tr>
            </table>

            <h5 class="mt-4">🎖️ Badge yang Dimiliki</h5>
            <?php if ($badges_result->num_rows > 0): ?>
                <ul class="list-group">
                    <?php while ($badge = $badges_result->fetch_assoc()): ?>
                        <li class="list-group-item">
                            <?= $badge['icon'] ? $badge['icon'] . ' ' : '' ?>
                            <strong><?= htmlspecialchars($badge['name']) ?></strong>
                            <br><small><?= htmlspecialchars($badge['description']) ?></small>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">Belum ada badge.</p>
            <?php endif; ?>
        </div>
        <div class="card-footer text-end">
            <a href="edit_profile.php" class="btn btn-secondary">Edit Profil</a>
            <a href="change_password.php" class="btn btn-warning">Ubah Password</a>
            <a href="dashboard_user.php" class="btn btn-outline-dark">Kembali</a>
        </div>
    </div>
</div>
</body>
</html>
