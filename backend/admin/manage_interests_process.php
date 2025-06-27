<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once __DIR__ . "/../auth/auth_check.php";
include_once __DIR__ . "/../../includes/db.php";

// Cek hak akses
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'moderator') {
    if ($isAjax) {
        http_response_code(403);
        echo "Hanya admin dan moderator yang dapat mengakses.";
    } else {
        echo "<script>alert('Hanya admin dan moderator yang dapat mengakses.'); window.location='../../admin/dashboard_admin.php';</script>";
    }
    exit;
}

$success = '';
$error = '';

// Tambah minat baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_interest'])) {
    header('Content-Type: application/json');

    $new_interest = trim($_POST['new_interest']);

    if (empty($new_interest)) {
        echo json_encode(['error' => "Nama minat tidak boleh kosong."]);
        exit;
    }

    $check = $conn->prepare("SELECT id FROM interests WHERE name = ?");
    $check->bind_param("s", $new_interest);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo json_encode(['error' => "Minat tersebut sudah ada."]);
    } else {
        $insert = $conn->prepare("INSERT INTO interests (name) VALUES (?)");
        $insert->bind_param("s", $new_interest);
        if ($insert->execute()) {
            echo json_encode([
                'success' => "Minat berhasil ditambahkan.",
                'id' => $insert->insert_id,
                'name' => $new_interest
            ]);
        } else {
            echo json_encode(['error' => "Gagal menambahkan minat."]);
        }
        $insert->close();
    }
    $check->close();
    exit;
}

// Hapus minat
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    header('Content-Type: application/json');

    $del_id = intval($_GET['delete']);
    $delete = $conn->prepare("DELETE FROM interests WHERE id = ?");
    $delete->bind_param("i", $del_id);
    if ($delete->execute()) {
        echo json_encode(['success' => "Minat berhasil dihapus."]);
    } else {
        echo json_encode(['error' => "Gagal menghapus minat."]);
    }
    $delete->close();
    exit;
}

// Ambil semua data minat untuk tampilan
$all = $conn->query("SELECT * FROM interests ORDER BY name ASC");
