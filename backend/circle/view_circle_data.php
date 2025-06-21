<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../includes/db.php';

$user_id = $_SESSION['user_id'];
$search = isset($_GET['search']) ? '%' . trim($_GET['search']) . '%' : '%';

$managed_circles = [];
$joined_circles = [];

// Ambil circle yang diikuti user dan pisahkan berdasarkan role
$stmt = $conn->prepare("
    SELECT
        c.id, c.name, c.description, c.creator_id,
        cm.role,
        (SELECT COUNT(*) FROM circle_members WHERE circle_id = c.id) AS member_count,
        CASE
            WHEN c.creator_id = cm.user_id THEN 'creator'
            ELSE cm.role
        END AS actual_role
    FROM circle_members cm
    JOIN circles c ON cm.circle_id = c.id
    WHERE cm.user_id = ? AND c.name LIKE ?
    ORDER BY c.name ASC
");
$stmt->bind_param("is", $user_id, $search);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    if ($row['actual_role'] === 'creator' || $row['actual_role'] === 'moderator') {
        $managed_circles[] = $row;
    } else {
        $joined_circles[] = $row;
    }
}
$stmt->close();

// Ambil permintaan join yang masih pending
$pending_requests = [];
$req_stmt = $conn->prepare("
    SELECT cr.id, cr.circle_id, c.name, c.description
    FROM circle_requests cr
    JOIN circles c ON cr.circle_id = c.id
    WHERE cr.user_id = ? AND cr.status = 'pending'
");
$req_stmt->bind_param("i", $user_id);
$req_stmt->execute();
$req_result = $req_stmt->get_result();
while ($row = $req_result->fetch_assoc()) {
    $pending_requests[] = $row;
}
$req_stmt->close();

// Logika pembatalan request join
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_request_id'])) {
    $cancel_id = intval($_POST['cancel_request_id']);
    $del = $conn->prepare("DELETE FROM circle_requests WHERE id = ? AND user_id = ?");
    $del->bind_param("ii", $cancel_id, $user_id);
    $del->execute();
    $del->close();
    header("Location: view_circle.php?msg=Permintaan gabung dibatalkan");
    exit;
}
