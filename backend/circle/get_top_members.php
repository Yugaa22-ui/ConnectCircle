<?php
include_once '../../includes/db.php';

$circleId = isset($_GET['circle_id']) ? intval($_GET['circle_id']) : 0;

if ($circleId <= 0) {
    echo json_encode([]);
    exit;
}

$query = "
    SELECT 
        u.id,
        u.username,
        u.profile_picture,
        COUNT(p.id) AS post_count,
        CASE 
            WHEN cm.user_id IS NOT NULL AND cm.circle_id IS NOT NULL AND m.until_time > NOW() THEN 1
            ELSE 0
        END AS is_muted
    FROM circle_members cm
    JOIN users u ON cm.user_id = u.id
    LEFT JOIN posts p ON p.user_id = u.id AND p.circle_id = cm.circle_id AND p.deleted = 0
    LEFT JOIN circle_mutes m ON m.user_id = u.id AND m.circle_id = cm.circle_id
    WHERE cm.circle_id = ?
    GROUP BY u.id, u.username, u.profile_picture, is_muted
    ORDER BY post_count DESC
    LIMIT 3
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $circleId);
$stmt->execute();
$result = $stmt->get_result();

$topMembers = [];
while ($row = $result->fetch_assoc()) {
    $topMembers[] = [
        'id' => $row['id'],
        'username' => $row['username'],
        'profile_picture' => $row['profile_picture'],
        'post_count' => (int)$row['post_count'],
        'is_muted' => (int)$row['is_muted']
    ];
}

echo json_encode($topMembers);
?>
