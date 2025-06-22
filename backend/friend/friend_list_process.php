<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$friends = [];

// Ambil daftar teman dari tabel friends
$stmt = $conn->prepare("
    SELECT u.username, u.city, u.profession
    FROM friends f
    JOIN users u ON u.id = f.friend_id
    WHERE f.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $friends[] = $row;
}

$stmt->close();
