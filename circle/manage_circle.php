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

    <!-- Form Edit Circle -->
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
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_private" id="is_private" <?= $is_private ? 'checked' : '' ?>>
            <label class="form-check-label" for="is_private">
                Circle ini Private (butuh persetujuan untuk bergabung)
            </label>
        </div>
        <button type="submit" name="update_circle" value="1" class="btn btn-primary">Simpan Perubahan</button>
    </form>

    <!-- Daftar Anggota -->
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
                <form method="POST">
                    <input type="hidden" name="member_id" value="<?= $row['id'] ?>">
                    <button name="action" value="kick" class="btn btn-sm btn-outline-danger">Keluarkan</button>
                </form>

                <form method="POST" class="d-flex gap-1 align-items-center">
                    <input type="hidden" name="member_id" value="<?= $row['id'] ?>">
                    <select name="mute_duration" class="form-select form-select-sm" style="width: auto;" required>
                        <option value="1">1 jam</option>
                        <option value="6">6 jam</option>
                        <option value="24">1 hari</option>
                    </select>
                    <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#confirmMuteModal<?= $row['id'] ?>">Mute</button>
                </form>

                <form method="POST">
                    <input type="hidden" name="member_id" value="<?= $row['id'] ?>">
                    <?php if ($row['role'] === 'moderator'): ?>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#confirmDemoteModal<?= $row['id'] ?>">Cabut Moderator</button>
                    <?php else: ?>
                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#confirmPromoteModal<?= $row['id'] ?>">Promosikan</button>
                    <?php endif; ?>
                </form>
            </div>
            <?php endif; ?>
        </li>
        <!-- Modal Promosi -->
                <div class="modal fade" id="confirmPromoteModal<?= $row['id'] ?>" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <form method="POST">
                  <input type="hidden" name="member_id" value="<?= $row['id'] ?>">
                  <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Promosi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">Yakin ingin menjadikan <?= htmlspecialchars($row['username']) ?> sebagai moderator?</div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <button type="submit" name="action" value="promote" class="btn btn-success">Ya</button>
                  </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Modal Demote -->
        <div class="modal fade" id="confirmDemoteModal<?= $row['id'] ?>" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <form method="POST">
                  <input type="hidden" name="member_id" value="<?= $row['id'] ?>">
                  <div class="modal-header">
                    <h5 class="modal-title">Cabut Moderator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">Yakin ingin mencabut moderator dari <?= htmlspecialchars($row['username']) ?>?</div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <button type="submit" name="action" value="demote" class="btn btn-danger">Ya</button>
                  </div>
              </form>
            </div>
          </div>
        </div>
        <!-- Modal Mute -->
                <div class="modal fade" id="confirmMuteModal<?= $row['id'] ?>" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <form method="POST">
                  <input type="hidden" name="member_id" value="<?= $row['id'] ?>">
                  <input type="hidden" name="mute_duration" value="1">
                  <div class="modal-header">
                    <h5 class="modal-title">Mute Anggota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">Yakin ingin mute <?= htmlspecialchars($row['username']) ?> selama 1 jam?</div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <button type="submit" name="action" value="mute" class="btn btn-warning">Ya</button>
                  </div>
              </form>
            </div>
          </div>
        </div>
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
