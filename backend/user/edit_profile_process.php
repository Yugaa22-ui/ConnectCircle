<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../includes/db.php';

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

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

// Ambil minat yang sedang dipilih user
$interest_stmt = $conn->prepare("SELECT interest_id FROM user_interests WHERE user_id = ?");
$interest_stmt->bind_param("i", $user_id);
$interest_stmt->execute();
$interest_result = $interest_stmt->get_result();
$user_interests = $interest_result->fetch_all(MYSQLI_ASSOC);
$interest_stmt->close();

// Ambil semua minat
$all_interest_result = $conn->query("SELECT id, name FROM interests");
$all_interests = $all_interest_result->fetch_all(MYSQLI_ASSOC);

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_username   = trim($_POST['username']);
    $new_email      = trim($_POST['email']);
    $new_city       = trim($_POST['city']);
    $new_profession = trim($_POST['profession']);
    $new_bio        = trim($_POST['bio']);
    $new_interests  = $_POST['interests'] ?? [];

    // === Validasi ===
    if (empty($new_username) || empty($new_email)) {
        $error = "Username dan Email wajib diisi.";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif (count($new_interests) < 1) {
        $error = "Pilih minimal 1 minat.";
    } elseif (count($new_interests) > 3) {
        $error = "Maksimal hanya bisa memilih 3 minat.";
    } else {
        // Cek email unik jika email diubah
        if ($new_email !== $old_email) {
            $cek_email = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $cek_email->bind_param("si", $new_email, $user_id);
            $cek_email->execute();
            $cek_email->store_result();
            if ($cek_email->num_rows > 0) {
                $error = "Email sudah digunakan oleh pengguna lain.";
            }
            $cek_email->close();
        }

        // Jika belum ada error, lanjut upload
        if (empty($error)) {
            // === Cropper
            if (isset($_POST['cropped_image']) && !empty($_POST['cropped_image'])) {
                $data = $_POST['cropped_image'];
                list(, $data) = explode(',', $data);
                $data = base64_decode($data);

                $new_filename = 'user_' . $user_id . '_' . time() . '.png';
                $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/connectcircle/assets/uploads/img/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                file_put_contents($upload_dir . $new_filename, $data);

                if ($old_profile_picture && file_exists($upload_dir . $old_profile_picture)) {
                    unlink($upload_dir . $old_profile_picture);
                }

                $profile_picture = $new_filename;
                $uploaded_new_photo = true;
            }

            // === Upload Biasa
            if (
                isset($_FILES['profile_picture']) &&
                $_FILES['profile_picture']['error'] !== 4
            ) {
                $file = $_FILES['profile_picture'];
                $ext  = pathinfo($file['name'], PATHINFO_EXTENSION);
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array(strtolower($ext), $allowed)) {
                    $new_filename = 'user_' . $user_id . '_' . time() . '.' . $ext;
                    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/connectcircle/assets/uploads/img/';
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                    $upload_path = $upload_dir . $new_filename;

                    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
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
        }

        // Cek perubahan isi
        if (
            empty($error) &&
            !$uploaded_new_photo &&
            $new_username === $old_username &&
            $new_email === $old_email &&
            $new_city === $old_city &&
            $new_profession === $old_profession &&
            $new_bio === $old_bio &&
            array_column($user_interests, 'interest_id') === array_map('intval', $new_interests)
        ) {
            $error = "Tidak ada perubahan yang dilakukan.";
        }

        // === Simpan jika tidak error
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

                // Update minat
                $conn->query("DELETE FROM user_interests WHERE user_id = $user_id");
                $insert = $conn->prepare("INSERT INTO user_interests (user_id, interest_id) VALUES (?, ?)");
                foreach ($new_interests as $iid) {
                    $iid = intval($iid);
                    $insert->bind_param("ii", $user_id, $iid);
                    $insert->execute();
                }
                $insert->close();

                // Simpan ulang minat untuk UI
                $user_interests = array_map(function ($id) {
                    return ['interest_id' => $id];
                }, $new_interests);
            } else {
                $error = "Gagal memperbarui profil.";
            }

            $stmt->close();
        }
    }
}
?>
