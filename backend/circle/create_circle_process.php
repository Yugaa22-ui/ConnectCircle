<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../includes/db.php';

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';
$circle_name = '';
$description = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $circle_name = trim($_POST['circle_name']);
    $description = trim($_POST['description']);

    if (empty($circle_name)) {
        $error = "Tolong isi nama circle terlebih dahulu.";
    } elseif (strlen($circle_name) < 3) {
        $error = "Nama circle minimal 3 karakter.";
    } else {
        // Cek apakah nama circle sudah ada
        $check = $conn->prepare("SELECT id FROM circles WHERE name = ?");
        $check->bind_param("s", $circle_name);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Nama circle \"$circle_name\" sudah digunakan. Silakan pilih nama lain.";
            $check->close();
        } else {
            $check->close();

            // Simpan circle
            $stmt = $conn->prepare("INSERT INTO circles (name, description, creator_id) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $circle_name, $description, $user_id);

            if ($stmt->execute()) {
                $circle_id = $stmt->insert_id;

                // Tambahkan ke circle_members
                $member_stmt = $conn->prepare("INSERT INTO circle_members (user_id, circle_id) VALUES (?, ?)");
                $member_stmt->bind_param("ii", $user_id, $circle_id);
                $member_stmt->execute();
                $member_stmt->close();

                $success = "Circle \"$circle_name\" berhasil dibuat!";
                $circle_name = '';
                $description = '';
            } else {
                $error = "Terjadi kesalahan saat membuat circle.";
            }

            $stmt->close();
        }
    }
}
