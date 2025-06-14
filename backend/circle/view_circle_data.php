<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../includes/db.php';

$user_id = $_SESSION['user_id'];
$search = isset($_GET['search']) ? '%' . trim($_GET['search']) . '%' : '%';
$circles = [];

// Ambil circle yang sudah diikuti user
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

// Ambil permintaan join yang belum disetujui
$pending_requests = [];
$req_stmt = $conn->prepare("
    SELECT cr.circle_id, c.name, c.description
    FROM circle_requests cr
    JOIN circles c ON cr.circle_id = c.id
    WHERE cr.user_id = ?
");
$req_stmt->bind_param("i", $user_id);
$req_stmt->execute();
$req_result = $req_stmt->get_result();
while ($row = $req_result->fetch_assoc()) {
    $pending_requests[] = $row;
}
$req_stmt->close();

// Batalkan permintaan join jika ada POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_request'])) {
    $cancel_circle_id = intval($_POST['cancel_request']);
    $del = $conn->prepare("DELETE FROM circle_requests WHERE user_id = ? AND circle_id = ?");
    $del->bind_param("ii", $user_id, $cancel_circle_id);
    $del->execute();
    $del->close();
    header("Location: view_circle.php?msg=Permintaan dibatalkan");
    exit;
}
