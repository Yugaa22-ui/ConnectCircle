<?php
// === manage_circle_data.php ===
if (session_status() === PHP_SESSION_NONE) session_start();
include_once '../includes/db.php';

$circle_id = isset($_GET['circle_id']) ? intval($_GET['circle_id']) : 0;
$user_id = $_SESSION['user_id'];

$circle_name = $circle_description = $msg = '';
$circle_detail = [];

// Validasi kepemilikan circle
$cek = $conn->prepare("SELECT name, description, creator_id, rules FROM circles WHERE id = ?");
$cek->bind_param("i", $circle_id);
$cek->execute();
$cek->store_result();

if ($cek->num_rows === 0) {
    echo "<script>alert('Circle tidak ditemukan.'); window.location='view_circle.php';</script>";
    exit;
}

$cek->bind_result($circle_name, $circle_description, $creator_id, $rules);
$cek->fetch();
$cek->close();

if ($creator_id !== $user_id) {
    echo "<script>alert('Hanya pembuat circle yang dapat mengelola.'); window.location='view_circle.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['circle_name'], $_POST['circle_description'], $_POST['rules'])) {
        $new_name = trim($_POST['circle_name']);
        $new_desc = trim($_POST['circle_description']);
        $new_rules = trim($_POST['rules']);

        $update = $conn->prepare("UPDATE circles SET name = ?, description = ?, rules = ? WHERE id = ?");
        $update->bind_param("sssi", $new_name, $new_desc, $new_rules, $circle_id);
        if ($update->execute()) {
            $circle_name = $new_name;
            $circle_description = $new_desc;
            $rules = $new_rules;
            $msg = "Circle berhasil diperbarui.";
        }
        $update->close();
    }

    if (isset($_POST['action']) && $_POST['member_id']) {
        $member_id = intval($_POST['member_id']);
        $action = $_POST['action'];

        if ($action === 'kick') {
            $conn->query("DELETE FROM circle_members WHERE user_id = $member_id AND circle_id = $circle_id");
        } elseif ($action === 'mute') {
            $until = date('Y-m-d H:i:s', strtotime('+1 day'));
            $conn->query("REPLACE INTO circle_mutes (user_id, circle_id, until_time) VALUES ($member_id, $circle_id, '$until')");
        } elseif ($action === 'promote') {
            $conn->query("UPDATE circle_members SET role = 'moderator' WHERE user_id = $member_id AND circle_id = $circle_id");
        }
    }
}

$members = $conn->query("SELECT u.id, u.username, u.profile_picture, cm.role FROM circle_members cm JOIN users u ON cm.user_id = u.id WHERE cm.circle_id = $circle_id ORDER BY cm.joined_at ASC");

$top_active = $conn->query("SELECT u.username FROM posts p JOIN users u ON p.user_id = u.id WHERE p.circle_id = $circle_id GROUP BY p.user_id ORDER BY COUNT(*) DESC LIMIT 1")->fetch_assoc();
$newest = $conn->query("SELECT u.username FROM circle_members cm JOIN users u ON cm.user_id = u.id WHERE cm.circle_id = $circle_id ORDER BY cm.joined_at DESC LIMIT 1")->fetch_assoc();

?>