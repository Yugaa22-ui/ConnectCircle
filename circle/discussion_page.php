<?php include '../backend/auth/auth_check.php'; ?>
<?php include '../backend/circle/discussion_controller.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Diskusi - <?= htmlspecialchars($circle_name) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap & Icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .context-menu {
            position: absolute;
            z-index: 10000;
            display: none;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
        }
        .context-menu li {
            padding: 6px 12px;
            list-style: none;
            cursor: pointer;
        }
        .context-menu li:hover {
            background-color: #eee;
        }
    </style>
</head>
<body class="bg-light">

<!-- Notifikasi -->
<?php if (isset($_GET['msg'])): ?>
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
  <div class="toast show text-white bg-success border-0" role="alert">
    <div class="d-flex">
      <div class="toast-body"><?= htmlspecialchars($_GET['msg']) ?></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="container mt-4">
    <!-- Header Circle -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Diskusi: <?= htmlspecialchars($circle_name) ?></h4>
        <div class="d-flex gap-2">
            <?php if ($is_creator): ?>
                <form method="POST" action="">
                    <input type="hidden" name="toggle_visibility" value="1">
                    <input type="hidden" name="circle_id" value="<?= $circle_id ?>">
                    <button class="btn btn-sm btn-outline-<?= isset($is_private) && $is_private ? 'danger' : 'success' ?>" type="submit">
                        <?= $is_private ? 'Ubah ke Public' : 'Ubah ke Private' ?>
                    </button>
                </form>
                <a href="circle_requests.php?circle_id=<?= $circle_id ?>" class="btn btn-sm btn-outline-warning">Lihat Permintaan</a>
            <?php endif; ?>
            <a href="manage_circle.php?circle_id=<?= $circle_id ?>" class="btn btn-sm btn-outline-primary">Kelola Circle</a>
            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#circleInfoModal">Lihat Info Circle</button>
            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmLeaveModal">Keluar Circle</button>
            <a href="view_circle.php" class="btn btn-sm btn-secondary">Kembali</a>
        </div>
    </div>

    <!-- Pesan Diskusi -->
    <div class="card mb-3">
        <div class="card-body" id="message-container" style="max-height: 350px; overflow-y: auto;">
            <?php if ($results->num_rows > 0): ?>
                <?php while ($row = $results->fetch_assoc()): ?>
                    <?php if (!$row['deleted']): ?>
                    <div class="mb-3 message-item" 
                         data-id="<?= $row['id'] ?>" 
                         data-content="<?= htmlspecialchars($row['content']) ?>" 
                         data-created="<?= $row['created_at'] ?>" 
                         data-updated="<?= $row['updated_at'] ?>" 
                         data-username="<?= htmlspecialchars($row['username']) ?>">
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
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-muted">Belum ada pesan. Jadilah yang pertama!</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Form Kirim Pesan -->
    <?php if ($is_muted): ?>
        <div class="alert alert-warning"><?= $mute_message ?></div>
    <?php else: ?>
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
    <?php endif; ?>
</div>

<!-- Modal Info Circle -->
<?php include 'modal_info_circle.php'; ?>

<!-- Modal Keluar Circle -->
<?php include 'modal_keluar_circle.php'; ?>

<!-- Modal Edit Pesan -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="edit_post.php">
        <div class="modal-header">
          <h5 class="modal-title">Edit Pesan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <textarea name="new_content" id="editContent" class="form-control" rows="4"></textarea>
          <input type="hidden" name="post_id" id="editPostId">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Info Pesan -->
<div class="modal fade" id="infoModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Info Pesan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Oleh:</strong> <span id="infoUser"></span></p>
        <p><strong>Dibuat:</strong> <span id="infoCreated"></span></p>
        <p><strong>Terakhir Diedit:</strong> <span id="infoUpdated"></span></p>
        <p><strong>Sudah Dilihat Oleh:</strong> <span id="infoSeenBy">-</span></p>
      </div>
    </div>
  </div>
</div>

<!-- Context Menu -->
<ul class="context-menu" id="contextMenu">
    <li id="editBtn">✏️ Edit</li>
    <li id="deleteBtn">🗑️ Hapus</li>
    <li id="infoBtn">ℹ️ Info</li>
</ul>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/discussion.js"></script>
</body>
</html>
