<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');
include_once '../../includes/db.php';

$user_id = $_SESSION['user_id'] ?? null;
$circle_name = trim($_POST['circle_name'] ?? '');
$description = trim($_POST['description'] ?? '');
$interest_id = $_POST['interest_id'] ?? '';

$errors = [];

// Validasi
if (empty($circle_name)) {
    $errors['circle_name'] = "Nama Circle wajib diisi!";
} elseif (strlen($circle_name) < 3) {
    $errors['circle_name'] = "Nama Circle minimal 3 karakter.";
}

if (empty($description)) {
    $errors['description'] = "Deskripsi wajib diisi!";
}

if (empty($interest_id)) {
    $errors['interest_id'] = "Wajib memilih 1 minat circle.";
}

// Jika ada error
if (!empty($errors)) {
    echo json_encode(['errors' => $errors]);
    exit;
}

// Cek nama unik
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

// Simpan circle
$stmt = $conn->prepare("INSERT INTO circles (name, description, creator_id, interest_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssii", $circle_name, $description, $user_id, $interest_id);

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
