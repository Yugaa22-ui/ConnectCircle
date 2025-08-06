<?php
session_start();
$errors = $_SESSION['login_errors'] ?? [];
$old_email = $_SESSION['old_email'] ?? '';
unset($_SESSION['login_errors'], $_SESSION['old_email']);
?>

<?php include '../templates/header.php'; ?>

<main class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header card-header-dark">
          <h3 class="mb-0">Login ke ConnectCircle</h3>
        </div>
        <div class="card-body">

          <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
          <?php endif; ?>

          <form method="POST" action="../backend/auth/login_process.php" novalidate autocomplete="off">
            <!-- Anti-autofill -->
            <input type="text" name="fakeusernameremembered" style="display:none">
            <input type="password" name="fakepasswordremembered" style="display:none">

            <div class="mb-3">
              <label class="form-label">Email *</label>
              <input type="email" name="login_email"
                class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($old_email) ?>" required autocomplete="off" autofocus>
              <?php if (isset($errors['email'])): ?>
                <div class="invalid-feedback"><?= $errors['email'] ?></div>
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label">Password *</label>
              <div class="input-group">
                <input type="password" name="login_password" id="password"
                  class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                  autocomplete="new-password" required>
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                  <i class="bi bi-eye-slash" id="icon-password"></i>
                </button>
              </div>
              <?php if (isset($errors['password'])): ?>
                <div class="invalid-feedback d-block"><?= $errors['password'] ?></div>
              <?php endif; ?>
            </div>

            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-outline-light">Login</button>  
              <a href="register.php" class="btn btn-secondary">Belum punya akun? Daftar</a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</main>

<!-- Script toggle password -->
<script src="../js/components/toggle_password.js"></script>

<?php include '../templates/footer.php'; ?>
