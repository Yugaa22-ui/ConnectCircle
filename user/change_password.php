<?php
include '../backend/auth/auth_check.php';
include '../backend/user/change_password_process.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Ubah Password - ConnectCircle</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>.form-error { color: red; font-size: 0.9em; }</style>
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-warning text-dark">
      <h4>Ubah Password</h4>
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
          <label class="form-label">Password Lama *</label>
          <input type="password" name="old_password" class="form-control">
          <?php if (isset($errors['old_password'])): ?>
            <div class="form-error"><?= $errors['old_password'] ?></div>
          <?php endif; ?>
        </div>

        <!-- Password Baru -->
        <div class="mb-3">
          <label class="form-label">Password Baru *</label>
          <input type="password" name="new_password" class="form-control">
          <div class="form-text">Minimal 8 karakter, kombinasi huruf besar, kecil, dan angka.</div>
          <?php if (isset($errors['new_password'])): ?>
            <div class="form-error"><?= $errors['new_password'] ?></div>
          <?php endif; ?>
        </div>

        <!-- Konfirmasi Password Baru -->
        <div class="mb-3">
          <label class="form-label">Konfirmasi Password Baru *</label>
          <input type="password" name="confirm_password" class="form-control">
          <?php if (isset($errors['confirm_password'])): ?>
            <div class="form-error"><?= $errors['confirm_password'] ?></div>
          <?php endif; ?>
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">Simpan Password Baru</button>
          <a href="profile.php" class="btn btn-secondary">Kembali</a>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>
