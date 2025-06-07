<?php include '../backend/admin/manage_roles_process.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Role Pengguna - ConnectCircle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h4>Kelola Role Pengguna</h4>
        </div>
        <div class="card-body">
            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role Sekarang</th>
                        <th>Ubah Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $users->fetch_assoc()): ?>
                        <tr>
                            <form method="POST" action="">
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['role']) ?></td>
                                <td class="d-flex gap-2">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <select name="role" class="form-select form-select-sm" required>
                                        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                        <option value="moderator" <?= $user['role'] === 'moderator' ? 'selected' : '' ?>>Moderator</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                </td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-end">
            <a href="dashboard_admin.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>
