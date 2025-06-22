<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once __DIR__ . '/../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

$current_user = $_SESSION['user_id'];
$requests = []; // Pastikan didefinisikan dulu agar tidak undefined

// Jika user ingin mengkonfirmasi permintaan pertemanan
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['action'], $_POST['request_id']) && is_numeric($_POST['request_id'])) {
        $request_id = intval($_POST['request_id']);
        $action     = $_POST['action']; // 'accept' atau 'reject'

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

                // Tambah dua arah
                $insert = $conn->prepare("INSERT INTO friends (user_id, friend_id) VALUES (?, ?), (?, ?)");
                $insert->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
                $insert->execute();
                $insert->close();

                header("Location: ../../friend/friend_request.php?success=Permintaan diterima.");
                exit;
            } elseif ($action === 'reject') {
                $reject = $conn->prepare("UPDATE friend_requests SET status = 'rejected' WHERE id = ?");
                $reject->bind_param("i", $request_id);
                $reject->execute();
                $reject->close();

                header("Location: ../../friend/friend_request.php?success=Permintaan ditolak.");
                exit;
            }
        } else {
            $stmt->close();
            header("Location: ../../friend/friend_request.php?error=Permintaan tidak ditemukan.");
            exit;
        }
    }

    // Jika mengirim permintaan baru
    elseif (isset($_POST['target_user'])) {
        $target_user = intval($_POST['target_user']);

        if ($target_user <= 0 || $target_user === $current_user) {
            header("Location: ../../friend/friend_request.php?error=Permintaan tidak valid");
            exit;
        }

        $check = $conn->prepare("SELECT id FROM friend_requests WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)");
        $check->bind_param("iiii", $current_user, $target_user, $target_user, $current_user);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $check->close();
            header("Location: ../../friend/friend_request.php?error=Permintaan sudah ada.");
            exit;
        }
        $check->close();

        $insert = $conn->prepare("INSERT INTO friend_requests (sender_id, receiver_id, status) VALUES (?, ?, 'pending')");
        $insert->bind_param("ii", $current_user, $target_user);

        if ($insert->execute()) {
            header("Location: ../../friend/friend_request.php?success=Permintaan pertemanan dikirim.");
        } else {
            header("Location: ../../friend/friend_request.php?error=Gagal mengirim permintaan.");
        }
        $insert->close();
    }
}

// Ambil permintaan pertemanan yang masuk
$stmt = $conn->prepare("
    SELECT fr.id, u.id AS user_id, u.username, u.city, u.profession
    FROM friend_requests fr
    JOIN users u ON fr.sender_id = u.id
    WHERE fr.receiver_id = ? AND fr.status = 'pending'
");
$stmt->bind_param("i", $current_user);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $requests[] = $row;
}
$stmt->close();
