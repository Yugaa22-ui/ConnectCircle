<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - ConnectCircle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Login ke ConnectCircle</h3>
                </div>
                <div class="card-body">

                    <!-- Notifikasi -->
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
                    <?php elseif (isset($_GET['success'])): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="../backend/auth/login_process.php">
                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Login</button>
                            <a href="register.php" class="btn btn-outline-secondary">Belum punya akun? Daftar</a>
                        </div>
                    </form>

                </div>
            </div>

            <p class="text-center text-muted mt-4">&copy; <?= date('Y') ?> ConnectCircle</p>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
