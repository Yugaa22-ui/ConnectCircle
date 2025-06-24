<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo '<div class="alert alert-danger">Anda belum login.</div>';
    exit;
}

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

if (count($managed_circles) === 0 && count($joined_circles) === 0) {
    echo '<div class="alert alert-warning">Circle yang anda cari tidak tersedia.</div>';
    exit;
}

// Circle yang dikelola
if (count($managed_circles) > 0) {
    echo '<h6 class="text-info mb-2"><i class="bi bi-tools me-1"></i> Circle yang Kamu Kelola</h6>';
    echo '<div class="list-group mb-4">';
    foreach ($managed_circles as $circle) {
        echo '<div class="list-group-item bg-dark text-white border-secondary">';
        echo '<h5 class="mb-1">' . htmlspecialchars($circle['name']) . '</h5>';
        echo '<p class="mb-1">' . nl2br(htmlspecialchars($circle['description'])) . '</p>';
        echo '<small class="text-muted"><i class="bi bi-people-fill me-1"></i>' . $circle['member_count'] . ' anggota</small>';
        echo '<br><a href="discussion_page.php?circle_id=' . $circle['id'] . '" class="btn btn-outline-success btn-sm mt-2">Masuk Diskusi</a>';
        echo '</div>';
    }
    echo '</div>';
}

// Circle yang diikuti
if (count($joined_circles) > 0) {
    echo '<h6 class="text-primary mb-2"><i class="bi bi-people me-1"></i> Circle yang Kamu Ikuti</h6>';
    echo '<div class="list-group mb-4">';
    foreach ($joined_circles as $circle) {
        echo '<div class="list-group-item bg-dark text-white border-secondary">';
        echo '<h5 class="mb-1">' . htmlspecialchars($circle['name']) . '</h5>';
        echo '<p class="mb-1">' . nl2br(htmlspecialchars($circle['description'])) . '</p>';
        echo '<small class="text-muted"><i class="bi bi-people-fill me-1"></i>' . $circle['member_count'] . ' anggota</small>';
        echo '<br><a href="discussion_page.php?circle_id=' . $circle['id'] . '" class="btn btn-outline-success btn-sm mt-2">Masuk Diskusi</a>';
        echo '</div>';
    }
    echo '</div>';
}
