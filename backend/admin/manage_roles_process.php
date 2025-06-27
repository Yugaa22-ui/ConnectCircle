<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once '../auth/auth_check.php';
include_once '../../includes/db.php';

// Batasi akses admin
if ($_SESSION['role'] !== 'admin') {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Akses hanya untuk admin.']);
    exit;
}

// Validasi request AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' &&
    isset($_POST['user_id'], $_POST['role'])) {
    
    header('Content-Type: application/json');

    $user_id = intval($_POST['user_id']);
    $new_role = $_POST['role'];
    $valid_roles = ['admin', 'user', 'moderator'];

    if (in_array($new_role, $valid_roles)) {
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $new_role, $user_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => 'Role berhasil diperbarui.']);
        } else {
            echo json_encode(['error' => 'Gagal memperbarui role.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Role tidak valid.']);
    }
    exit;
}

// Jika tidak POST AJAX
http_response_code(400);
header('Content-Type: application/json');
echo json_encode(['error' => 'Permintaan tidak valid.']);
exit;
