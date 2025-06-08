<?php
if (session_status() === PHP_SESSION_NONE) session_start();
date_default_timezone_set('Asia/Makassar');
include_once '../includes/db.php';

$circle_id = isset($_GET['circle_id']) ? intval($_GET['circle_id']) : 0;
$user_id = $_SESSION['user_id'];

$circle_name = $circle_description = $rules = $msg = '';
$circle_detail = [];
$members = [];
$top_active = $newest = null;

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

// === Update nama, deskripsi, rules circle ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['circle_name'], $_POST['circle_description'], $_POST['rules'])) {
        $new_name  = trim($_POST['circle_name']);
        $new_desc  = trim($_POST['circle_description']);
        $new_rules = trim($_POST['rules']);

        $update = $conn->prepare("UPDATE circles SET name = ?, description = ?, rules = ? WHERE id = ?");
        $update->bind_param("sssi", $new_name, $new_desc, $new_rules, $circle_id);
        if ($update->execute()) {
            $circle_name        = $new_name;
            $circle_description = $new_desc;
            $rules              = $new_rules;
            $msg = "✅ Circle berhasil diperbarui.";
        }
        $update->close();
    }

    // === Aksi terhadap anggota ===
    if (isset($_POST['action']) && isset($_POST['member_id'])) {
        $member_id = intval($_POST['member_id']);
        $action    = $_POST['action'];

        if ($action === 'kick') {
            $del = $conn->prepare("DELETE FROM circle_members WHERE user_id = ? AND circle_id = ?");
            $del->bind_param("ii", $member_id, $circle_id);
            $del->execute();
            $del->close();
            $msg = "🚫 Anggota dikeluarkan.";

        date_default_timezone_set('Asia/Makassar'); // atau 'Asia/Singapore'


        } elseif ($action === 'mute' && isset($_POST['mute_duration'])) {
            $duration = intval($_POST['mute_duration']);
            $until = date('Y-m-d H:i:s', strtotime("+$duration hour"));

            $mute = $conn->prepare("REPLACE INTO circle_mutes (user_id, circle_id, until_time) VALUES (?, ?, ?)");
            $mute->bind_param("iis", $member_id, $circle_id, $until);
            $mute->execute();
            $mute->close();
            $msg = "🔇 Anggota dimute selama $duration jam.";

        } elseif ($action === 'promote') {
            $promote = $conn->prepare("UPDATE circle_members SET role = 'moderator' WHERE user_id = ? AND circle_id = ?");
            $promote->bind_param("ii", $member_id, $circle_id);
            $promote->execute();
            $promote->close();
            $msg = "⭐ Anggota dipromosikan sebagai moderator.";
        }
    }
}

// === Ambil daftar anggota ===
$get_members = $conn->prepare("
    SELECT u.id, u.username, u.profile_picture, cm.role, cm.joined_at
    FROM circle_members cm
    JOIN users u ON cm.user_id = u.id
    WHERE cm.circle_id = ?
    ORDER BY cm.joined_at ASC
");
$get_members->bind_param("i", $circle_id);
$get_members->execute();
$res = $get_members->get_result();
while ($row = $res->fetch_assoc()) {
    $members[] = $row;
}
$get_members->close();

// === Anggota paling aktif ===
$top_stmt = $conn->prepare("
    SELECT u.username FROM posts p
    JOIN users u ON u.id = p.user_id
    WHERE p.circle_id = ?
    GROUP BY p.user_id
    ORDER BY COUNT(*) DESC LIMIT 1
");
$top_stmt->bind_param("i", $circle_id);
$top_stmt->execute();
$top_stmt->bind_result($top_active_user);
$top_stmt->fetch();
$top_active = $top_active_user;
$top_stmt->close();

// === Anggota terbaru ===
$new_stmt = $conn->prepare("
    SELECT u.username FROM circle_members cm
    JOIN users u ON cm.user_id = u.id
    WHERE cm.circle_id = ?
    ORDER BY cm.joined_at DESC LIMIT 1
");
$new_stmt->bind_param("i", $circle_id);
$new_stmt->execute();
$new_stmt->bind_result($newest_user);
$new_stmt->fetch();
$newest = $newest_user;
$new_stmt->close();
?>
