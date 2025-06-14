<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once '../../includes/db.php';

$user_id = $_SESSION['user_id'];
$circle_id = isset($_POST['circle_id']) ? intval($_POST['circle_id']) : 0;
$request_id = isset($_POST['request_id']) ? intval($_POST['request_id']) : 0;
$request_user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
$action = $_POST['action'] ?? '';

if (!$circle_id || !$request_id || !$request_user_id || !in_array($action, ['approve', 'reject'])) {
    header("Location: ../../circle/discussion_page.php?circle_id=$circle_id&msg=Data tidak valid");
    exit;
}

// Validasi: hanya creator/moderator circle yang bisa akses
$auth = $conn->prepare("SELECT cm.role, c.creator_id FROM circle_members cm JOIN circles c ON c.id = cm.circle_id WHERE cm.user_id = ? AND cm.circle_id = ?");
$auth->bind_param("ii", $user_id, $circle_id);
$auth->execute();
$auth->bind_result($role, $creator_id);
$auth->fetch();
$auth->close();

if (!in_array($role, ['moderator']) && $creator_id != $user_id) {
    header("Location: ../../circle/discussion_page.php?circle_id=$circle_id&msg=Tidak diizinkan");
    exit;
}

if ($action === 'approve') {
    // Tambahkan ke anggota
    $join = $conn->prepare("INSERT IGNORE INTO circle_members (user_id, circle_id) VALUES (?, ?)");
    $join->bind_param("ii", $request_user_id, $circle_id);
    $join->execute();
    $join->close();

    // Update status permintaan
    $update = $conn->prepare("UPDATE circle_requests SET status = 'accepted' WHERE id = ?");
    $update->bind_param("i", $request_id);
    $update->execute();
    $update->close();

    header("Location: ../../circle/circle_requests.php?circle_id=$circle_id&msg=Permintaan disetujui");
    exit;
}

if ($action === 'reject') {
    // Update status menjadi rejected
    $update = $conn->prepare("UPDATE circle_requests SET status = 'rejected' WHERE id = ?");
    $update->bind_param("i", $request_id);
    $update->execute();
    $update->close();

    header("Location: ../../circle/circle_requests.php?circle_id=$circle_id&msg=Permintaan ditolak");
    exit;
}
