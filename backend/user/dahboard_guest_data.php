<?php
include '../includes/db.php';

// Ambil beberapa circle publik
$circles = $conn->query("SELECT name, description, created_at FROM circles ORDER BY created_at DESC LIMIT 5");

// Ambil beberapa user aktif (yang punya posting)
$users = $conn->query("
    SELECT u.username, u.profession, u.city, COUNT(p.id) AS total_post
    FROM users u
    JOIN posts p ON u.id = p.user_id
    GROUP BY u.id
    ORDER BY total_post DESC
    LIMIT 5
");
