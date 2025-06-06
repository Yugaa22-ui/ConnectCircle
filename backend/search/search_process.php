<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../includes/db.php';

$search_term = '';
$results = [];
$total_matches = 0;

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['minat'])) {
    $search_term = trim($_GET['minat']);

    if (!empty($search_term)) {
        $stmt = $conn->prepare("
            SELECT u.username, u.city, u.profession, i.name AS interest
            FROM users u
            JOIN user_interests ui ON u.id = ui.user_id
            JOIN interests i ON ui.interest_id = i.id
            WHERE i.name LIKE CONCAT('%', ?, '%')
            GROUP BY u.id
        ");
        $stmt->bind_param("s", $search_term);
        $stmt->execute();
        $results = $stmt->get_result();
        $total_matches = $results->num_rows;
    }
}
