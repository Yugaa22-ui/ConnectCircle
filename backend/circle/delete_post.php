<?php
session_start();
include '../includes/db.php';

$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
$user_id = $_SESSION['user_id'];

if ($post_id === 0) {
    echo 'Invalid';
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
    echo 'Unauthorized';
    exit;
}

// Soft delete
$del = $conn->prepare("UPDATE posts SET deleted = 1 WHERE id = ?");
$del->bind_param("i", $post_id);
if ($del->execute()) {
    echo 'success';
} else {
    echo 'error';
}
$del->close();
?>
