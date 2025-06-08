<?php include '../backend/circle/manage_circle_data.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Circle - <?= htmlspecialchars($circle_name) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .toast-container {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            z-index: 9999;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    <h3 class="mb-4">Kelola Circle: <?= htmlspecialchars($circle_name) ?></h3>

    <!-- Toast Message -->
    <?php if (!empty($msg)): ?>
    <div class="toast-container">
        <div class="toast align-items-center text-bg-success show" role="alert">
            <div class="d-flex">
                <div class="toast-body"><?= $msg ?></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Form Edit -->
    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label class="form-label">Nama Circle</label>
            <input type="text" name="circle_name" class="form-control" value="<?= htmlspecialchars($circle_name) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="circle_description" class="form-control" rows="3"><?= htmlspecialchars($circle_description) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Syarat Bergabung</label>
            <textarea name="rules" class="form-control" rows="2"><?= htmlspecialchars($rules ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>

    <!-- Anggota -->
    <h5 class="mb-3">Anggota Circle</h5>
    <ul class="list-group">
        <?php foreach ($members as $row): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="<?= $row['profile_picture'] ? '../assets/uploads/img/' . $row['profile_picture'] : '../assets/img/default.png' ?>" class="rounded-circle me-2" width="40" height="40">
                <?= htmlspecialchars($row['username']) ?>
                <small class="ms-2 text-muted">(<?= $row['role'] ?>)</small>
            </div>
            <?php if ($row['id'] != $user_id): ?>
                <div class="d-flex flex-wrap gap-1 align-items-center">
            <!-- Kick -->
            <form method="POST">
                <input type="hidden" name="member_id" value="<?= $row['id'] ?>">
                <button name="action" value="kick" class="btn btn-sm btn-outline-danger">Keluarkan</button>
            </form>

            <!-- Mute -->
            <form method="POST" class="d-flex gap-1 align-items-center">
                <input type="hidden" name="member_id" value="<?= $row['id'] ?>">
                <select name="mute_duration" class="form-select form-select-sm" style="width: auto;" required>
                    <option value="1">1 jam</option>
                    <option value="6">6 jam</option>
                    <option value="24">1 hari</option>
                </select>
                <button name="action" value="mute" class="btn btn-sm btn-outline-warning">Mute</button>
            </form>

            <!-- Promote -->
            <form method="POST" onsubmit="return confirm('Yakin ingin menjadikan anggota ini sebagai moderator?')">
                <input type="hidden" name="member_id" value="<?= $row['id'] ?>">
                <button name="action" value="promote" class="btn btn-sm btn-outline-success">Promosikan</button>
            </form>
        </div>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ul>

    <hr class="my-4">
    <p><strong>Anggota Paling Aktif:</strong> <?= $top_active ?? '-' ?></p>
    <p><strong>Anggota Baru Bergabung:</strong> <?= $newest ?? '-' ?></p>

    <a href="../circle/discussion_page.php?circle_id=<?= $circle_id ?>" class="btn btn-secondary mt-3">🔙 Kembali ke Diskusi</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
