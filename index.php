<?php include 'templates/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section text-center py-5">
  <div class="container">
    <h1 class="display-4 fw-bold text-white">Temukan Teman Sejalan</h1>
    <p class="lead text-white">
      ConnectCircle membantumu menemukan kolaborator berdasarkan minat yang sama.<br>
      Buat circle, berdiskusi, dan bangun komunitas yang produktif.
    </p>

    <div class="mt-4">
      <a href="auth/login.php" class="btn btn-outline-light btn-lg me-2">
        <i class="bi bi-box-arrow-in-right"></i> Login
      </a>
      <a href="auth/register.php" class="btn btn-outline-light btn-lg me-2">
        <i class="bi bi-person-plus"></i> Daftar
      </a>
      <a href="user/dashboard_guest.php" class="btn btn-secondary btn-lg">
        <i class="bi bi-eye"></i> Masuk sebagai Guest
      </a>
    </div>
  </div>
</section>

<?php include 'templates/footer.php'; ?>
