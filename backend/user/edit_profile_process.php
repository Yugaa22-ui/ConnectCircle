<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../includes/db.php';

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Ambil data lama
$stmt = $conn->prepare("SELECT username, email, city, profession, bio, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($old_username, $old_email, $old_city, $old_profession, $old_bio, $old_profile_picture);
$stmt->fetch();
$stmt->close();

$username = $old_username;
$email = $old_email;
$city = $old_city;
$profession = $old_profession;
$bio = $old_bio;
$profile_picture = $old_profile_picture;
$uploaded_new_photo = false;

// proses update mengisi form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_username   = trim($_POST['username']);
    $new_email      = trim($_POST['email']);
    $new_city       = trim($_POST['city']);
    $new_profession = trim($_POST['profession']);
    $new_bio        = trim($_POST['bio']);

    if (empty($new_username) || empty($new_email)) {
        $error = "Username dan Email wajib diisi.";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
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
                    // hapus foto lama jika ada
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

        // Cek apakah ada perubahan
        if (
            !$uploaded_new_photo &&
            $new_username === $old_username &&
            $new_email === $old_email &&
            $new_city === $old_city &&
            $new_profession === $old_profession &&
            $new_bio === $old_bio
        ) {
            $error = "Tidak ada perubahan yang dilakukan.";
        }

        // Jika ada perubahan dan tidak ada error
        if (empty($error)) {
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, city = ?, profession = ?, bio = ?, profile_picture = ? WHERE id = ?");
            $stmt->bind_param("ssssssi", $new_username, $new_email, $new_city, $new_profession, $new_bio, $profile_picture, $user_id);

            if ($stmt->execute()) {
                $success = "Profil berhasil diperbarui.";
                $username = $new_username;
                $email = $new_email;
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
?>
