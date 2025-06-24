<?php
require __DIR__ . '/../templates/header.php'; // pastikan header mengatur tema gelap
require __DIR__ . '/../backend/circle/circle_requests_process.php';
?>

<div class="container my-5">
  <div class="card bg-dark text-light border border-secondary shadow">
    <div class="card-header d-flex justify-content-between align-items-center border-bottom border-secondary">
      <h5 class="mb-0">Permintaan Gabung Circle (<?= $requests->num_rows ?>)</h5>
      <a href="discussion_page.php?circle_id=<?= $circle_id ?>" class="btn btn-outline-light btn-sm">
        <i class="bi bi-arrow-left-circle"></i> Kembali
      </a>
    </div>

    <div class="card-body">
      <?php if ($requests->num_rows > 0): ?>
        <ul class="list-group list-group-flush">
          <?php while ($row = $requests->fetch_assoc()): ?>
            <li class="list-group-item bg-dark text-light d-flex justify-content-between align-items-center border-secondary">
              <div class="d-flex align-items-center">
                <img src="<?= $row['profile_picture'] ? '../assets/uploads/img/' . htmlspecialchars($row['profile_picture']) : '../assets/img/default.png' ?>"
                     class="rounded-circle me-3 border border-secondary" width="40" height="40" alt="User">
                <div>
                  <div class="fw-semibold"><?= htmlspecialchars($row['username']) ?></div>
                  <small class="text-muted"><?= date('d M Y H:i', strtotime($row['created_at'])) ?></small>
                </div>
              </div>
              <form method="POST" action="../backend/circle/handle_request.php" class="d-flex gap-2">
                <input type="hidden" name="circle_id" value="<?= $circle_id ?>">
                <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                <button name="action" value="approve" class="btn btn-success btn-sm" title="Setujui" onclick="return confirm('Terima permintaan ini?')">
                  <i class="bi bi-check-circle"></i>
                </button>
                <button name="action" value="reject" class="btn btn-danger btn-sm" title="Tolak" onclick="return confirm('Tolak permintaan ini?')">
                  <i class="bi bi-x-circle"></i>
                </button>
              </form>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <div class="alert alert-info text-center text-dark bg-light">Tidak ada permintaan bergabung saat ini.</div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>
