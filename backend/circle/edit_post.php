<?php
session_start();
include '../includes/db.php';

$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
$new_content = trim($_POST['content'] ?? '');

if ($post_id === 0 || $new_content === '') {
    echo 'Invalid input';
    exit;
}

// Pastikan user adalah pemilik post
$user_id = $_SESSION['user_id'];
$check = $conn->prepare("SELECT user_id FROM posts WHERE id = ?");
$check->bind_param("i", $post_id);
$check->execute();
$check->bind_result($owner_id);
$check->fetch();
$check->close();

if ($owner_id != $user_id) {
    echo 'Unauthorized';
    exit;
}

// Update isi post
$update = $conn->prepare("UPDATE posts SET content = ?, updated_at = NOW() WHERE id = ?");
$update->bind_param("si", $new_content, $post_id);
if ($update->execute()) {
    echo 'success';
} else {
    echo 'error';
}
$update->close();
?>
