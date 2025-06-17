<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once '../includes/db.php';

function get_post_info($conn, $post_id) {
    $stmt = $conn->prepare("SELECT p.content, p.image_path, p.created_at, p.updated_at, u.username, u.profile_picture FROM posts p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $stmt->close();
    return $post;
}

function get_post_views($conn, $post_id) {
    $seen = [];
    $seen_stmt = $conn->prepare("SELECT u.username, u.profile_picture, pv.viewed_at FROM post_views pv JOIN users u ON pv.user_id = u.id WHERE pv.post_id = ? ORDER BY pv.viewed_at ASC");
    $seen_stmt->bind_param("i", $post_id);
    $seen_stmt->execute();
    $result = $seen_stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $seen[] = $row;
    }
    $seen_stmt->close();
    return $seen;
}

function format_time($datetime) {
    $date = date("d M Y", strtotime($datetime));
    $time = date("H:i", strtotime($datetime));
    $today = date("d M Y");
    return ($date === $today ? "Hari ini" : $date) . ", " . $time;
}
