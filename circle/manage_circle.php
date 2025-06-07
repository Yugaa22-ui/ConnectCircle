<?php include '../backend/circle/manage_circle_data.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Circle - <?= htmlspecialchars($circle_name) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h3>Kelola Circle: <?= htmlspecialchars($circle_name) ?></h3>

    <?php if (!empty($msg)): ?>
        <div class="alert alert-success"><?= $msg ?></div>
    <?php endif; ?>

    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label>Nama Circle</label>
            <input type="text" name="circle_name" class="form-control" value="<?= htmlspecialchars($circle_name) ?>">
        </div>
        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="circle_description" class="form-control" rows="3"><?= htmlspecialchars($circle_description) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Syarat Bergabung</label>
            <textarea name="rules" class="form-control" rows="2"><?= htmlspecialchars($rules ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>

    <h5>Anggota Circle</h5>
    <ul class="list-group">
        <?php while ($row = $members->fetch_assoc()): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <img src="<?= $row['profile_picture'] ? '../assets/uploads/img/' . $row['profile_picture'] : '../assets/img/default.png' ?>" class="rounded-circle me-2" width="40" height="40">
                    <?= htmlspecialchars($row['username']) ?>
                    <small class="ms-2 text-muted">(<?= $row['role'] ?>)</small>
                </div>
                <?php if ($row['id'] !== $user_id): ?>
                    <form method="POST" class="d-flex gap-1">
                        <input type="hidden" name="member_id" value="<?= $row['id'] ?>">
                        <button name="action" value="kick" class="btn btn-sm btn-danger">Keluarkan</button>
                        <button name="action" value="mute" class="btn btn-sm btn-warning">Mute</button>
                        <button name="action" value="promote" class="btn btn-sm btn-success">Jadikan Moderator</button>
                    </form>
                <?php endif; ?>
            </li>
        <?php endwhile; ?>
    </ul>

    <hr>
    <p><strong>Anggota Paling Aktif:</strong> <?= $top_active['username'] ?? '-' ?></p>
    <p><strong>Anggota Baru Bergabung:</strong> <?= $newest['username'] ?? '-' ?></p>

    <a href="../circle/discussion_page.php?circle_id=<?= $circle_id ?>" class="btn btn-secondary mt-3">Kembali ke Diskusi</a>
</div>
</body>
</html>
