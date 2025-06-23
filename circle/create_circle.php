<?php
$include_template = $_GET['embed'] ?? false;
if (!$include_template) include '../templates/header.php';

include '../backend/auth/auth_check.php';
include '../backend/circle/create_circle_process.php';
?>

<main class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
      <div class="card bg-dark text-white border-secondary shadow">
        <div class="card-header bg-secondary d-flex align-items-center">
          <i class="bi bi-plus-circle me-2"></i>
          <h5 class="mb-0">Buat Circle Baru</h5>
        </div>

        <div class="card-body">
          <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php elseif (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
          <?php endif; ?>

          <form method="POST" action="">
            <!-- Nama Circle -->
            <div class="mb-3">
              <label class="form-label text-white">Nama Circle <span class="text-danger">*</span></label>
              <input type="text" name="circle_name" class="form-control bg-dark text-white border-secondary" value="<?= htmlspecialchars($circle_name) ?>" required>
            </div>

            <!-- Deskripsi -->
            <div class="mb-3">
              <label class="form-label text-white">Deskripsi</label>
              <textarea name="description" rows="4" class="form-control bg-dark text-white border-secondary" placeholder="Ceritakan tentang circle ini..."><?= htmlspecialchars($description) ?></textarea>
            </div>

            <!-- Tombol -->
            <div class="d-flex justify-content-end gap-2">
              <button type="submit" class="btn btn-outline-success">
                <i class="bi bi-check-circle me-1"></i> Buat Circle
              </button>
              <a href="../user/dashboard_user.php" class="btn btn-outline-light">
                <i class="bi bi-arrow-left me-1"></i> Kembali
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>

<?php if (!$include_template) include '../templates/footer.php'; ?>
