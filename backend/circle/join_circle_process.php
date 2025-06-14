<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../includes/db.php';

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';
$available_circles = [];

// Proses ketika user ingin bergabung atau mengajukan
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['join']) && is_numeric($_GET['join'])) {
        $circle_id = intval($_GET['join']);

        // Cek apakah user sudah tergabung
        $check = $conn->prepare("SELECT id FROM circle_members WHERE user_id = ? AND circle_id = ?");
        $check->bind_param("ii", $user_id, $circle_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "Kamu sudah tergabung dalam circle ini.";
        } else {
            // Cek apakah circle private
            $priv = $conn->prepare("SELECT is_private FROM circles WHERE id = ?");
            $priv->bind_param("i", $circle_id);
            $priv->execute();
            $priv->bind_result($is_private);
            $priv->fetch();
            $priv->close();

            if ($is_private) {
                // Cek jika sudah mengirim request
                $exist = $conn->prepare("SELECT id FROM circle_requests WHERE user_id = ? AND circle_id = ?");
                $exist->bind_param("ii", $user_id, $circle_id);
                $exist->execute();
                $exist->store_result();

                if ($exist->num_rows > 0) {
                    $error = "Kamu sudah mengajukan permintaan ke circle ini.";
                } else {
                    $req = $conn->prepare("INSERT INTO circle_requests (user_id, circle_id) VALUES (?, ?)");
                    $req->bind_param("ii", $user_id, $circle_id);
                    if ($req->execute()) {
                        $success = "Permintaan bergabung telah dikirim. Menunggu persetujuan.";
                    } else {
                        $error = "Gagal mengirim permintaan.";
                    }
                    $req->close();
                }
                $exist->close();
            } else {
                // Public: langsung gabung
                $join = $conn->prepare("INSERT INTO circle_members (user_id, circle_id) VALUES (?, ?)");
                $join->bind_param("ii", $user_id, $circle_id);
                if ($join->execute()) {
                    $success = "Berhasil bergabung dengan circle!";
                } else {
                    $error = "Gagal bergabung dengan circle.";
                }
                $join->close();
            }
        }
        $check->close();
    }
}

// Ambil daftar circle yang belum diikuti user
$stmt = $conn->prepare("
    SELECT c.id, c.name, c.description, c.is_private,
        (SELECT COUNT(*) FROM circle_members cm WHERE cm.circle_id = c.id) AS member_count
    FROM circles c
    WHERE c.id NOT IN (
        SELECT circle_id FROM circle_members WHERE user_id = ?
    )
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $available_circles[] = $row;
}

$stmt->close();
?>
