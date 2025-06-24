<?php
require __DIR__ . '/../templates/header.php';
require __DIR__ . '/../backend/circle/circle_requests_process.php';

// Snackbar feedback (dari handle_request.php pakai ?status=success|error&msg=...)
$snackbar = null;
if (isset($_GET['status']) && isset($_GET['msg'])) {
  $snackbar = [
    'type' => $_GET['status'] === 'success' ? 'success' : 'danger',
    'msg' => htmlspecialchars($_GET['msg'])
  ];
}
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
              <div class="d-flex gap-2">
                <button type="button" class="btn btn-success btn-sm"
                        data-bs-toggle="modal" data-bs-target="#confirmModal"
                        data-username="<?= htmlspecialchars($row['username']) ?>"
                        data-action="approve"
                        data-request-id="<?= $row['id'] ?>"
                        data-user-id="<?= $row['user_id'] ?>">
                  <i class="bi bi-check-circle"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm"
                        data-bs-toggle="modal" data-bs-target="#confirmModal"
                        data-username="<?= htmlspecialchars($row['username']) ?>"
                        data-action="reject"
                        data-request-id="<?= $row['id'] ?>"
                        data-user-id="<?= $row['user_id'] ?>">
                  <i class="bi bi-x-circle"></i>
                </button>
              </div>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <div class="alert alert-info text-center text-dark bg-light">Tidak ada permintaan bergabung saat ini.</div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light border border-secondary shadow">
      <div class="modal-header border-bottom border-secondary">
        <h5 class="modal-title">Konfirmasi Aksi</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p id="confirmMessage">Yakin ingin melanjutkan aksi ini?</p>
      </div>
      <div class="modal-footer border-top border-secondary">
        <form method="POST" action="../backend/circle/handle_request.php" id="confirmForm">
          <input type="hidden" name="circle_id" value="<?= $circle_id ?>">
          <input type="hidden" name="request_id" id="confirmRequestId">
          <input type="hidden" name="user_id" id="confirmUserId">
          <input type="hidden" name="action" id="confirmAction">
          <button type="submit" class="btn btn-primary px-4">Ya, Lanjutkan</button>
          <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Batal</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Snackbar -->
<?php if ($snackbar): ?>
  <div id="snackbar" class="position-fixed bottom-0 end-0 m-4 z-3 alert alert-<?= $snackbar['type'] ?> shadow border border-light" role="alert">
    <?= $snackbar['msg'] ?>
  </div>
  <script>
    setTimeout(() => {
      const snackbar = document.getElementById('snackbar');
      if (snackbar) snackbar.remove();
    }, 4000);
  </script>
<?php endif; ?>

<script>
  const confirmModal = document.getElementById('confirmModal');
  confirmModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const username = button.getAttribute('data-username');
    const action = button.getAttribute('data-action');
    const requestId = button.getAttribute('data-request-id');
    const userId = button.getAttribute('data-user-id');

    const message = action === 'approve'
      ? `Yakin ingin <strong>menyetujui</strong> permintaan dari <strong>${username}</strong>?`
      : `Yakin ingin <strong>menolak</strong> permintaan dari <strong>${username}</strong>?`;

    document.getElementById('confirmMessage').innerHTML = message;
    document.getElementById('confirmRequestId').value = requestId;
    document.getElementById('confirmUserId').value = userId;
    document.getElementById('confirmAction').value = action;
  });
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>
