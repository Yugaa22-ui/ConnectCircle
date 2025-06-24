<?php
include __DIR__ . '/../auth/auth_check.php';
include __DIR__ . '/../../includes/db.php';

if (!isset($_GET['circle_id']) || !is_numeric($_GET['circle_id'])) {
    echo "<script>alert('Circle tidak ditemukan'); window.location='../../circle/view_circle.php';</script>";
    exit;
}

$circle_id = intval($_GET['circle_id']);
$user_id = $_SESSION['user_id'];

// Cek role user
$auth_check = $conn->prepare("SELECT cm.role, c.creator_id FROM circle_members cm
    JOIN circles c ON c.id = cm.circle_id
    WHERE cm.circle_id = ? AND cm.user_id = ?");
$auth_check->bind_param("ii", $circle_id, $user_id);
$auth_check->execute();
$auth_check->bind_result($role, $creator_id);
if (!$auth_check->fetch() || (!in_array($role, ['moderator']) && $creator_id != $user_id)) {
    echo "<script>alert('Kamu tidak memiliki akses ke halaman ini.'); window.location='../../circle/discussion_page.php?circle_id=$circle_id';</script>";
    exit;
}
$auth_check->close();

// Ambil permintaan pending
$req_stmt = $conn->prepare("SELECT cr.id, u.username, u.profile_picture, cr.created_at, cr.user_id
    FROM circle_requests cr
    JOIN users u ON cr.user_id = u.id
    WHERE cr.circle_id = ? AND cr.status = 'pending'
    ORDER BY cr.created_at ASC");
$req_stmt->bind_param("i", $circle_id);
$req_stmt->execute();
$requests = $req_stmt->get_result();
