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
                      
                      <?php if (!empty($circle['interest_name'])): ?>
                        <span class="badge bg-info text-dark mb-1">
                          <i class="bi bi-tag-fill me-1"></i><?= htmlspecialchars($circle['interest_name']) ?>
                        </span>
                      <?php endif; ?>
                      
                      <small class="text-muted d-block mt-1">
                        <i class="bi bi-people-fill me-1"></i><?= $circle['member_count'] ?> anggota
                      </small>
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
      </div>
    </div>
  </div>

  <!-- Modal Konfirmasi Gabung -->
  <div class="modal fade" id="confirmJoinModal" tabindex="-1" aria-labelledby="confirmJoinLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-dark text-white border-secondary">
        <div class="modal-header border-bottom">
          <h5 class="modal-title" id="confirmJoinLabel">Konfirmasi</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <p id="confirmJoinMessage" class="mb-0"></p>
        </div>
        <div class="modal-footer border-top">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-outline-light" id="confirmJoinBtn">Ya, Lanjutkan</button>
        </div>
      </div>
    </div>
  </div>
</main>

<?php if (!$include_template): ?>
  <div class="text-center mt-4">
    <a href="../user/dashboard_user.php" class="btn btn-outline-light">
      <i class="bi bi-arrow-left-circle"></i> Kembali ke Dashboard
    </a>
  </div>

  <!-- Hanya load JS & init saat bukan embed -->
  <script src="../js/circle/join_circle.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (typeof initJoinCircleButtons === 'function') {
        initJoinCircleButtons();
      }
    });
  </script>
<?php endif; ?>

<?php if (!$include_template) include '../templates/footer.php'; ?>
