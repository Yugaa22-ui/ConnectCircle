<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once __DIR__ . '/../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

$current_user = $_SESSION['user_id'];
$requests = [];

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
