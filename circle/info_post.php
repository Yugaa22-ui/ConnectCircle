<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once '../backend/circle/info_post_data.php';
include_once '../includes/db.php';

$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

if (!$post_id) {
    http_response_code(400);
    echo "ID tidak valid.";
    exit;
}

$post = get_post_info($conn, $post_id);

if (!$post) {
    http_response_code(404);
    echo "Pesan tidak ditemukan.";
    exit;
}

$seen = get_post_views($conn, $post_id);
?>

<div class="p-3">
  <div class="d-flex align-items-center mb-3">
    <img src="<?= $post['profile_picture'] ? '../assets/uploads/img/' . $post['profile_picture'] : '../assets/img/default.png' ?>" class="rounded-circle me-2" width="50" height="50">
    <div>
      <strong><?= htmlspecialchars($post['username']) ?></strong><br>
      <small class="text-muted"><?= format_time($post['created_at']) ?></small>
    </div>
  </div>
  <div class="mb-3">
    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
    <?php if (!empty($post['image_path'])): ?>
      <img src="../assets/uploads/img/<?= htmlspecialchars($post['image_path']) ?>" width="150" class="img-thumbnail">
    <?php endif; ?>
  </div>
  <?php if ($post['updated_at'] && $post['updated_at'] !== $post['created_at']): ?>
    <div class="text-muted"><small>Diubah pada: <?= format_time($post['updated_at']) ?></small></div>
  <?php endif; ?>

  <?php if (!empty($seen)): ?>
    <hr>
    <h6 class="mt-3">Dilihat oleh:</h6>
    <ul class="list-unstyled">
      <?php foreach ($seen as $user): ?>
        <li class="d-flex align-items-center mb-2">
          <img src="<?= $user['profile_picture'] ? '../assets/uploads/img/' . $user['profile_picture'] : '../assets/img/default.png' ?>" class="rounded-circle me-2" width="35" height="35">
          <div>
            <strong><?= htmlspecialchars($user['username']) ?></strong><br>
            <small class="text-muted"><?= format_time($user['viewed_at']) ?></small>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <div class="text-muted"><em>Belum ada yang melihat pesan ini.</em></div>
  <?php endif; ?>
</div>
