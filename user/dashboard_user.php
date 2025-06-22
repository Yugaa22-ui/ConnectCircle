<?php include '../templates/header.php'; ?>

<main class="d-flex min-vh-100">
  <!-- Sidebar dengan toggle -->
  <aside id="sidebar" class="bg-black text-white p-3 d-flex flex-column" style="width: 250px; min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold m-0">ConnectCircle</h4>
      <button id="toggleSidebar" class="btn btn-sm btn-outline-light d-lg-none">
        <i class="bi bi-list"></i>
      </button>
    </div>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a href="#" class="nav-link text-white" data-page="profile.php">
          <i class="bi bi-person-circle me-2"></i> Lihat Profil
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link text-white" data-page="../circle/create_circle.php">
          <i class="bi bi-plus-circle me-2"></i> Buat Circle
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link text-white" data-page="../circle/join_circle.php">
          <i class="bi bi-search me-2"></i> Gabung Circle
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link text-white" data-page="../circle/view_circle.php">
          <i class="bi bi-collection me-2"></i> Lihat Circle Saya
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link text-white" data-page="../search/search.php">
          <i class="bi bi-person-plus me-2"></i> Cari Teman Berdasarkan Minat
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link text-white" data-page="../friend/friend_request.php">
          <i class="bi bi-person-check me-2"></i> Permintaan Pertemanan
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link text-white" data-page="../friend/friend_list.php">
          <i class="bi bi-people-fill me-2"></i> Daftar Teman
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link text-white" data-bs-toggle="modal" data-bs-target="#logoutModal">
          <i class="bi bi-box-arrow-right me-2"></i> Logout
        </a>
      </li>
    </ul>
  </aside>

  <!-- Konten -->
  <section class="flex-grow-1 p-4" id="content-area">
    <div class="text-center text-muted">
      <h5 class="fw-light">Pilih menu yang tersedia</h5>
    </div>
  </section>
</main>

<!-- Modal Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
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

<script src="../js/dashboard_user.js"></script>
<?php include '../templates/footer.php'; ?>
