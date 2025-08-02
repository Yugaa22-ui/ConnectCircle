<?php
$include_template = $_GET['embed'] ?? false;
if (!$include_template) include '../templates/header.php';

include '../backend/auth/auth_check.php';
include '../backend/circle/discussion_controller.php';
?>

<main class="container-fluid py-4 px-2 px-md-4">
  <?php if (isset($_GET['msg'])): ?>
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
      <div class="toast show text-white bg-success border-0" role="alert" id="snackbar">
        <div class="d-flex">
          <div class="toast-body"><?= htmlspecialchars($_GET['msg']) ?></div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
      </div>
    </div>
    <script>
      setTimeout(() => {
        const toast = document.getElementById('snackbar');
        if (toast) toast.style.display = 'none';
      }, 2000);
    </script>
  <?php endif; ?>

  <div class="card bg-dark text-white border-secondary shadow">
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
      <h5 class="mb-0"><i class="bi bi-chat-left-text me-2"></i> Diskusi: <?= htmlspecialchars($circle_name) ?></h5>
      <div class="d-flex flex-wrap gap-2">
        <?php if ($is_creator): ?>
          <a href="circle_requests.php?circle_id=<?= $circle_id ?>" class="btn btn-sm btn-outline-warning">Lihat Permintaan</a>
        <?php endif; ?>
        <?php if ($is_creator || $is_moderator): ?>
          <a href="manage_circle.php?circle_id=<?= $circle_id ?>" class="btn btn-sm btn-outline-primary">Kelola Circle</a>
        <?php endif; ?>
        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#circleInfoModal" data-circle-id="<?= (int)$_GET['circle_id'] ?>">Lihat Info Circle</button>
        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmLeaveModal">Keluar Circle</button>
        <a href="view_circle.php" class="btn btn-sm btn-secondary">Kembali</a>
      </div>
    </div>

    <div class="card-body p-3" id="message-container" style="max-height: 500px; overflow-y: auto;">
      <?php if ($results->num_rows > 0): ?>
        <?php while ($row = $results->fetch_assoc()): ?>
          <?php if (!$row['deleted']): ?>
            <?php
            $avatar = $row['profile_picture']
              ? '../assets/uploads/img/' . htmlspecialchars($row['profile_picture'])
              : '../assets/img/default.png';
            ?>
            <div class="d-flex align-items-start mb-3 message-item message-block"
              data-id="<?= $row['id'] ?>"
              data-post-id="<?= $row['id'] ?>"
              data-content="<?= htmlspecialchars($row['content']) ?>"
              data-created="<?= $row['created_at'] ?>"
              data-updated="<?= $row['updated_at'] ?>"
              data-username="<?= htmlspecialchars($row['username']) ?>">

              <img src="<?= $avatar ?>" class="rounded-circle me-2" width="40" height="40" alt="avatar">
              <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-center">
                  <strong><?= htmlspecialchars($row['username']) ?></strong>
                  <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-secondary btn-info-post" data-post-id="<?= $row['id'] ?>" title="Info"><i class="bi bi-info-circle"></i></button>
                    <?php if ($row['user_id'] == $_SESSION['user_id']): ?>
                      <button class="btn btn-outline-secondary btn-edit-post" data-id="<?= $row['id'] ?>" data-content="<?= htmlspecialchars($row['content']) ?>" title="Edit"><i class="bi bi-pencil"></i></button>
                      <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-post-id="<?= $row['id'] ?>"><i class="bi bi-trash"></i></button>
                    <?php endif; ?>
                  </div>
                </div>
                <div><?= nl2br(htmlspecialchars($row['content'])) ?></div>
                <?php if (!empty($row['image_path'])): ?>
                  <div class="mt-2">
                    <img src="../assets/uploads/img/<?= htmlspecialchars($row['image_path']) ?>" width="150" class="img-thumbnail">
                  </div>
                <?php endif; ?>
                <div><small class="text-muted"><?= $row['created_at'] ?></small></div>
              </div>
            </div>
            <hr>
          <?php endif; ?>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-muted">Belum ada pesan. Jadilah yang pertama!</p>
      <?php endif; ?>
    </div>

    <div class="card-footer bg-dark border-top border-secondary">
      <?php if ($is_muted): ?>
        <div class="alert alert-warning"><?= $mute_message ?></div>
      <?php else: ?>
        <div id="preview-image-container" class="mb-2" style="display: none;">
          <img id="preview-image" src="#" class="img-thumbnail" style="max-width: 150px;">
          <div id="cancel-image-wrapper" class="mt-2"></div>
        </div>
        <form method="POST" enctype="multipart/form-data" class="input-message-wrapper">
          <input type="hidden" name="circle_id" value="<?= $circle_id ?>">
          <div class="d-flex flex-column flex-md-row align-items-start gap-2">
            <label for="imageInput" class="btn btn-outline-secondary" id="uploadBtn" title="Unggah Gambar">
              <i class="bi bi-image"></i>
            </label>
            <textarea name="message" class="form-control" rows="1" placeholder="Tulis pesan..."></textarea>
            <button type="submit" name="submit_message" class="btn btn-success align-self-stretch">
              <i class="bi bi-send"></i>
            </button>
          </div>
          <input type="file" name="image" id="imageInput" accept="image/*" style="display: none;">
        </form>
      <?php endif; ?>
    </div>
  </div>
</main>

// Modal pada halaman diskusi
<?php if (file_exists(__DIR__ . '/modal_discussion_page/modal_info_circle.php')) include 'modal_discussion_page/modal_info_circle.php'; ?>
<?php if (file_exists(__DIR__ . '/modal_discussion_page/modal_keluar_circle.php')) include 'modal_discussion_page/modal_keluar_circle.php'; ?>
<?php if (file_exists(__DIR__ . '/modal_discussion_page/modal_edit.php')) include 'modal_discussion_page/modal_edit.php'; ?>
<?php if (file_exists(__DIR__ . '/modal_discussion_page/modal_konfirmasi_hapus.php')) include 'modal_discussion_page/modal_konfirmasi_hapus.php'; ?>
<?php if (file_exists(__DIR__ . '/modal_discussion_page/modal_info_pesan.php')) include 'modal_discussion_page/modal_info_pesan.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/discussion.js"></script>


<?php if (!$include_template) include '../templates/footer.php'; ?>
