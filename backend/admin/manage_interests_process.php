<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once '../backend/auth/auth_check.php';
include_once '../includes/db.php';

// Cek jika bukan admin / moderator
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'moderator') {
    echo "<script>alert('Hanya admin dan moderator yang dapat mengakses.'); window.location='../../admin/dashboard_admin.php';</script>";
    exit;
}

$success = '';
$error = '';

// Tambah minat baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_interest'])) {
    $new_interest = trim($_POST['new_interest']);

    if (empty($new_interest)) {
        $error = "Nama minat tidak boleh kosong.";
    } else {
        // Cek duplikat
        $check = $conn->prepare("SELECT id FROM interests WHERE name = ?");
        $check->bind_param("s", $new_interest);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Minat tersebut sudah ada.";
        } else {
            $insert = $conn->prepare("INSERT INTO interests (name) VALUES (?)");
            $insert->bind_param("s", $new_interest);
            if ($insert->execute()) {
                $success = "Minat berhasil ditambahkan.";
            } else {
                $error = "Gagal menambahkan minat.";
            }
            $insert->close();
        }

        $check->close();
    }
}

// Hapus minat
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    $delete = $conn->prepare("DELETE FROM interests WHERE id = ?");
    $delete->bind_param("i", $del_id);
    $delete->execute();
    $delete->close();
    $success = "Minat berhasil dihapus.";
}

// Ambil semua data minat
$all = $conn->query("SELECT * FROM interests ORDER BY name ASC");
