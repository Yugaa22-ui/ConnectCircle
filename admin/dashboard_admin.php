<?php include '../backend/admin/dashboard_admin_data.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - ConnectCircle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Selamat datang, <?= htmlspecialchars($username) ?> (Admin/Moderator)</h4>
        </div>
        <div class="card-body">
            <p>Pilih menu administrasi berikut:</p>
            <div class="list-group">
                <a href="manage_interests.php" class="list-group-item list-group-item-action">🔧 Kelola Minat</a>
                <a href="manage_users.php" class="list-group-item list-group-item-action">👥 Kelola Pengguna</a>
                <a href="manage_roles.php" class="list-group-item list-group-item-action">🔐 Kelola Role Pengguna</a>
                <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#logoutModal" \>🚪 Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Modal Konfirmasi Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        Apakah kamu yakin ingin keluar dari ConnectCircle?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
        <a href="../backend/auth/logout.php" class="btn btn-danger">Ya, Logout</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>
