<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../includes/db.php';

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';
$available_circles = [];

// Proses ketika user mengklik gabung
if (isset($_GET['join']) && is_numeric($_GET['join'])) {
    $circle_id = intval($_GET['join']);

    // Cek apakah user sudah tergabung
    $check = $conn->prepare("SELECT id FROM circle_members WHERE user_id = ? AND circle_id = ?");
    $check->bind_param("ii", $user_id, $circle_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Kamu sudah tergabung dalam circle ini.";
    } else {
        $join = $conn->prepare("INSERT INTO circle_members (user_id, circle_id) VALUES (?, ?)");
        $join->bind_param("ii", $user_id, $circle_id);
        if ($join->execute()) {
            $success = "Berhasil bergabung dengan circle!";
        } else {
            $error = "Gagal bergabung dengan circle.";
        }
        $join->close();
    }

    $check->close();
}

// Ambil daftar circle yang belum diikuti user
$stmt = $conn->prepare("
    SELECT c.id, c.name, c.description
    FROM circles c
    WHERE c.id NOT IN (
        SELECT circle_id FROM circle_members WHERE user_id = ?
    )
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $available_circles[] = $row;
}

$stmt->close();
