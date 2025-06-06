<?php
include_once '../backend/auth/auth_check.php';
include_once '../includes/db.php';
include_once '../backend/badges/check_and_assign_badges.php';

$user_id = $_SESSION['user_id'];
assign_badges($conn, $user_id);

// Ambil data user
$stmt = $conn->prepare("SELECT username, email, city, profession, bio, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $city, $profession, $bio, $profile_picture);
$stmt->fetch();
$stmt->close();

// Ambil badge user
$badges_stmt = $conn->prepare("
    SELECT b.name, b.description, b.icon
    FROM user_badges ub
    JOIN badges b ON ub.badge_id = b.id
    WHERE ub.user_id = ?
");
$badges_stmt->bind_param("i", $user_id);
$badges_stmt->execute();
$badges_result = $badges_stmt->get_result();
