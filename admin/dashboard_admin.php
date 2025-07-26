<?php
include '../backend/admin/dashboard_stats.php';
include '../templates/header.php';
?>

<div id="notification-container" class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055;"></div>

<main class="container-fluid mt-4">
  <div class="row">
    <!-- Sidebar Desktop -->
    <nav class="col-lg-3 col-md-4 mb-3 d-none d-md-block" id="sidebar">
      <div class="card bg-dark border-secondary">
        <div class="card-header text-white bg-secondary d-flex justify-content-between align-items-center">
          <span class="fw-bold">Menu Admin</span>
        </div>
        <div class="list-group list-group-flush">
          <a href="#" class="list-group-item list-group-item-action bg-dark text-white sidebar-link active" data-page="dashboard_home.php">
            <i class="bi bi-house me-2"></i> Dashboard
          </a>
          <a href="#" class="list-group-item list-group-item-action bg-dark text-white sidebar-link" data-page="manage_interests.php">
            <i class="bi bi-sliders me-2"></i> Kelola Minat
          </a>
          <a href="#" class="list-group-item list-group-item-action bg-dark text-white sidebar-link" data-page="manage_users.php">
            <i class="bi bi-people me-2"></i> Kelola Pengguna
          </a>
          <a href="#" class="list-group-item list-group-item-action bg-dark text-white sidebar-link" data-page="manage_roles.php">
            <i class="bi bi-shield-lock me-2"></i> Kelola Role
          </a>
          <a href="#" class="list-group-item list-group-item-action bg-dark text-danger sidebar-link" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
          </a>
        </div>
      </div>
    </nav>

    <!-- Burger Button (Mobile) -->
    <div class="d-block d-md-none text-end mb-3 px-3">
      <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
        <i class="bi bi-list"></i> Menu
      </button>
    </div>

    <!-- Sidebar Mobile -->
    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="mobileSidebar">
      <div class="offcanvas-header border-bottom border-secondary">
        <h5 class="offcanvas-title">Menu Admin</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body p-0">
        <div class="list-group list-group-flush">
          <a href="#" class="list-group-item list-group-item-action bg-dark text-white sidebar-link" data-page="dashboard_home.php">
            <i class="bi bi-house me-2"></i> Dashboard
          </a>
          <a href="#" class="list-group-item list-group-item-action bg-dark text-white sidebar-link" data-page="manage_interests.php">
            <i class="bi bi-sliders me-2"></i> Kelola Minat
          </a>
          <a href="#" class="list-group-item list-group-item-action bg-dark text-white sidebar-link" data-page="manage_users.php">
            <i class="bi bi-people me-2"></i> Kelola Pengguna
          </a>
          <a href="#" class="list-group-item list-group-item-action bg-dark text-white sidebar-link" data-page="manage_roles.php">
            <i class="bi bi-shield-lock me-2"></i> Kelola Role
          </a>
          <a href="#" class="list-group-item list-group-item-action bg-dark text-danger sidebar-link" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
          </a>
        </div>
      </div>
    </div>

    <!-- Konten -->
    <div class="col-lg-9 col-md-8" id="admin-content">
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

<!-- Modal Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark border-secondary text-white">
      <div class="modal-header border-bottom">
        <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">Yakin ingin keluar dari ConnectCircle?</div>
      <div class="modal-footer border-top">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <a href="../backend/auth/logout.php" class="btn btn-danger">Ya, Logout</a>
      </div>
    </div>
  </div>
</div>

<script src="../js/admin_dashboard.js"></script>

<?php include '../templates/footer.php'; ?>

