<?php
if (session_status() === PHP_SESSION_NONE) session_start();
date_default_timezone_set('Asia/Makassar');
include_once '../includes/db.php';

$user_id = $_SESSION['user_id'];
$circle_id = isset($_POST['circle_id']) ? intval($_POST['circle_id']) : (isset($_GET['circle_id']) ? intval($_GET['circle_id']) : 0);
$is_muted = false;
$mute_message = '';

if ($circle_id === 0) {
    header("Location: ../../circle/view_circle.php");
    exit;
}

// Cek apakah user tergabung
$cek = $conn->prepare("SELECT id FROM circle_members WHERE user_id = ? AND circle_id = ?");
$cek->bind_param("ii", $user_id, $circle_id);
$cek->execute();
$cek->store_result();
if ($cek->num_rows === 0) {
    header("Location: ../../circle/view_circle.php?msg=Kamu telah keluar atau tidak tergabung dalam circle ini.");
    exit;
}
$cek->close();

// Cek status mute
$mute = $conn->prepare("SELECT until_time FROM circle_mutes WHERE user_id = ? AND circle_id = ?");
$mute->bind_param("ii", $user_id, $circle_id);
$mute->execute();
$mute->store_result();
$mute->bind_result($until_time);
if ($mute->num_rows > 0 && $mute->fetch()) {
    if (strtotime($until_time) > time()) {
        $is_muted = true;
        $mute_message = "Kamu sedang dimute hingga " . date("d M Y H:i", strtotime($until_time)) . ". Kamu tidak dapat mengirim pesan.";
    } else {
        $clear = $conn->prepare("DELETE FROM circle_mutes WHERE user_id = ? AND circle_id = ?");
        $clear->bind_param("ii", $user_id, $circle_id);
        $clear->execute();
        $clear->close();
    }
}
$mute->close();

// Info circle
$circle_info = $conn->prepare("SELECT name, creator_id FROM circles WHERE id = ?");
$circle_info->bind_param("i", $circle_id);
$circle_info->execute();
$circle_info->bind_result($circle_name, $creator_id);
$circle_info->fetch();
$circle_info->close();

$is_creator = ($creator_id == $user_id);

// Status private/public
$circle_priv_stmt = $conn->prepare("SELECT is_private FROM circles WHERE id = ?");
$circle_priv_stmt->bind_param("i", $circle_id);
$circle_priv_stmt->execute();
$circle_priv_stmt->bind_result($is_private);
$circle_priv_stmt->fetch();
$circle_priv_stmt->close();

// Keluar circle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['leave_confirm']) && $_POST['leave_confirm'] === 'yes') {
    $out = $conn->prepare("DELETE FROM circle_members WHERE user_id = ? AND circle_id = ?");
    $out->bind_param("ii", $user_id, $circle_id);
    if ($out->execute()) {
        header("Location: ../circle/view_circle.php?msg=Berhasil keluar dari circle.");
        exit;
    }
    $out->close();
}

// Hapus pesan
if (isset($_POST['delete_post_id'])) {
    $post_id = intval($_POST['delete_post_id']);
    $del = $conn->prepare("UPDATE posts SET deleted = 1 WHERE id = ? AND user_id = ?");
    $del->bind_param("ii", $post_id, $user_id);
    $del->execute();
    $del->close();
    header("Location: discussion_page.php?circle_id=$circle_id&msg=Pesan dihapus");
    exit;
}

// Edit pesan
if (isset($_POST['edit_post_id'], $_POST['edit_message'])) {
    $post_id = intval($_POST['edit_post_id']);
    $new_content = trim($_POST['edit_message']);
    $edit = $conn->prepare("UPDATE posts SET content = ?, updated_at = NOW() WHERE id = ? AND user_id = ?");
    $edit->bind_param("sii", $new_content, $post_id, $user_id);
    $edit->execute();
    $edit->close();
    header("Location: discussion_page.php?circle_id=$circle_id&msg=Pesan diperbarui");
    exit;
}

// Kirim pesan baru
if ($_SERVER["REQUEST_METHOD"] === "POST" && !$is_muted && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    $image = '';

    if (!empty($_FILES['image']['name'])) {
        $img = $_FILES['image'];
        $ext = pathinfo($img['name'], PATHINFO_EXTENSION);
        $valid = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($ext), $valid)) {
            $filename = 'img_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
            $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/connectcircle/assets/uploads/img/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            $target = $upload_dir . $filename;
            if (move_uploaded_file($img['tmp_name'], $target)) {
                $image = $filename;
            }
        }
    }

    if (!empty($message) || $image) {
        $stmt = $conn->prepare("INSERT INTO posts (circle_id, user_id, content, image_path) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $circle_id, $user_id, $message, $image);
        $stmt->execute();
        $stmt->close();
    }
}

// Catat siapa yang lihat
$conn->query("SET time_zone = '+08:00'");
$conn->query("INSERT IGNORE INTO post_views (post_id, user_id) SELECT id, $user_id FROM posts WHERE circle_id = $circle_id");

// Ambil semua pesan (kecuali yang dihapus)
$posts = $conn->prepare("SELECT p.id, u.username, p.content, p.created_at, p.updated_at, p.image_path, p.user_id, p.deleted FROM posts p JOIN users u ON p.user_id = u.id WHERE p.circle_id = ? ORDER BY p.created_at ASC");
$posts->bind_param("i", $circle_id);
$posts->execute();
$results = $posts->get_result();

// Ambil detail circle
function get_circle_detail($conn, $circle_id) {
    $circle_stmt = $conn->prepare("SELECT c.name, c.description, u.username AS creator_name, u.profile_picture AS creator_photo FROM circles c JOIN users u ON c.creator_id = u.id WHERE c.id = ?");
    $circle_stmt->bind_param("i", $circle_id);
    $circle_stmt->execute();
    $circle_info = $circle_stmt->get_result()->fetch_assoc();
    $circle_stmt->close();

    $members_stmt = $conn->prepare("SELECT u.username, u.profile_picture, CASE WHEN m.until_time > NOW() THEN 1 ELSE 0 END AS is_muted FROM circle_members cm JOIN users u ON cm.user_id = u.id LEFT JOIN circle_mutes m ON m.user_id = u.id AND m.circle_id = cm.circle_id WHERE cm.circle_id = ?");
    $members_stmt->bind_param("i", $circle_id);
    $members_stmt->execute();
    $circle_info['members'] = $members_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $members_stmt->close();

    return $circle_info;
}

$circle_detail = get_circle_detail($conn, $circle_id);
