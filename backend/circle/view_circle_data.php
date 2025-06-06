<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../includes/db.php';

$user_id = $_SESSION['user_id'];
$search = isset($_GET['search']) ? '%' . trim($_GET['search']) . '%' : '%';
$circles = [];

$stmt = $conn->prepare("
    SELECT c.id, c.name, c.description,
        (SELECT COUNT(*) FROM circle_members cm2 WHERE cm2.circle_id = c.id) AS member_count
    FROM circle_members cm
    JOIN circles c ON cm.circle_id = c.id
    WHERE cm.user_id = ? AND c.name LIKE ?
    ORDER BY c.name ASC
");
$stmt->bind_param("is", $user_id, $search);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $circles[] = $row;
}

$stmt->close();
