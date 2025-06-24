<?php
$include_template = $_GET['embed'] ?? false;
if (!$include_template) include '../templates/header.php';

include '../backend/auth/auth_check.php';
include '../backend/circle/view_circle_data.php';
?>

<main class="container-fluid py-4 px-2 px-md-4">
  <?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-info"><?= htmlspecialchars($_GET['msg']) ?></div>
  <?php endif; ?>

  <div class="card bg-dark text-white border-secondary shadow">
    <div class="card-header bg-secondary d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
      <h5 class="mb-0"><i class="bi bi-collection me-2"></i> Circle Saya</h5>
      <form method="GET" class="d-flex flex-wrap align-items-center gap-2 search-form">
        <div class="input-group input-group-sm">
          <input type="text" name="search" class="form-control bg-dark text-white border-secondary" placeholder="Cari nama circle" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
          <button class="btn btn-outline-light" type="submit"><i class="bi bi-search"></i></button>
        </div>
      </form>
    </div>

    <div class="card-body" id="circle-results">
      <?php if (count($managed_circles) > 0): ?>
        <h6 class="text-info mb-2"><i class="bi bi-tools me-1"></i> Circle yang Kamu Kelola</h6>
        <div class="list-group mb-4">
          <?php foreach ($managed_circles as $circle): ?>
            <div class="list-group-item bg-dark text-white border-secondary">
              <h5 class="mb-1"><?= htmlspecialchars($circle['name']) ?></h5>
              <p class="mb-1"><?= nl2br(htmlspecialchars($circle['description'])) ?></p>
              <small class="text-muted"><i class="bi bi-people-fill me-1"></i><?= $circle['member_count'] ?> anggota</small>
              <a href="../circle/discussion_page.php?circle_id=<?= $circle['id'] ?>"class="btn btn-outline-success btn-sm mt-2">Masuk Diskusi</a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if (count($joined_circles) > 0): ?>
        <h6 class="text-primary mb-2"><i class="bi bi-people me-1"></i> Circle yang Kamu Ikuti</h6>
        <div class="list-group mb-4">
          <?php foreach ($joined_circles as $circle): ?>
            <div class="list-group-item bg-dark text-white border-secondary">
              <h5 class="mb-1"><?= htmlspecialchars($circle['name']) ?></h5>
              <p class="mb-1"><?= nl2br(htmlspecialchars($circle['description'])) ?></p>
              <small class="text-muted"><i class="bi bi-people-fill me-1"></i><?= $circle['member_count'] ?> anggota</small><br>
              <a href="../circle/discussion_page.php?circle_id=<?= $circle['id'] ?>" class="btn btn-outline-success btn-sm mt-2">Masuk Diskusi</a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($pending_requests)): ?>
        <h6 class="text-muted mb-2"><i class="bi bi-hourglass-split me-1"></i> Permintaan Gabung yang Menunggu</h6>
        <div class="list-group mb-4">
          <?php foreach ($pending_requests as $req): ?>
            <div class="list-group-item bg-dark text-white border-secondary d-flex justify-content-between flex-wrap align-items-start">
              <div>
                <h6 class="mb-1"><?= htmlspecialchars($req['name']) ?></h6>
                <p class="mb-1"><?= nl2br(htmlspecialchars($req['description'])) ?></p>
                <small class="text-muted">Menunggu Persetujuan</small>
              </div>
              <form method="POST" class="ms-3 mt-2">
                <input type="hidden" name="cancel_request_id" value="<?= $req['id'] ?>">
                <button type="submit" class="btn btn-sm btn-outline-danger">Batalkan</button>
              </form>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if (count($joined_circles) === 0 && count($managed_circles) === 0 && count($pending_requests) === 0): ?>
        <div class="alert alert-warning mt-4">
          Kamu belum bergabung di circle manapun.
          <br>
          <a href="create_circle.php" class="btn btn-sm btn-primary mt-2">Buat Circle Baru</a>
          <a href="join_circle.php" class="btn btn-sm btn-outline-primary mt-2">Gabung Circle</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</main>

<?php if (!$include_template) include '../templates/footer.php'; ?>
