<?php
include '../backend/auth/auth_check.php';
include '../backend/friend/friend_request_process.php';

$embed = isset($_GET['embed']) && $_GET['embed'] == '1';
if (!$embed) include '../templates/header.php';
?>

<div class="container-fluid mt-3">
  <div class="card bg-dark text-white border-secondary shadow">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0"><i class="bi bi-person-plus me-2"></i> Permintaan Pertemanan</h4>
      <?php if (!$embed): ?>
        <a href="../user/dashboard_user.php" class="btn btn-outline-light btn-sm">
          <i class="bi bi-arrow-left-circle"></i> Kembali
        </a>
      <?php endif; ?>
    </div>
    <div class="card-body">

      <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
      <?php elseif (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <?php if (count($requests) > 0): ?>
        <ul class="list-group" id="friend-request-list">
            <?php foreach ($requests as $req): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                      <strong><?= htmlspecialchars($req['username']) ?></strong><br>
                      <small class="text-muted">Dari: <?= htmlspecialchars($req['city']) ?> | Profesi: <?= htmlspecialchars($req['profession']) ?></small>
                  </div>
                  <form method="POST" data-request-form class="d-flex gap-2 mb-0">
                      <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
                      <button type="submit" name="action" value="accept" class="btn btn-sm btn-success">
                        <i class="bi bi-check-circle"></i> Terima
                      </button>
                      <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger">
                        <i class="bi bi-x-circle"></i> Tolak
                      </button>
                  </form>
                </li>
            <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <div class="alert alert-info">Tidak ada permintaan pertemanan saat ini.</div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php if (!$embed) include '../templates/footer.php'; ?>
