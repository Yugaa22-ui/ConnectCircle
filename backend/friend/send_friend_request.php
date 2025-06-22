<?php
if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');

include_once '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'unauthorized']);
    exit;
}

$current_user = $_SESSION['user_id'];
$target_user  = isset($_POST['target_user']) ? intval($_POST['target_user']) : 0;

// Validasi awal
if ($target_user <= 0 || $target_user === $current_user) {
    echo json_encode(['status' => 'invalid']);
    exit;
}

// Cek apakah sudah berteman (di tabel friends)
$checkFriend = $conn->prepare("SELECT 1 FROM friends WHERE user_id = ? AND friend_id = ?");
$checkFriend->bind_param("ii", $current_user, $target_user);
$checkFriend->execute();
$checkFriend->store_result();
if ($checkFriend->num_rows > 0) {
    echo json_encode(['status' => 'already_friends']);
    $checkFriend->close();
    exit;
}
$checkFriend->close();

// Cek apakah sudah ada permintaan sebelumnya dari current_user ke target_user
$check = $conn->prepare("
    SELECT id, status FROM friend_requests
    WHERE sender_id = ? AND receiver_id = ?
");
$check->bind_param("ii", $current_user, $target_user);
$check->execute();
$check->store_result();
$check->bind_result($req_id, $status);
$found = $check->fetch();
$check->close();

if ($found) {
    if ($status === 'pending') {
        echo json_encode(['status' => 'already_sent']);
        exit;
    } elseif ($status === 'accepted') {
        echo json_encode(['status' => 'already_friends']);
        exit;
    } elseif ($status === 'rejected') {
        // Ubah jadi pending lagi
        $update = $conn->prepare("UPDATE friend_requests SET status = 'pending' WHERE id = ?");
        $update->bind_param("i", $req_id);
        if ($update->execute()) {
            echo json_encode(['status' => 'ok']);
        } else {
            echo json_encode(['status' => 'error']);
        }
        $update->close();
        exit;
    }
}

// Jika belum pernah mengirim ke user ini
$insert = $conn->prepare("INSERT INTO friend_requests (sender_id, receiver_id, status) VALUES (?, ?, 'pending')");
$insert->bind_param("ii", $current_user, $target_user);
if ($insert->execute()) {
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'error']);
}
$insert->close();
