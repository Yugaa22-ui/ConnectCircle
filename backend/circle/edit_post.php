<?php
session_start();
header('Content-Type: application/json');

include __DIR__ . '/../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
$new_content = trim($_POST['new_content'] ?? '');

// Validasi
if ($post_id <= 0 || $new_content === '') {
    echo json_encode(['status' => 'invalid']);
    exit;
}

// Pastikan user adalah pemilik post
$check = $conn->prepare("SELECT user_id FROM posts WHERE id = ?");
$check->bind_param("i", $post_id);
$check->execute();
$check->bind_result($owner_id);
$check->fetch();
$check->close();

if ($owner_id != $user_id) {
    echo json_encode(['status' => 'unauthorized']);
    exit;
}

// Update isi post
$update = $conn->prepare("UPDATE posts SET content = ?, updated_at = NOW() WHERE id = ?");
$update->bind_param("si", $new_content, $post_id);

if ($update->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}

$update->close();
?>
