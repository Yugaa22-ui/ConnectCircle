<?php
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/../backend/circle/manage_circle_data.php';
?>

<div class="container py-4">
  <h3 class="text-light mb-4">Kelola Circle: <?= htmlspecialchars($circle_name) ?></h3>

  <?php if (!empty($msg)): ?>
    <div class="position-fixed bottom-0 end-0 p-3 z-3">
      <div class="toast align-items-center text-bg-success show border border-light" role="alert">
        <div class="d-flex">
          <div class="toast-body"><?= $msg ?></div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- Tab Navigation -->
  <ul class="nav nav-tabs mb-4" id="circleTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="setting-tab" data-bs-toggle="tab" data-bs-target="#setting" type="button" role="tab">Pengaturan</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="member-tab" data-bs-toggle="tab" data-bs-target="#member" type="button" role="tab">Anggota</button>
    </li>
  </ul>

  <div class="tab-content" id="circleTabContent">
    <!-- Tab: Pengaturan -->
    <div class="tab-pane fade show active" id="setting" role="tabpanel">
      <form method="POST" class="mb-4 bg-dark p-4 rounded shadow border border-secondary">
        <div class="mb-3">
          <label class="form-label text-light">Nama Circle</label>
          <input type="text" name="circle_name" class="form-control bg-dark text-light border-secondary" value="<?= htmlspecialchars($circle_name) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label text-light">Deskripsi</label>
          <textarea name="circle_description" class="form-control bg-dark text-light border-secondary" rows="3"><?= htmlspecialchars($circle_description) ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label text-light">Syarat Bergabung</label>
          <textarea name="rules" class="form-control bg-dark text-light border-secondary" rows="2"><?= htmlspecialchars($rules ?? '') ?></textarea>
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="is_private" id="is_private" <?= $is_private ? 'checked' : '' ?>>
          <label class="form-check-label text-light" for="is_private">
            Circle ini <strong>Private</strong> (butuh persetujuan untuk bergabung)
          </label>
        </div>
        <button type="submit" name="update_circle" value="1" class="btn btn-primary">Simpan Perubahan</button>
      </form>
    </div>

    <!-- Tab: Anggota -->
    <div class="tab-pane fade" id="member" role="tabpanel">
      <ul class="list-group mb-4">
        <?php foreach ($members as $row): ?>
          <li class="list-group-item bg-dark text-light border-secondary d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center flex-grow-1">
              <img src="<?= $row['profile_picture'] ? '../assets/uploads/img/' . $row['profile_picture'] : '../assets/img/default.png' ?>"
                   class="rounded-circle me-3 border border-secondary" width="40" height="40" alt="User">
              <div>
                <?= htmlspecialchars($row['username']) ?>
                <small class="text-muted ms-2">(<?= $row['role'] ?>)</small>
              </div>
            </div>

            <?php if ($row['id'] != $user_id): ?>
              <div class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
                <!-- Kick -->
                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmKickModal<?= $row['id'] ?>">
                  <i class="bi bi-person-x"></i>
                </button>
                <form method="POST" class="d-flex align-items-center">
                  <input type="hidden" name="member_id" value="<?= $row['id'] ?>">
                  <select name="mute_duration" class="form-select form-select-sm bg-dark text-light border-secondary me-2" style="width: auto;">
                    <option value="1">1 jam</option>
                    <option value="6">6 jam</option>
                    <option value="24">1 hari</option>
                  </select>
                  <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#confirmMuteModal<?= $row['id'] ?>">
                    <i class="bi bi-volume-mute"></i>
                  </button>
                </form>

                <form method="POST">
                  <input type="hidden" name="member_id" value="<?= $row['id'] ?>">
                  <?php if ($row['role'] === 'moderator'): ?>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#confirmDemoteModal<?= $row['id'] ?>">
                      <i class="bi bi-person-dash"></i>
                    </button>
                  <?php else: ?>
                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#confirmPromoteModal<?= $row['id'] ?>">
                      <i class="bi bi-person-plus"></i>
                    </button>
                  <?php endif; ?>
                </form>
              </div>
            <?php endif; ?>
          </li>

          <!-- Modal Kick -->
          <div class="modal fade" id="confirmKickModal<?= $row['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content bg-dark text-light border border-secondary">
                <form method="POST">
                  <input type="hidden" name="member_id" value="<?= $row['id'] ?>">
                  <div class="modal-header border-bottom border-secondary">
                    <h5 class="modal-title">Keluarkan Anggota</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">Yakin ingin mengeluarkan <strong><?= htmlspecialchars($row['username']) ?></strong> dari circle?</div>
                  <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="action" value="kick" class="btn btn-danger">Ya</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

      <!-- Modal Demote -->
      <div class="modal fade" id="confirmDemoteModal<?= $row['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content bg-dark text-light border border-secondary">
            <form method="POST">
              <input type="hidden" name="member_id" value="<?= $row['id'] ?>">
              <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title">Cabut Moderator</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">Yakin ingin mencabut moderator dari <strong><?= htmlspecialchars($row['username']) ?></strong>?</div>
              <div class="modal-footer border-top border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="action" value="demote" class="btn btn-danger">Ya</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Modal Mute -->
      <div class="modal fade" id="confirmMuteModal<?= $row['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content bg-dark text-light border border-secondary">
            <form method="POST">
              <input type="hidden" name="member_id" value="<?= $row['id'] ?>">
              <input type="hidden" name="mute_duration" value="1">
              <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title">Mute Anggota</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">Yakin ingin mute <strong><?= htmlspecialchars($row['username']) ?></strong> selama 1 jam?</div>
              <div class="modal-footer border-top border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="action" value="mute" class="btn btn-warning">Ya</button>
              </div>
            </form>
          </div>
        </div>
      </div>
        <?php endforeach; ?>
      </ul>

      <div class="text-light">
        <p><strong>Anggota Paling Aktif:</strong> <?= $top_active ?? '-' ?></p>
        <p><strong>Anggota Baru Bergabung:</strong> <?= $newest ?? '-' ?></p>
      </div>

      <a href="discussion_page.php?circle_id=<?= $circle_id ?>" class="btn btn-outline-light mt-3">
        <i class="bi bi-arrow-left-circle"></i> Kembali ke Diskusi
      </a>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../templates/footer.php'; ?>
