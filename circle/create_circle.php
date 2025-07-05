<?php
$include_template = $_GET['embed'] ?? false;
if (!$include_template) include '../templates/header.php';

include '../backend/auth/auth_check.php';
include '../includes/db.php';

// Ambil minat
$interest_query = $conn->query("SELECT id, name FROM interests");
$interests = $interest_query->fetch_all(MYSQLI_ASSOC);
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
          <div id="formAlert"></div>

          <form id="createCircleForm">
            <!-- Nama Circle -->
            <div class="mb-3">
              <label class="form-label text-white">Nama Circle <span class="text-danger">*</span></label>
              <input type="text" name="circle_name" class="form-control bg-dark text-white border-secondary" id="circle_name">
              <div class="invalid-feedback" id="error_circle_name"></div>
            </div>

            <!-- Deskripsi -->
            <div class="mb-3">
              <label class="form-label text-white">Deskripsi <span class="text-danger">*</span></label>
              <textarea name="description" rows="4" class="form-control bg-dark text-white border-secondary" id="description"></textarea>
              <div class="invalid-feedback" id="error_description"></div>
            </div>

            <!-- Minat Circle -->
            <div class="mb-3">
              <label class="form-label text-white">Minat Circle <span class="text-danger">*</span></label>
              <div id="interest-error" class="text-danger small mb-2"></div>
              <div class="d-flex flex-wrap gap-2" id="interests-container">
                <?php foreach ($interests as $index => $int): ?>
                  <?php $radioId = 'interest_' . $index; ?>
                  <input type="radio" class="btn-check interest-radio" name="interest_id" id="<?= $radioId ?>" value="<?= $int['id'] ?>" autocomplete="off">
                  <label class="btn btn-outline-primary" for="<?= $radioId ?>"><?= htmlspecialchars($int['name']) ?></label>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Tombol -->
            <div class="d-flex justify-content-end gap-2">
              <button type="submit" class="btn btn-outline-success">
                <i class="bi bi-check-circle me-1"></i> Buat Circle
              </button>
            </div>
          </form>
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
<?php endif; ?>

<script src="../js/create_circle.js"></script>
<?php if (!$include_template): ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    if (typeof initCreateCircleForm === 'function') {
      initCreateCircleForm();
    }
  });
</script>
<?php endif; ?>
<?php if (!$include_template) include '../templates/footer.php'; ?>
