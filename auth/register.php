<?php
session_start();
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
include '../includes/db.php';

$interest_query = $conn->query("SELECT id, name FROM interests");
$interests = $interest_query->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar - ConnectCircle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap + Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .form-error { color: red; font-size: 0.9em; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Daftar Akun Baru</h3>
                </div>
                <div class="card-body">

                    <?php if (isset($errors['global'])): ?>
                        <div class="alert alert-danger"><?= $errors['global'] ?></div>
                    <?php endif; ?>

                    <form method="POST" action="../backend/auth/register_process.php">
                        <div class="mb-3">
                            <label class="form-label">Username *</label>
                            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($old['username'] ?? '') ?>">
                            <?php if (isset($errors['username'])): ?><div class="form-error"><?= $errors['username'] ?></div><?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                            <?php if (isset($errors['email'])): ?><div class="form-error"><?= $errors['email'] ?></div><?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password *</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                    <i class="bi bi-eye-slash" id="icon-password"></i>
                                </button>
                            </div>
                            <div class="form-text">Minimal 8 karakter, harus mengandung huruf besar, huruf kecil, dan angka.</div>
                            <?php if (isset($errors['password'])): ?><div class="form-error"><?= $errors['password'] ?></div><?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password *</label>
                            <div class="input-group">
                                <input type="password" id="confirm" name="confirm_password" class="form-control">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirm">
                                    <i class="bi bi-eye-slash" id="icon-confirm"></i>
                                </button>
                            </div>
                            <?php if (isset($errors['confirm_password'])): ?><div class="form-error"><?= $errors['confirm_password'] ?></div><?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kota</label>
                            <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($old['city'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Profesi</label>
                            <input type="text" name="profession" class="form-control" value="<?= htmlspecialchars($old['profession'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" class="form-control" rows="3"><?= htmlspecialchars($old['bio'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Minat</label>
                            <select name="interests[]" class="form-select" multiple>
                                <?php foreach ($interests as $int): ?>
                                    <option value="<?= $int['id'] ?>" <?= in_array($int['id'], $old['interests'] ?? []) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($int['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Daftar</button>
                            <a href="login.php" class="btn btn-outline-secondary">Sudah punya akun? Login</a>
                        </div>
                    </form>
                </div>
            </div>

            <p class="text-center text-muted mt-4">&copy; <?= date('Y') ?> ConnectCircle</p>
        </div>
    </div>
</div>

<!-- JS toggle icon password -->
<script>
document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', function () {
        const targetId = this.dataset.target;
        const input = document.getElementById(targetId);
        const icon = document.getElementById('icon-' + targetId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
