<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once __DIR__ . '/../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

$current_user = $_SESSION['user_id'];
$requests = []; // definisi awal
$success = '';
$error = '';

// Proses konfirmasi/penolakan permintaan
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['action'], $_POST['request_id']) && is_numeric($_POST['request_id'])) {
        $request_id = intval($_POST['request_id']);
        $action     = $_POST['action'];

        $stmt = $conn->prepare("SELECT sender_id, receiver_id FROM friend_requests WHERE id = ? AND receiver_id = ?");
        $stmt->bind_param("ii", $request_id, $current_user);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($sender_id, $receiver_id);
            $stmt->fetch();
            $stmt->close();

            if ($action === 'accept') {
                $update = $conn->prepare("UPDATE friend_requests SET status = 'accepted' WHERE id = ?");
                $update->bind_param("i", $request_id);
                $update->execute();
                $update->close();

                $insert = $conn->prepare("INSERT INTO friends (user_id, friend_id) VALUES (?, ?), (?, ?)");
                $insert->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
                $insert->execute();
                $insert->close();

                $success = "Permintaan telah diterima.";
            } elseif ($action === 'reject') {
                $reject = $conn->prepare("UPDATE friend_requests SET status = 'rejected' WHERE id = ?");
                $reject->bind_param("i", $request_id);
                $reject->execute();
                $reject->close();

                $success = "Permintaan telah ditolak.";
            }
        } else {
            $stmt->close();
            $error = "Permintaan tidak ditemukan atau sudah diproses.";
        }
    }
}

// Ambil ulang permintaan PENDING
$stmt = $conn->prepare("
    SELECT fr.id, u.id AS user_id, u.username, u.city, u.profession
    FROM friend_requests fr
    JOIN users u ON fr.sender_id = u.id
    WHERE fr.receiver_id = ? AND fr.status = 'pending'
    ORDER BY fr.id DESC
");
$stmt->bind_param("i", $current_user);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}
$stmt->close();
?>
