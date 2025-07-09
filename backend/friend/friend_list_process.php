<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once(__DIR__ . '/../../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}

$current_user = $_SESSION['user_id'];
$friends = [];

// Ambil daftar teman + foto profil
$stmt = $conn->prepare("
    SELECT u.id, u.username, u.city, u.profession, u.profile_picture
    FROM friends f
    JOIN users u ON u.id = f.friend_id
    WHERE f.user_id = ?
    ORDER BY u.username ASC
");
$stmt->bind_param("i", $current_user);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $friends[] = $row;
}

$stmt->close();
