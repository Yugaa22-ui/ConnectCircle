<?php include '../backend/user/dashboard_user_data.php'; ?>
<?php include '../templates/header.php'; ?>

<main class="container-fluid mt-4">
  <div class="row">
    <!-- Sidebar -->
    <nav class="col-lg-3 col-md-4 mb-3 d-none d-md-block" id="sidebar">
      <div class="card bg-dark border-secondary">
        <div class="card-header text-white bg-secondary d-flex justify-content-between align-items-center">
          <span class="fw-bold">Menu</span>
        </div>
        <div class="list-group list-group-flush">
          <a href="profile.php" class="list-group-item list-group-item-action bg-dark text-white" data-page="profile.php">
            <i class="bi bi-person-circle me-2"></i> Lihat Profil
          </a>
          <a href="../circle/create_circle.php" class="list-group-item list-group-item-action bg-dark text-white" data-page="../circle/create_circle.php">
            <i class="bi bi-plus-circle me-2"></i> Buat Circle
          </a>
          <a href="../circle/join_circle.php" class="list-group-item list-group-item-action bg-dark text-white" data-page="../circle/join_circle.php">
            <i class="bi bi-search me-2"></i> Gabung Circle
          </a>
          <a href="../circle/view_circle.php" class="list-group-item list-group-item-action bg-dark text-white" data-page="../circle/view_circle.php">
            <i class="bi bi-collection me-2"></i> Lihat Circle Saya
          </a>
          <a href="../search/search.php" class="list-group-item list-group-item-action bg-dark text-white" data-page="../search/search.php">
            <i class="bi bi-person-plus me-2"></i> Cari Teman Berdasarkan Minat
          </a>
          <a href="../friend/friend_request.php" class="list-group-item list-group-item-action bg-dark text-white" data-page="../friend/friend_request.php">
            <i class="bi bi-person-check me-2"></i> Permintaan Pertemanan
          </a>
          <a href="../friend/friend_list.php" class="list-group-item list-group-item-action bg-dark text-white" data-page="../friend/friend_list.php">
            <i class="bi bi-people-fill me-2"></i> Daftar Teman
          </a>
          <a href="#" class="list-group-item list-group-item-action bg-dark text-white" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
          </a>
        </div>
      </div>
    </nav>

    <!-- Burger button (mobile) -->
    <div class="d-block d-md-none text-end mb-3 px-3">
      <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
        <i class="bi bi-list"></i> Menu
      </button>
    </div>

    <!-- Offcanvas sidebar (mobile) -->
    <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="mobileSidebar">
      <div class="offcanvas-header border-bottom border-secondary">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body p-0">
        <div class="list-group list-group-flush">
          <!-- Duplicate of sidebar for mobile -->
          <a href="profile.php" class="list-group-item list-group-item-action bg-dark text-white" data-page="profile.php">
            <i class="bi bi-person-circle me-2"></i> Lihat Profil
          </a>
          <a href="../circle/create_circle.php" class="list-group-item list-group-item-action bg-dark text-white" data-page="../circle/create_circle.php">
            <i class="bi bi-plus-circle me-2"></i> Buat Circle
          </a>
          <a href="../circle/join_circle.php" class="list-group-item list-group-item-action bg-dark text-white" data-page="../circle/join_circle.php">
            <i class="bi bi-search me-2"></i> Gabung Circle
          </a>
          <a href="../circle/view_circle.php" class="list-group-item list-group-item-action bg-dark text-white" data-page="../circle/view_circle.php">
            <i class="bi bi-collection me-2"></i> Lihat Circle Saya
          </a>
          <a href="../search/search.php" class="list-group-item list-group-item-action bg-dark text-white" data-page="../search/search.php">
            <i class="bi bi-person-plus me-2"></i> Cari Teman Berdasarkan Minat
          </a>
          <a href="../friend/friend_request.php" class="list-group-item list-group-item-action bg-dark text-white" data-page="../friend/friend_request.php">
            <i class="bi bi-person-check me-2"></i> Permintaan Pertemanan
          </a>
          <a href="../friend/friend_list.php" class="list-group-item list-group-item-action bg-dark text-white" data-page="../friend/friend_list.php">
            <i class="bi bi-people-fill me-2"></i> Daftar Teman
          </a>
          <a href="#" class="list-group-item list-group-item-action bg-dark text-white" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
          </a>
        </div>
      </div>
    </div>

    <!-- Content Area -->
    <div class="col-lg-9 col-md-8" id="content-area">
      <div class="card bg-dark border-secondary text-white">
        <div class="card-body">
          <h4 class="fw-semibold mb-2">Selamat Datang, <?= htmlspecialchars($username) ?>!</h4>
          <p class="text-muted">Pilih menu yang tersedia.</p>
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

<script src="../js/dashboard_user.js"></script>
<?php include '../templates/footer.php'; ?>
