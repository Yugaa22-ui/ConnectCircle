<?php
include '../backend/admin/manage_interests_process.php';
?>

<div class="container-fluid py-3">
  <?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($success) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($error) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="card bg-dark text-white shadow-sm">
    <div class="card-header border-secondary">
      <h4 class="mb-0">
        <i class="bi bi-sliders me-2"></i>Kelola Minat Pengguna
      </h4>
    </div>
    <div class="card-body">
      <form id="interestForm" method="POST" class="mb-4">
        <label class="form-label">Tambah Minat Baru:</label>
        <div class="input-group">
          <input type="text" name="new_interest" class="form-control" placeholder="Contoh: Musik, Menulis, Desain" required>
          <button type="submit" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Tambah
          </button>
        </div>
      </form>

      <h5 class="border-bottom border-secondary pb-2">Daftar Minat Tersedia</h5>
      <?php if ($all && $all->num_rows > 0): ?>
        <ul class="list-group list-group-flush">
          <?php while ($row = $all->fetch_assoc()): ?>
            <li class="list-group-item bg-dark text-white d-flex justify-content-between align-items-center">
              <?= htmlspecialchars($row['name']) ?>
              <button class="btn btn-sm btn-danger btn-delete" data-id="<?= $row['id'] ?>">
                <i class="bi bi-trash"></i> Hapus
              </button>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p class="text-muted mt-3">Belum ada data minat.</p>
      <?php endif; ?>
    </div>
  </div>
</div>
