<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once '../includes/db.php';

$user_id = $_SESSION['user_id'];
$circle_id = isset($_GET['circle_id']) ? intval($_GET['circle_id']) : 0;

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
    echo "<script>alert('Kamu belum tergabung dalam circle ini.'); window.location='../../circle/join_circle.php';</script>";
    exit;
}
$cek->close();

// Ambil info circle
$circle_info = $conn->prepare("SELECT name, creator_id FROM circles WHERE id = ?");
$circle_info->bind_param("i", $circle_id);
$circle_info->execute();
$circle_info->bind_result($circle_name, $creator_id);
$circle_info->fetch();
$circle_info->close();

$is_creator = $creator_id == $user_id;

// Keluar dari circle
if (isset($_GET['leave']) && $_GET['leave'] === 'yes') {
    $out = $conn->prepare("DELETE FROM circle_members WHERE user_id = ? AND circle_id = ?");
    $out->bind_param("ii", $user_id, $circle_id);
    if ($out->execute()) {
        header("Location: ../../circle/view_circle.php?msg=Berhasil keluar dari circle.");
    } else {
        $error = "Gagal keluar.";
    }
    $out->close();
}

// Kirim pesan
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $message = trim($_POST['message']);
    $image = '';

    if (!empty($_FILES['image']['name'])) {
        $img = $_FILES['image'];
        $ext = pathinfo($img['name'], PATHINFO_EXTENSION);
        $valid = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($ext), $valid)) {
            $filename = 'img_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
            $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/connectcircle/assets/uploads/img/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true); // pastikan folder ada

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

// Ambil semua pesan
$posts = $conn->prepare("
    SELECT u.username, p.content, p.created_at, p.image_path
    FROM posts p
    JOIN users u ON p.user_id = u.id
    WHERE p.circle_id = ?
    ORDER BY p.created_at ASC
");

// Ambil info circle lengkap (untuk modal informasi)
function get_circle_detail($conn, $circle_id) {
    $circle_stmt = $conn->prepare("
        SELECT c.name, c.description, u.username AS creator_name, u.profile_picture AS creator_photo
        FROM circles c
        JOIN users u ON c.creator_id = u.id
        WHERE c.id = ?
    ");
    $circle_stmt->bind_param("i", $circle_id);
    $circle_stmt->execute();
    $circle_info = $circle_stmt->get_result()->fetch_assoc();
    $circle_stmt->close();

    $members_stmt = $conn->prepare("
        SELECT u.username, u.profile_picture
        FROM circle_members cm
        JOIN users u ON cm.user_id = u.id
        WHERE cm.circle_id = ?
    ");
    $members_stmt->bind_param("i", $circle_id);
    $members_stmt->execute();
    $circle_info['members'] = $members_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $members_stmt->close();

    return $circle_info;
}

$circle_detail = get_circle_detail($conn, $circle_id);


$posts->bind_param("i", $circle_id);
$posts->execute();
$results = $posts->get_result();
