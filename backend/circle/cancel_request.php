<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../auth/auth_check.php'; // sudah cukup
include __DIR__ . '/../../includes/db.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$circle_id = $input['circle_id'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

if (!$circle_id || !$user_id) {
  echo json_encode(['success' => false, 'error' => 'Data tidak lengkap']);
  exit;
}

$stmt = $conn->prepare("DELETE FROM circle_requests WHERE circle_id = ? AND user_id = ? AND status = 'pending'");
$stmt->bind_param("ii", $circle_id, $user_id);
$stmt->execute();

$response = [
  'executed' => true,
  'affected_rows' => $stmt->affected_rows,
  'debug' => [
    'circle_id' => $circle_id,
    'user_id' => $user_id,
    'error' => $stmt->error
  ]
];

if ($stmt->affected_rows > 0) {
  $response['success'] = true;
} else {
  $response['success'] = false;
  $response['error'] = 'Query dijalankan tapi tidak menghapus apa pun.';
}

echo json_encode($response);
