<?php
include '../backend/auth/auth_check.php';
include '../backend/friend/friend_list_process.php';

$embed = isset($_GET['embed']) && $_GET['embed'] == '1';
if (!$embed) include '../templates/header.php';
?>

<div class="container-fluid mt-3">
  <div class="card bg-dark text-white border-secondary shadow">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0">
        <i class="bi bi-people-fill me-2"></i> Daftar Teman
      </h4>
      <?php if (!$embed): ?>
        <a href="../user/dashboard_user.php" class="btn btn-outline-light btn-sm">
          <i class="bi bi-arrow-left-circle"></i> Kembali
        </a>
      <?php endif; ?>
    </div>
    <div class="card-body">
      <?php if (count($friends) > 0): ?>
        <div class="list-group">
          <?php foreach ($friends as $friend): ?>
            <div class="list-group-item list-group-item-dark d-flex justify-content-between align-items-center">
              <div class="text-truncate">
                <h6 class="mb-1 text-light">
                  <i class="bi bi-person-circle text-primary me-1"></i>
                  <?= htmlspecialchars($friend['username']) ?>
                </h6>
                <small class="text-muted">
                  <?= htmlspecialchars($friend['profession']) ?> dari <?= htmlspecialchars($friend['city']) ?>
                </small>
              </div>
              <span class="badge bg-success d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-1"></i> Berteman
              </span>
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

<?php if (!$embed) include '../templates/footer.php'; ?>
