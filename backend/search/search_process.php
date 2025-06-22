<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../includes/db.php';

$current_user_id = $_SESSION['user_id'] ?? 0;
$search_term = '';
$results = [];
$total_matches = 0;

function getFriendStatus($conn, $current_user_id, $target_user_id) {
    $stmt = $conn->prepare("
        SELECT status FROM friend_requests
        WHERE (sender_id = ? AND receiver_id = ?)
           OR (sender_id = ? AND receiver_id = ?)
        LIMIT 1
    ");
    $stmt->bind_param("iiii", $current_user_id, $target_user_id, $target_user_id, $current_user_id);
    $stmt->execute();
    $stmt->store_result();

    $status = 'none';
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($f_status);
        $stmt->fetch();
        $status = $f_status;
    }
    $stmt->close();
    return $status;
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['minat'])) {
    $search_term = trim($_GET['minat']);

    if (!empty($search_term)) {
        $stmt = $conn->prepare("
            SELECT u.id, u.username, u.city, u.profession, i.name AS interest
            FROM users u
            JOIN user_interests ui ON u.id = ui.user_id
            JOIN interests i ON ui.interest_id = i.id
            WHERE i.name LIKE CONCAT('%', ?, '%') AND u.id != ?
            GROUP BY u.id
        ");
        $stmt->bind_param("si", $search_term, $current_user_id);
        $stmt->execute();
        $results = $stmt->get_result();
        $total_matches = $results->num_rows;
    }
}
