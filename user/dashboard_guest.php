<?php
include '../backend/user/dahboard_guest_data.php';
include '../templates/header.php';
?>

<main class="container py-5">
  <h1 class="text-center mb-4">Selamat Datang di ConnectCircle <i></i></h1>
  <p class="text-center text-muted mb-5">
    Kamu sedang mengakses sebagai <strong>Guest</strong>. Untuk bergabung ke circle dan berdiskusi, silakan login atau daftar dulu.
  </p>

  <!-- Circle Terbaru -->
  <h4><i class="bi bi-arrow-repeat"></i> Circle Terbaru</h4>
  <div class="list-group mb-5">
    <?php if ($circles->num_rows > 0): ?>
      <?php while ($c = $circles->fetch_assoc()): ?>
        <div class="list-group-item bg-dark text-white border-secondary">
          <h5><?= htmlspecialchars($c['name']) ?></h5>
          <p><?= nl2br(htmlspecialchars($c['description'])) ?></p>
          <small class="text-muted">Dibuat pada: <?= date("d M Y", strtotime($c['created_at'])) ?></small>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-muted">Belum ada circle yang tersedia.</p>
    <?php endif; ?>
  </div>

  <!-- Pengguna Aktif -->
  <h4><i class="bi bi-fire"></i> Pengguna Aktif</h4>
  <ul class="list-group">
    <?php while ($u = $users->fetch_assoc()): ?>
      <li class="list-group-item bg-dark text-white border-secondary">
        <strong><?= htmlspecialchars($u['username']) ?></strong>
        (<?= htmlspecialchars($u['profession']) ?> dari <?= htmlspecialchars($u['city']) ?>)<br>
        <small class="text-muted">Posting: <?= $u['total_post'] ?> diskusi</small>
      </li>
    <?php endwhile; ?>
  </ul>

  <!-- CTA -->
  <div class="text-center mt-5">
    <a href="../auth/login.php" class="btn btn-outline-light btn-lg">
      <i class="bi bi-box-arrow-in-right"></i> Login untuk Bergabung
    </a>
  </div>
</main>

<?php include '../templates/footer.php'; ?>
