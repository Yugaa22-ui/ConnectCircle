<?php
$include_template = $_GET['embed'] ?? false;
if (!$include_template) include '../templates/header.php';

include '../backend/auth/auth_check.php';
include '../backend/user/change_password_process.php';
?>

<main class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
      <div class="card bg-dark text-white border-secondary shadow">
        <div class="card-header bg-secondary text-white d-flex align-items-center">
          <i class="bi bi-key-fill me-2"></i>
          <h5 class="mb-0">Ubah Password</h5>
        </div>

        <div class="card-body">
          <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
          <?php elseif (!empty($errors['global'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errors['global']) ?></div>
          <?php endif; ?>

          <form method="POST" action="">
            <!-- Password Lama -->
            <div class="mb-3">
              <label class="form-label text-white">Password Lama *</label>
              <div class="input-group">
                <input type="password" name="old_password" id="old_password" class="form-control <?= isset($errors['old_password']) ? 'is-invalid' : '' ?>">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="old_password">
                  <i class="bi bi-eye-slash" id="icon-old_password"></i>
                </button>
                <?php if (isset($errors['old_password'])): ?>
                  <div class="invalid-feedback d-block"><?= $errors['old_password'] ?></div>
                <?php endif; ?>
              </div>
            </div>

            <!-- Password Baru -->
            <div class="mb-3">
              <label class="form-label text-white">Password Baru *</label>
              <div class="input-group">
                <input type="password" name="new_password" id="new_password" class="form-control <?= isset($errors['new_password']) ? 'is-invalid' : '' ?>">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                  <i class="bi bi-eye-slash" id="icon-new_password"></i>
                </button>
              </div>
              <div class="form-text text-white-50">Minimal 8 karakter, kombinasi huruf besar, kecil, dan angka.</div>
              <?php if (isset($errors['new_password'])): ?>
                <div class="invalid-feedback d-block"><?= $errors['new_password'] ?></div>
              <?php endif; ?>
            </div>

            <!-- Konfirmasi Password Baru -->
            <div class="mb-3">
              <label class="form-label text-white">Konfirmasi Password Baru *</label>
              <div class="input-group">
                <input type="password" name="confirm_password" id="confirm_password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirm_password">
                  <i class="bi bi-eye-slash" id="icon-confirm_password"></i>
                </button>
              </div>
              <?php if (isset($errors['confirm_password'])): ?>
                <div class="invalid-feedback d-block"><?= $errors['confirm_password'] ?></div>
              <?php endif; ?>
            </div>

            <div class="d-flex justify-content-end gap-2">
              <button type="submit" class="btn btn-outline-light">
                <i class="bi bi-check-circle me-1"></i> Simpan
              </button>
              <a href="profile.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Link toggle password -->
<script src="../js/components/toggle_password.js"></script>

<?php if (!$include_template) include '../templates/footer.php'; ?>
