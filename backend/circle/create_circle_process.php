<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');
include_once '../../includes/db.php';

$user_id = $_SESSION['user_id'] ?? null;
$circle_name = trim($_POST['circle_name'] ?? '');
$description = trim($_POST['description'] ?? '');

$errors = [];
$response = [];

// Validasi
if (empty($circle_name)) {
    $errors['circle_name'] = "Nama Circle wajib diisi!";
} elseif (strlen($circle_name) < 3) {
    $errors['circle_name'] = "Nama Circle minimal 3 karakter.";
}

if (empty($description)) {
    $errors['description'] = "Deskripsi wajib diisi!";
}

// Jika ada error validasi
if (!empty($errors)) {
    echo json_encode(['errors' => $errors]);
    exit;
}

// Cek apakah nama sudah ada
$check = $conn->prepare("SELECT id FROM circles WHERE name = ?");
$check->bind_param("s", $circle_name);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(['errors' => ['circle_name' => "Nama Circle \"$circle_name\" sudah digunakan."]]);
    $check->close();
    exit;
}
$check->close();

// Simpan ke DB
$stmt = $conn->prepare("INSERT INTO circles (name, description, creator_id) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $circle_name, $description, $user_id);

if ($stmt->execute()) {
    $circle_id = $stmt->insert_id;

    $member_stmt = $conn->prepare("INSERT INTO circle_members (user_id, circle_id) VALUES (?, ?)");
    $member_stmt->bind_param("ii", $user_id, $circle_id);
    $member_stmt->execute();
    $member_stmt->close();

    echo json_encode(['success' => "Circle \"$circle_name\" berhasil dibuat!"]);
} else {
    echo json_encode(['errors' => ['global' => "Terjadi kesalahan saat membuat circle."]]);
}
$stmt->close();
