<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../includes/db.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$available_circles = [];

// Deteksi apakah permintaan adalah AJAX JSON
$is_ajax = $_SERVER['REQUEST_METHOD'] === 'POST' &&
           strpos($_SERVER["CONTENT_TYPE"] ?? '', "application/json") !== false;

if ($is_ajax) {
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents("php://input"), true);

    $circle_id = intval($input['circle_id'] ?? 0);

    if (!$circle_id) {
        echo json_encode(['error' => 'ID circle tidak valid.']);
        exit;
    }

    // Cek apakah user sudah tergabung
    $check = $conn->prepare("SELECT id FROM circle_members WHERE user_id = ? AND circle_id = ?");
    $check->bind_param("ii", $user_id, $circle_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $check->close();
        echo json_encode(['error' => 'Kamu sudah tergabung dalam circle ini.']);
        exit;
    }
    $check->close();

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
            $exist->close();
            echo json_encode(['error' => 'Kamu sudah mengajukan permintaan ke circle ini.']);
            exit;
        }
        $exist->close();

        // Kirim permintaan
        $req = $conn->prepare("INSERT INTO circle_requests (user_id, circle_id) VALUES (?, ?)");
        $req->bind_param("ii", $user_id, $circle_id);
        if ($req->execute()) {
            echo json_encode(['success' => 'Permintaan bergabung telah dikirim. Menunggu persetujuan.']);
        } else {
            echo json_encode(['error' => 'Gagal mengirim permintaan.']);
        }
        $req->close();

    } else {
        // Circle publik: langsung gabung
        $join = $conn->prepare("INSERT INTO circle_members (user_id, circle_id) VALUES (?, ?)");
        $join->bind_param("ii", $user_id, $circle_id);
        if ($join->execute()) {
            echo json_encode(['success' => 'Berhasil bergabung dengan circle.']);
        } else {
            echo json_encode(['error' => 'Gagal bergabung dengan circle.']);
        }
        $join->close();
    }

    exit;
}

// --- GET: Ambil daftar circle yang belum diikuti DAN belum diajukan ---
$stmt = $conn->prepare("
    SELECT 
        c.id,
        c.name,
        c.description,
        c.is_private,
        i.name AS interest_name,
        (SELECT COUNT(*) FROM circle_members cm WHERE cm.circle_id = c.id) AS member_count
    FROM circles c
    LEFT JOIN interests i ON c.interest_id = i.id
    WHERE c.id NOT IN (
        SELECT circle_id FROM circle_members WHERE user_id = ?
        UNION
        SELECT circle_id FROM circle_requests WHERE user_id = ?
    )
"); 
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $available_circles[] = $row;
}

$stmt->close();
