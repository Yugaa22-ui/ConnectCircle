<?php
include '../backend/auth/auth_check.php';
include '../includes/db.php';

// Batasi akses admin
if ($_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses hanya untuk admin.'); window.location='../admin/dashboard_admin.php';</script>";
    exit;
}

// Ambil semua pengguna
$users = $conn->query("SELECT id, username, email, role FROM users ORDER BY username ASC");
?>

<div class="container-fluid py-3">
  <div class="card bg-dark text-white shadow-sm">
    <div class="card-header border-secondary d-flex justify-content-between align-items-center">
      <h4 class="mb-0">
        <i class="bi bi-shield-lock me-2"></i>Kelola Role Pengguna
      </h4>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-dark table-striped table-hover align-middle mb-0">
          <thead class="table-secondary text-dark">
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
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td>
                  <form class="form-role d-flex flex-wrap gap-2 mb-0" data-id="<?= $user['id'] ?>">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <select name="role" class="form-select form-select-sm bg-dark text-white border-secondary" required>
                      <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                      <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                      <option value="moderator" <?= $user['role'] === 'moderator' ? 'selected' : '' ?>>Moderator</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">
                      <i class="bi bi-save"></i>
                    </button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
