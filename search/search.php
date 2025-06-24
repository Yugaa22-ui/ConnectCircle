<?php
include '../backend/auth/auth_check.php';
include '../backend/search/search_process.php';

$embed = isset($_GET['embed']) && $_GET['embed'] == '1';
if (!$embed) include '../templates/header.php';
?>

<div class="container-fluid mt-3">
  <div class="card bg-dark border-secondary text-white shadow">
    <div class="card-header bg-secondary text-white">
      <h4 class="mb-0"><i class="bi bi-search me-2"></i> Cari Teman Berdasarkan Minat</h4>
    </div>
    <div class="card-body">
    <form method="GET" action="<?= $embed ? 'javascript:void(0)' : '' ?>" id="search-form">
        <?php if ($embed): ?>
          <input type="hidden" name="embed" value="1">
        <?php endif; ?>
        <div class="mb-3">
          <label class="form-label">Masukkan Kata Kunci Minat:</label>
          <input type="text" name="minat"
                 class="form-control bg-dark text-white border-secondary <?= !empty($search_error) ? 'is-invalid' : '' ?>"
                 value="<?= htmlspecialchars($search_term) ?>"
                 placeholder="Contoh: Pemrograman, Musik, Desain">
          <?php if (!empty($search_error)): ?>
            <div class="invalid-feedback"><?= htmlspecialchars($search_error) ?></div>
          <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-success">
            <i class="bi bi-search"></i> Cari
        </button>
      </form>

      <hr class="border-secondary">

      <div id="search-result">
        <?php if (isset($_GET['minat'])): ?>
          <h5 class="mt-3">Hasil untuk: <strong>"<?= htmlspecialchars($search_term) ?>"</strong></h5>

          <?php if ($total_matches > 0): ?>
            <p><strong><?= $total_matches ?></strong> pengguna ditemukan.</p>
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
            <div class="alert alert-warning mt-3">Tidak ada pengguna dengan minat tersebut.</div>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>

    <?php if (!$embed): ?>
      <div class="card-footer text-end">
        <a href="../user/dashboard_user.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php if (!$embed) include '../templates/footer.php'; ?>
