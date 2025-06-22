<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../../includes/db.php'; // ← karena file ini ada di folder backend/friend/

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

$current_user = $_SESSION['user_id'];
$target_user = isset($_POST['target_user']) ? intval($_POST['target_user']) : 0;

if ($target_user <= 0 || $target_user === $current_user) {
    header("Location: ../../search/search.php?error=Permintaan tidak valid.");
    exit;
}

// Cek apakah sudah ada permintaan sebelumnya
$check = $conn->prepare("
    SELECT id FROM friend_requests
    WHERE (sender_id = ? AND receiver_id = ?)
       OR (sender_id = ? AND receiver_id = ?)
");
$check->bind_param("iiii", $current_user, $target_user, $target_user, $current_user);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $check->close();
    header("Location: ../../search/search.php?error=Permintaan sudah dikirim atau telah ada status.");
    exit;
}
$check->close();

// Simpan permintaan baru
$insert = $conn->prepare("INSERT INTO friend_requests (sender_id, receiver_id, status) VALUES (?, ?, 'pending')");
$insert->bind_param("ii", $current_user, $target_user);
if ($insert->execute()) {
    header("Location: ../../search/search.php?success=Permintaan pertemanan berhasil dikirim.");
} else {
    header("Location: ../../search/search.php?error=Gagal mengirim permintaan.");
}
$insert->close();
?>
