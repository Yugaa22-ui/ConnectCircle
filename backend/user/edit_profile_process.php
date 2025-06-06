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
$stmt->bind_result($old_username, $old_city, $old_profession, $old_bio, $old_profile_picture);
$stmt->fetch();
$stmt->close();

// Tetapkan nilai default agar bisa digunakan di UI (form input)
$username = $old_username;
$city = $old_city;
$profession = $old_profession;
$bio = $old_bio;
$profile_picture = $old_profile_picture; // default tetap
$uploaded_new_photo = false;

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

                $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/connectcircle/assets/uploads/img/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                $upload_path = $upload_dir . $new_filename;

                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    // Hapus foto lama jika ada
                    if ($old_profile_picture && file_exists($upload_dir . $old_profile_picture)) {
                        unlink($upload_dir . $old_profile_picture);
                    }
                    $profile_picture = $new_filename;
                    $uploaded_new_photo = true;
                } else {
                    $error = "Gagal upload foto.";
                }
            } else {
                $error = "Format file tidak didukung.";
            }
        }

        // ✅ Cek apakah data benar-benar berubah
        if (
            !$uploaded_new_photo &&
            $new_username === $old_username &&
            $new_city === $old_city &&
            $new_profession === $old_profession &&
            $new_bio === $old_bio
        ) {
            $error = "Tidak ada perubahan yang dilakukan.";
        }

        // Jika ada perubahan dan tidak ada error
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
