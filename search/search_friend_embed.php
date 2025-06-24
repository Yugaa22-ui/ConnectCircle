<?php
include '../backend/auth/auth_check.php';
include '../backend/search/search_process.php';

header('Content-Type: text/html; charset=UTF-8');

if (!isset($_GET['minat']) || trim($_GET['minat']) === '') {
  echo '<div class="alert alert-danger">Masukkan kata kunci terlebih dahulu.</div>';
  exit;
}

$search_term = trim($_GET['minat']);

if ($total_matches > 0): ?>
  <p><strong><?= $total_matches ?></strong> pengguna ditemukan untuk "<strong><?= htmlspecialchars($search_term) ?></strong>".</p>
  <div class="list-group">
    <?php while ($row = $results->fetch_assoc()): ?>
      <div class="list-group-item bg-dark text-white border-secondary d-flex justify-content-between align-items-center">
        <div>
          <h6 class="mb-1"><?= htmlspecialchars($row['username']) ?></h6>
          <p class="mb-0"><?= htmlspecialchars($row['profession']) ?> dari <?= htmlspecialchars($row['city']) ?></p>
          <small class="text-muted">Minat: <?= htmlspecialchars($row['interest']) ?></small>
        </div>
        <div id="friend-btn-<?= $row['id'] ?>">
          <?php
            $target_id = $row['id'];
            $status = getFriendStatus($conn, $_SESSION['user_id'], $target_id);

            if ($status === 'none' || $status === 'rejected') {
              echo '<button type="button" class="btn btn-sm btn-outline-primary" onclick="sendFriendRequest(' . $target_id . ')"><i class="bi bi-person-plus"></i> Tambah Teman</button>';
            } elseif ($status === 'pending') {
              echo '<span class="badge bg-warning text-dark">Menunggu konfirmasi</span>';
            } elseif ($status === 'friends') {
              echo '<span class="badge bg-success">Sudah berteman</span>';
            }
          ?>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
<?php else: ?>
  <div class="alert alert-warning">Tidak ada pengguna dengan minat tersebut.</div>
<?php endif; ?>
