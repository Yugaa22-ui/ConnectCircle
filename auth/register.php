<?php
session_start();
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
include '../includes/db.php';

// Ambil data minat
$interest_query = $conn->query("SELECT id, name FROM interests");
$interests = $interest_query->fetch_all(MYSQLI_ASSOC);
?>

<?php include '../templates/header.php'; ?>

<main class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow border-0">
      <div class="card-header card-header-dark">
          <h3 class="mb-0">Daftar Akun Baru</h3>
        </div>
        <div class="card-body">

          <?php if (isset($errors['global'])): ?>
            <div class="alert alert-danger"><?= $errors['global'] ?></div>
          <?php endif; ?>

          <form method="POST" action="../backend/auth/register_process.php">
            <!-- Username -->
            <div class="mb-3">
              <label class="form-label">Username *</label>
              <input type="text" name="username"
                     class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
                     value="<?= htmlspecialchars($old['username'] ?? '') ?>">
              <?php if (isset($errors['username'])): ?>
                <div class="invalid-feedback"><?= $errors['username'] ?></div>
              <?php endif; ?>
            </div>

            <!-- Email -->
            <div class="mb-3">
              <label class="form-label">Email *</label>
              <input type="email" name="email"
                     class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                     value="<?= htmlspecialchars($old['email'] ?? '') ?>">
              <?php if (isset($errors['email'])): ?>
                <div class="invalid-feedback"><?= $errors['email'] ?></div>
              <?php endif; ?>
            </div>

            <!-- Password -->
            <div class="mb-3">
              <label class="form-label">Password *</label>
              <div class="input-group">
                <input type="password" name="password" id="password"
                       class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                  <i class="bi bi-eye-slash" id="icon-password"></i>
                </button>
              </div>
              <div class="form-text">Minimal 8 karakter, harus mengandung huruf besar, kecil, dan angka.</div>
              <?php if (isset($errors['password'])): ?>
                <div class="invalid-feedback d-block"><?= $errors['password'] ?></div>
              <?php endif; ?>
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-3">
              <label class="form-label">Konfirmasi Password *</label>
              <div class="input-group">
                <input type="password" name="confirm_password" id="confirm"
                       class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirm">
                  <i class="bi bi-eye-slash" id="icon-confirm"></i>
                </button>
              </div>
              <?php if (isset($errors['confirm_password'])): ?>
                <div class="invalid-feedback d-block"><?= $errors['confirm_password'] ?></div>
              <?php endif; ?>
            </div>

            <!-- Kota -->
            <div class="mb-3">
              <label class="form-label">Kota</label>
              <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($old['city'] ?? '') ?>">
            </div>

            <!-- Profesi -->
            <div class="mb-3">
              <label class="form-label">Profesi</label>
              <input type="text" name="profession" class="form-control" value="<?= htmlspecialchars($old['profession'] ?? '') ?>">
            </div>

            <!-- Bio -->
            <div class="mb-3">
              <label class="form-label">Bio</label>
              <textarea name="bio" class="form-control" rows="3"><?= htmlspecialchars($old['bio'] ?? '') ?></textarea>
            </div>

            <!-- Minat -->
            <div class="mb-3">
              <label class="form-label">Minat (maksimal 3)</label>
              <div class="d-flex flex-wrap gap-2" id="interests-container">
                <?php foreach ($interests as $index => $int): ?>
                  <?php
                    $selected = in_array($int['id'], $old['interests'] ?? []);
                    $checkboxId = 'interest_' . $index;
                  ?>
                  <input type="checkbox" class="btn-check interest-checkbox" name="interests[]" id="<?= $checkboxId ?>" value="<?= $int['id'] ?>" autocomplete="off" <?= $selected ? 'checked' : '' ?>>
                  <label class="btn btn-outline-primary" for="<?= $checkboxId ?>"><?= htmlspecialchars($int['name']) ?></label>
                <?php endforeach; ?>
              </div>
              <?php if (isset($errors['interests'])): ?>
                <div class="text-danger mt-1"><?= $errors['interests'] ?></div>
              <?php endif; ?>
            </div>

            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-outline-light">Daftar</button>
              <a href="login.php" class="btn btn-secondary">Sudah punya akun? Login</a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</main>

<!-- Script: Toggle password + minat -->
<script src="../js/toggle_password.js"></script>
<script>
  document.querySelectorAll('.interest-checkbox').forEach(cb => {
    cb.addEventListener('change', function () {
      const checked = document.querySelectorAll('.interest-checkbox:checked');
      if (checked.length > 3) {
        this.checked = false;
        alert('Maksimal hanya bisa memilih 3 minat.');
      }
    });
  });
</script>

<?php include '../templates/footer.php'; ?>
