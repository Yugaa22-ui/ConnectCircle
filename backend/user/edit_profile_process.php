<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../includes/db.php';

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Ambil data lama
$stmt = $conn->prepare("SELECT username, city, profession, bio, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $city, $profession, $bio, $profile_picture);
$stmt->fetch();
$stmt->close();

// Proses update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_username   = trim($_POST['username']);
    $new_city       = trim($_POST['city']);
    $new_profession = trim($_POST['profession']);
    $new_bio        = trim($_POST['bio']);

    if (empty($new_username)) {
        $error = "Username tidak boleh kosong.";
    } else {
        // Upload foto jika ada
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
            $file = $_FILES['profile_picture'];
            $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array(strtolower($ext), $allowed)) {
                $new_filename = 'user_' . $user_id . '_' . time() . '.' . $ext;
                $upload_path = '../../uploads/' . $new_filename;

                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    // Hapus foto lama jika ada
                    if ($profile_picture && file_exists('../../uploads/' . $profile_picture)) {
                        unlink('../../uploads/' . $profile_picture);
                    }
                    $profile_picture = $new_filename;
                } else {
                    $error = "Gagal upload foto.";
                }
            } else {
                $error = "Format file tidak didukung.";
            }
        }

        if (empty($error)) {
            $stmt = $conn->prepare("UPDATE users SET username = ?, city = ?, profession = ?, bio = ?, profile_picture = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $new_username, $new_city, $new_profession, $new_bio, $profile_picture, $user_id);

            if ($stmt->execute()) {
                $success = "Profil berhasil diperbarui.";
                $username = $new_username;
                $city = $new_city;
                $profession = $new_profession;
                $bio = $new_bio;
            } else {
                $error = "Gagal memperbarui profil.";
            }

            $stmt->close();
        }
    }
}
