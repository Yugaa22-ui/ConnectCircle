<?php
session_start();
include_once '../../includes/db.php';

$user_id = $_SESSION['user_id'];

if (isset($_FILES['profile_pic'])) {
    $file = $_FILES['profile_pic'];
    $name = basename($file['name']);
    $ext = pathinfo($name, PATHINFO_EXTENSION);
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array(strtolower($ext), $allowed)) {
        $newName = 'user_' . $user_id . '_' . time() . '.' . $ext;
        $target = '../../uploads/' . $newName;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
            $stmt->bind_param("si", $newName, $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

header("Location: ../../user/profile.php");
exit;
