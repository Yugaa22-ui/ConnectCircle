<?php
function assign_badges($conn, $user_id) {
    // Cek jumlah posting
    $stmt = $conn->prepare("SELECT COUNT(*) FROM posts WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($post_count);
    $stmt->fetch();
    $stmt->close();

    // Cek jumlah circle
    $stmt = $conn->prepare("SELECT COUNT(*) FROM circle_members WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($circle_count);
    $stmt->fetch();
    $stmt->close();

    // Cek badge yang sudah dimiliki
    $owned = [];
    $check = $conn->prepare("SELECT badge_id FROM user_badges WHERE user_id = ?");
    $check->bind_param("i", $user_id);
    $check->execute();
    $result = $check->get_result();
    while ($row = $result->fetch_assoc()) {
        $owned[] = $row['badge_id'];
    }
    $check->close();

    // Ambil semua badge yang tersedia
    $badges = $conn->query("SELECT * FROM badges");
    while ($badge = $badges->fetch_assoc()) {
        $badge_id = $badge['id'];
        $name = $badge['name'];

        // Cek syarat per badge
        $eligible = false;
        if ($name === 'Aktif Diskusi' && $post_count >= 10) {
            $eligible = true;
        } elseif ($name === 'Rajin Berjejaring' && $circle_count >= 3) {
            $eligible = true;
        } elseif ($name === 'Pengguna Super Aktif' && $post_count >= 20 && $circle_count >= 5) {
            $eligible = true;
        }

        // Jika eligible dan belum dimiliki
        if ($eligible && !in_array($badge_id, $owned)) {
            $grant = $conn->prepare("INSERT INTO user_badges (user_id, badge_id) VALUES (?, ?)");
            $grant->bind_param("ii", $user_id, $badge_id);
            $grant->execute();
            $grant->close();
        }
    }
}
?>
