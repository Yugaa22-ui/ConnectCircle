<?php
include '../backend/admin/dashboard_stats.php';
?>

<?php include '../templates/header.php'; ?>

<main class="container-fluid py-4">
  <div class="row">
    <!-- Sidebar -->
    <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
      <div class="position-sticky pt-3 sidebar-sticky">
        <ul class="nav flex-column text-white">
          <li class="nav-item">
            <a class="nav-link text-white" href="#" data-page="dashboard_home.php">
              <i class="bi bi-house me-2"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="#" data-page="manage_interests.php">
              <i class="bi bi-sliders me-2"></i> Kelola Minat
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="#" data-page="manage_users.php">
              <i class="bi bi-people me-2"></i> Kelola Pengguna
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="#" data-page="manage_roles.php">
              <i class="bi bi-shield-lock me-2"></i> Kelola Role
            </a>
          </li>
          <li class="nav-item mt-3">
            <a class="nav-link text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
              <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <!-- Konten -->
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4" id="admin-content">
      <!-- Konten default (statistik) -->
      <div class="row g-3">
        <div class="col-md-6 col-lg-3">
          <div class="card bg-primary text-white shadow-sm">
            <div class="card-body">
              <h5><i class="bi bi-people-fill me-2"></i>Total Pengguna</h5>
              <h2><?= $totalUsers ?></h2>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card bg-success text-white shadow-sm">
            <div class="card-body">
              <h5><i class="bi bi-list-check me-2"></i>Total Minat</h5>
              <h2><?= $totalInterests ?></h2>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card bg-warning text-white shadow-sm">
            <div class="card-body">
              <h5><i class="bi bi-chat-dots me-2"></i>Total Circle</h5>
              <h2><?= $totalCircles ?></h2>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card bg-danger text-white shadow-sm">
            <div class="card-body">
              <h5><i class="bi bi-award me-2"></i>Total Badge</h5>
              <h2><?= $totalBadges ?></h2>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Modal Konfirmasi Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-secondary">
        <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Apakah kamu yakin ingin keluar dari ConnectCircle?
      </div>
      <div class="modal-footer border-secondary">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <a href="../backend/auth/logout.php" class="btn btn-danger">Ya, Logout</a>
      </div>
    </div>
  </div>
</div>

<?php include '../templates/footer.php'; ?>
