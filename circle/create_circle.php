<?php
$include_template = $_GET['embed'] ?? false;
if (!$include_template) include '../templates/header.php';

include '../backend/auth/auth_check.php';
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
<?php if (!$include_template) include '../templates/footer.php'; ?>
<script src="../js/create_circle.js"></script>
