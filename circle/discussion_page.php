<?php include '../backend/auth/auth_check.php'; ?>
<?php include '../backend/circle/discussion_controller.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Diskusi - <?= htmlspecialchars($circle_name) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Diskusi: <?= htmlspecialchars($circle_name) ?></h4>
        <div>
            <?php if ($is_creator): ?>
                <a href="manage_members.php?circle_id=<?= $circle_id ?>" class="btn btn-sm btn-outline-primary">Kelola Anggota</a>
            <?php endif; ?>
            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#circleInfoModal">ℹ️ Info Circle</button>
            <button class="btn btn-sm btn-outline-danger" onclick="confirmLeave(<?= $circle_id ?>)">Keluar Circle</button>
            <a href="view_circle.php" class="btn btn-sm btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body" style="max-height: 350px; overflow-y: auto;">
            <?php if ($results->num_rows > 0): ?>
                <?php while ($row = $results->fetch_assoc()): ?>
                    <div class="mb-3">
                        <strong><?= htmlspecialchars($row['username']) ?></strong><br>
                        <?= nl2br(htmlspecialchars($row['content'])) ?>
                        <?php if (!empty($row['image_path'])): ?>
                            <div class="mt-2">
                                <img src="../assets/uploads/img/<?= htmlspecialchars($row['image_path']) ?>" width="150" class="img-thumbnail">
                            </div>
                        <?php endif; ?>
                        <div><small class="text-muted"><?= $row['created_at'] ?></small></div>
                    </div>
                    <hr>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-muted">Belum ada pesan. Jadilah yang pertama!</p>
            <?php endif; ?>
        </div>
    </div>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <textarea name="message" class="form-control" rows="3" placeholder="Tulis pesan..."></textarea>
        </div>
        <div class="mb-3">
            <label>Gambar (opsional):</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-success">Kirim</button>
    </form>
</div>

<!-- Modal Info Circle -->
<div class="modal fade" id="circleInfoModal" tabindex="-1" aria-labelledby="circleInfoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="circleInfoLabel">Info Circle: <?= htmlspecialchars($circle_detail['name']) ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <p><strong>Deskripsi:</strong><br><?= nl2br(htmlspecialchars($circle_detail['description'])) ?></p>

        <p><strong>Dibuat oleh:</strong></p>
        <div class="d-flex align-items-center mb-3">
            <img src="<?= $circle_detail['creator_photo'] ? '../assets/uploads/img/' . $circle_detail['creator_photo'] : '../assets/img/default.png' ?>" class="rounded-circle me-2" width="40" height="40">
            <?= htmlspecialchars($circle_detail['creator_name']) ?>
        </div>

        <hr>
        <p><strong>Anggota:</strong></p>
        <ul class="list-unstyled row">
            <?php foreach ($circle_detail['members'] as $member): ?>
                <li class="col-md-4 d-flex align-items-center mb-2">
                    <img src="<?= $member['profile_picture'] ? '../assets/uploads/img/' . $member['profile_picture'] : '../assets/img/default.png' ?>" class="rounded-circle me-2" width="40" height="40">
                    <?= htmlspecialchars($member['username']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmLeave(circleId) {
    if (confirm("Yakin ingin keluar dari circle ini?")) {
        window.location.href = "discussion_page.php?circle_id=" + circleId + "&leave=yes";
    }
}
</script>
</body>
</html>
