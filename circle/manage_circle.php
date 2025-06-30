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
          <input type="text" name="circle_name" class="form-control bg-dark text-light border-secondary" value="<?= htmlspecialchars($circle_name) ?>" required <?= $user_id !== $creator_id ? 'readonly' : '' ?>>
        </div>
        <div class="mb-3">
          <label class="form-label text-light">Deskripsi</label>
          <textarea name="circle_description" class="form-control bg-dark text-light border-secondary" rows="3" <?= $user_id !== $creator_id ? 'readonly' : '' ?>><?= htmlspecialchars($circle_description) ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label text-light">Syarat Bergabung</label>
          <textarea name="rules" class="form-control bg-dark text-light border-secondary" rows="2" <?= $user_id !== $creator_id ? 'readonly' : '' ?>><?= htmlspecialchars($rules ?? '') ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label text-light">Minat Circle</label>
          <select name="interest_id" class="form-select bg-dark text-light border-secondary" required <?= $user_id !== $creator_id ? 'disabled' : '' ?>>
            <option value="" disabled <?= $current_interest_id === null ? 'selected' : '' ?>>Pilih minat...</option>
            <?php foreach ($interests as $interest): ?>
              <option value="<?= $interest['id'] ?>" <?= ($interest['id'] == $current_interest_id ? 'selected' : '') ?>>
                <?= htmlspecialchars($interest['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <small class="text-muted">Hanya creator yang dapat mengubah pengaturan ini.</small>
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="is_private" id="is_private" <?= $is_private ? 'checked' : '' ?> <?= $user_id !== $creator_id ? 'disabled' : '' ?>>
          <label class="form-check-label text-light" for="is_private">
            Circle ini <strong>Private</strong> (butuh persetujuan untuk bergabung)
          </label>
        </div>
        <?php if ($user_id === $creator_id): ?>
          <button type="submit" name="update_circle" value="1" class="btn btn-primary">Simpan Perubahan</button>
        <?php endif; ?>
      </form>
      <a href="discussion_page.php?circle_id=<?= $circle_id ?>" class="btn btn-outline-light mt-3">
        <i class="bi bi-arrow-left-circle"></i> Kembali ke Diskusi
      </a>
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
              <?php
                // Tentukan apakah tombol tindakan boleh ditampilkan
                $show_actions = false;
                if ($user_id === $creator_id) {
                    $show_actions = true;
                } elseif ($role === 'moderator' && $row['role'] === 'member') {
                    $show_actions = true;
                }
              ?>
              <?php if ($show_actions): ?>
                <div class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
                  <!-- Kick -->
                  <button
                    type="button"
                    class="btn btn-sm btn-outline-danger"
                    onclick="showGlobalModal(<?= $row['id'] ?>, 'kick', 'Keluarkan Anggota', 'Yakin ingin mengeluarkan <strong><?= htmlspecialchars($row['username']) ?></strong> dari circle?', null, 'danger')">
                    <i class="bi bi-person-x"></i>
                  </button>

                  <!-- Mute -->
                  <form class="d-flex align-items-center">
                    <select name="mute_duration" class="form-select form-select-sm bg-dark text-light border-secondary me-2" style="width: auto;">
                      <option value="1">1 jam</option>
                      <option value="6">6 jam</option>
                      <option value="24">1 hari</option>
                    </select>
                    <button
                      type="button"
                      class="btn btn-sm btn-outline-warning"
                      onclick="handleMuteClick(this, <?= $row['id'] ?>, '<?= htmlspecialchars($row['username']) ?>')">
                      <i class="bi bi-volume-mute"></i>
                    </button>
                  </form>

                  <?php if ($user_id === $creator_id): ?>
                    <!-- Promote/Demote hanya Creator -->
                    <?php if ($row['role'] === 'moderator'): ?>
                      <button
                        type="button"
                        class="btn btn-sm btn-outline-secondary"
                        onclick="showGlobalModal(<?= $row['id'] ?>, 'demote', 'Cabut Moderator', 'Yakin ingin mencabut moderator dari <strong><?= htmlspecialchars($row['username']) ?></strong>?', null, 'secondary')">
                        <i class="bi bi-person-dash"></i>
                      </button>
                    <?php else: ?>
                      <button
                        type="button"
                        class="btn btn-sm btn-outline-success"
                        onclick="showGlobalModal(<?= $row['id'] ?>, 'promote', 'Promosikan Menjadi Moderator', 'Yakin ingin mempromosikan <strong><?= htmlspecialchars($row['username']) ?></strong> menjadi moderator?', null, 'success')">
                        <i class="bi bi-person-plus"></i>
                      </button>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            <?php endif; ?>
          </li>
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

<!-- Modal Konfirmasi Global -->
<div class="modal fade" id="globalConfirmModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-light border border-secondary">
      <form id="globalActionForm" method="POST">
        <input type="hidden" name="member_id" id="globalMemberId">
        <input type="hidden" name="action" id="globalAction">
        <input type="hidden" name="mute_duration" id="globalMuteDuration">
        <div class="modal-header border-bottom border-secondary">
          <h5 class="modal-title" id="globalModalTitle">Konfirmasi</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="globalModalBody">
          Apakah Anda yakin?
        </div>
        <div class="modal-footer border-top border-secondary">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Ya</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function showGlobalModal(memberId, action, title, body, duration, color) {
  document.getElementById("globalModalTitle").textContent = title;
  document.getElementById("globalModalBody").innerHTML = body;
  document.getElementById("globalMemberId").value = memberId;
  document.getElementById("globalAction").value = action;
  document.getElementById("globalMuteDuration").value = duration || "";
  const submitBtn = document.querySelector("#globalActionForm button[type=submit]");
  submitBtn.className = `btn btn-${color}`;
  new bootstrap.Modal(document.getElementById("globalConfirmModal")).show();
}

function handleMuteClick(btn, memberId, username) {
  const form = btn.closest("form");
  const select = form.querySelector("select[name='mute_duration']");
  const duration = select ? select.value : "1";
  let label = duration + " jam";
  if (duration === "24") label = "1 hari";
  else if (duration === "6") label = "6 jam";
  else if (duration === "1") label = "1 jam";

  showGlobalModal(
    memberId,
    'mute',
    'Mute Anggota',
    `Yakin ingin mute <strong>${username}</strong> selama ${label}?`,
    duration,
    'warning'
  );
}

document.addEventListener("DOMContentLoaded", function () {
  const toastEl = document.querySelector('.toast');
  if (toastEl) {
    const bsToast = new bootstrap.Toast(toastEl, { delay: 3000 });
    bsToast.show();
  }
});
</script>

<?php require __DIR__ . '/../templates/footer.php'; ?>
