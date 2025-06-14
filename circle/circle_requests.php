<?php
include '../backend/auth/auth_check.php';
include '../includes/db.php';

if (!isset($_GET['circle_id']) || !is_numeric($_GET['circle_id'])) {
    echo "<script>alert('Circle tidak ditemukan'); window.location='view_circle.php';</script>";
    exit;
}

$circle_id = intval($_GET['circle_id']);
$user_id = $_SESSION['user_id'];

// Validasi role: hanya creator atau moderator yang boleh akses
$auth_check = $conn->prepare("SELECT cm.role, c.creator_id FROM circle_members cm
    JOIN circles c ON c.id = cm.circle_id
    WHERE cm.circle_id = ? AND cm.user_id = ?");
$auth_check->bind_param("ii", $circle_id, $user_id);
$auth_check->execute();
$auth_check->bind_result($role, $creator_id);
if (!$auth_check->fetch() || !in_array($role, ['moderator']) && $creator_id != $user_id) {
    echo "<script>alert('Kamu tidak memiliki akses ke halaman ini.'); window.location='discussion_page.php?circle_id=$circle_id';</script>";
    exit;
}
$auth_check->close();

// Ambil permintaan join yang masih pending
$req_stmt = $conn->prepare("SELECT cr.id, u.username, u.profile_picture, cr.created_at, cr.user_id
    FROM circle_requests cr
    JOIN users u ON cr.user_id = u.id
    WHERE cr.circle_id = ? AND cr.status = 'pending'
    ORDER BY cr.created_at ASC");
$req_stmt->bind_param("i", $circle_id);
$req_stmt->execute();
$requests = $req_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Permintaan Join Circle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Permintaan Gabung Circle (<?= $requests->num_rows ?>)</h4>
            <a href="discussion_page.php?circle_id=<?= $circle_id ?>" class="btn btn-light btn-sm">🔙 Kembali</a>
        </div>
        <div class="card-body">
            <?php if ($requests->num_rows > 0): ?>
                <ul class="list-group">
                    <?php while ($row = $requests->fetch_assoc()): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="<?= $row['profile_picture'] ? '../assets/uploads/img/' . htmlspecialchars($row['profile_picture']) : '../assets/img/default.png' ?>" class="rounded-circle me-2" width="40" height="40">
                                <div>
                                    <strong><?= htmlspecialchars($row['username']) ?></strong><br>
                                    <small class="text-muted"><?= date('d M Y H:i', strtotime($row['created_at'])) ?></small>
                                </div>
                            </div>
                            <form method="POST" action="../backend/circle/handle_request.php" class="d-flex gap-2">
                                <input type="hidden" name="circle_id" value="<?= $circle_id ?>">
                                <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">
                                <button name="action" value="approve" class="btn btn-success btn-sm" onclick="return confirm('Terima permintaan ini?')">✔️</button>
                                <button name="action" value="reject" class="btn btn-danger btn-sm" onclick="return confirm('Tolak permintaan ini?')">❌</button>
                            </form>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <div class="alert alert-info">Tidak ada permintaan bergabung saat ini.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
