// File: info_post.php
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once '../includes/db.php';

$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

if (!$post_id) {
    http_response_code(400);
    echo "ID tidak valid.";
    exit;
}

// Ambil info utama pesan
$stmt = $conn->prepare("SELECT p.content, p.image_path, p.created_at, p.updated_at, u.username, u.profile_picture FROM posts p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo "Pesan tidak ditemukan.";
    exit;
}

$post = $result->fetch_assoc();
$stmt->close();

// Ambil siapa saja yang sudah melihat
$seen = [];
$seen_stmt = $conn->prepare("SELECT u.username, u.profile_picture, pv.viewed_at FROM post_views pv JOIN users u ON pv.user_id = u.id WHERE pv.post_id = ? ORDER BY pv.viewed_at ASC");
$seen_stmt->bind_param("i", $post_id);
$seen_stmt->execute();
$seen_result = $seen_stmt->get_result();
while ($row = $seen_result->fetch_assoc()) {
    $seen[] = $row;
}
$seen_stmt->close();

function format_time($datetime) {
    $date = date("d M Y", strtotime($datetime));
    $time = date("H:i", strtotime($datetime));
    $today = date("d M Y");
    return ($date === $today ? "Hari ini" : $date) . ", " . $time;
}
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
