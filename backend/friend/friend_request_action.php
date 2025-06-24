<?php
if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');
include_once __DIR__ . '/../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['status' => 'unauthorized']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak valid']);
    exit;
}

$current_user = $_SESSION['user_id'];

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

            echo json_encode(["status" => "ok", "message" => "Permintaan telah diterima."]);
            exit;
        } elseif ($action === 'reject') {
            $reject = $conn->prepare("UPDATE friend_requests SET status = 'rejected' WHERE id = ?");
            $reject->bind_param("i", $request_id);
            $reject->execute();
            $reject->close();

            echo json_encode(["status" => "ok", "message" => "Permintaan telah ditolak."]);
            exit;
        }
    } else {
        $stmt->close();
        echo json_encode(["status" => "error", "message" => "Permintaan tidak ditemukan atau sudah diproses."]);
        exit;
    }
} else {
    echo json_encode(["status" => "error", "message" => "Data tidak valid."]);
    exit;
}
