<?php
// === FILE: backend/circle/info_post_data.php ===
if (session_status() === PHP_SESSION_NONE) session_start();
include_once '../../includes/db.php';

$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
if ($post_id === 0) {
    die('ID tidak valid');
}

// Ambil info post
$stmt = $conn->prepare("SELECT p.content, p.created_at, p.updated_at, u.username FROM posts p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

// Ambil siapa saja yang sudah melihat
$views = [];
$view_stmt = $conn->prepare("SELECT u.username, v.viewed_at FROM post_views v JOIN users u ON v.user_id = u.id WHERE v.post_id = ? ORDER BY v.viewed_at ASC");
$view_stmt->bind_param("i", $post_id);
$view_stmt->execute();
$view_result = $view_stmt->get_result();
while ($row = $view_result->fetch_assoc()) {
    $views[] = $row;
}
$view_stmt->close();
