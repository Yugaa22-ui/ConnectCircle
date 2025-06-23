<?php
$include_template = $_GET['embed'] ?? false;
if (!$include_template) include '../templates/header.php';

include '../backend/auth/auth_check.php';
include '../backend/circle/join_circle_process.php';
?>

<main class="container-fluid py-4 px-2 px-md-4">
  <div class="row justify-content-center">
    <div class="col-12 col-md-11 col-lg-10">
      <div class="card bg-dark text-white border-secondary shadow">
        <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
          <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i> Gabung Circle Baru</h5>
        </div>

        <div class="card-body">
          <div id="snackbar-container" class="position-fixed top-50 start-50 translate-middle z-3"></div>

          <?php if (count($available_circles) > 0): ?>
            <div class="list-group">
              <?php foreach ($available_circles as $circle): ?>
                <div class="list-group-item bg-dark text-white border-secondary">
                  <div class="d-flex justify-content-between flex-wrap">
                    <div class="me-3">
                      <h5 class="mb-1">
                        <?= htmlspecialchars($circle['name']) ?>
                        <?php if ($circle['is_private']): ?>
                          <span class="badge bg-secondary"><i class="bi bi-lock-fill me-1"></i>Private</span>
                        <?php else: ?>
                          <span class="badge bg-success"><i class="bi bi-unlock-fill me-1"></i>Public</span>
                        <?php endif; ?>
                      </h5>
                      <p class="mb-1"><?= nl2br(htmlspecialchars($circle['description'])) ?></p>
                      <small class="text-muted">👥 <?= $circle['member_count'] ?> anggota</small>
                    </div>
                    <div class="d-flex align-items-center">
                      <button class="btn btn-sm join-btn <?= $circle['is_private'] ? 'btn-outline-warning' : 'btn-outline-primary' ?>"
                              data-circle-id="<?= $circle['id'] ?>"
                              data-is-private="<?= $circle['is_private'] ?>">
                        <?= $circle['is_private'] ? 'Ajukan Bergabung' : 'Gabung' ?>
                      </button>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="alert alert-info">Tidak ada circle yang tersedia untuk saat ini.</div>
          <?php endif; ?>
        </div>

        <div class="card-footer border-top border-secondary text-end">
          <a href="../user/dashboard_user.php" class="btn btn-outline-light">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
          </a>
        </div>
      </div>
    </div>
  </div>
</main>

<?php if (!$include_template) include '../templates/footer.php'; ?>
