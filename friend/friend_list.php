<?php
include '../backend/auth/auth_check.php';
include '../backend/friend/friend_list_process.php';

$embed = isset($_GET['embed']) && $_GET['embed'] == '1';
if (!$embed) include '../templates/header.php';
?>

<div class="container-fluid mt-3">
  <div class="card bg-dark text-white border-secondary shadow">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center flex-wrap">
      <h4 class="mb-0">
        <i class="bi bi-people-fill me-2"></i> Daftar Teman
      </h4>
      <?php if (!$embed): ?>
        <a href="../user/dashboard_user.php" class="btn btn-outline-light btn-sm mt-2 mt-md-0">
          <i class="bi bi-arrow-left-circle"></i> Kembali
        </a>
      <?php endif; ?>
    </div>
    <div class="card-body">
      <?php if (count($friends) > 0): ?>
        <div class="list-group">
          <?php foreach ($friends as $friend): ?>
            <div class="list-group-item list-group-item-dark d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 py-3" id="friend-item-<?= $friend['id'] ?>">
              <div class="d-flex align-items-center gap-3 w-100">
                <img src="<?= $friend['profile_picture']
                  ? '../assets/uploads/img/' . htmlspecialchars($friend['profile_picture'])
                  : '../assets/img/default.png' ?>"
                  alt="Foto <?= htmlspecialchars($friend['username']) ?>"
                  class="rounded-circle flex-shrink-0"
                  width="48"
                  height="48"
                  style="object-fit: cover;">
                <div class="flex-grow-1">
                  <h6 class="mb-1 text-light text-break"><?= htmlspecialchars($friend['username']) ?></h6>
                  <small class="text-muted">
                    <?= htmlspecialchars($friend['profession']) ?>
                    <?= $friend['profession'] && $friend['city'] ? ' dari ' : '' ?>
                    <?= htmlspecialchars($friend['city']) ?>
                  </small>
                </div>
              </div>
              <div class="d-flex flex-column flex-md-row align-items-center gap-2">
                <span class="badge bg-success d-flex align-items-center justify-content-center">
                  <i class="bi bi-check-circle-fill me-1"></i> Berteman
                </span>
                <button class="btn btn-outline-danger btn-sm btn-remove-friend"
                  data-user-id="<?= $friend['id'] ?>"
                  data-user-name="<?= htmlspecialchars($friend['username']) ?>">
                  <i class="bi bi-person-x"></i> Hapus Pertemanan
                </button>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="alert alert-warning d-flex align-items-center" role="alert">
          <i class="bi bi-exclamation-circle-fill me-2"></i>
          Belum ada teman yang terhubung.
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script src="../js/friend/friend_list.js"></script>

<?php if (!$embed) include '../templates/footer.php'; ?>
