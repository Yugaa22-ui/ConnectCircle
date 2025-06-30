<?php
if (session_status() === PHP_SESSION_NONE) session_start();
date_default_timezone_set('Asia/Makassar');
include_once '../includes/db.php';

$circle_id = isset($_GET['circle_id']) ? intval($_GET['circle_id']) : 0;
$user_id = $_SESSION['user_id'];

$circle_name = $circle_description = $rules = $msg = '';
$circle_detail = [];
$members = [];
$top_active = $newest = null;

// Validasi circle dan ambil data awal
$cek = $conn->prepare("SELECT name, description, creator_id, rules, is_private, interest_id FROM circles WHERE id = ?");
$cek->bind_param("i", $circle_id);
$cek->execute();
$cek->store_result();

if ($cek->num_rows === 0) {
    echo "<script>alert('Circle tidak ditemukan.'); window.location='view_circle.php';</script>";
    exit;
}

$cek->bind_result($circle_name, $circle_description, $creator_id, $rules, $current_private_status, $current_interest_id);
$cek->fetch();
$cek->close();

$is_private = $current_private_status;

// Ambil role user
$role_stmt = $conn->prepare("SELECT role FROM circle_members WHERE user_id = ? AND circle_id = ?");
$role_stmt->bind_param("ii", $user_id, $circle_id);
$role_stmt->execute();
$role_stmt->bind_result($role);
$role_stmt->fetch();
$role_stmt->close();

// Validasi akses
if ($creator_id !== $user_id && $role !== 'moderator') {
    echo "<script>alert('Hanya creator atau moderator yang dapat mengelola.'); window.location='view_circle.php';</script>";
    exit;
}

// Ambil semua minat
$interests = [];
$interest_stmt = $conn->prepare("SELECT id, name FROM interests ORDER BY name ASC");
$interest_stmt->execute();
$res = $interest_stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $interests[] = $row;
}
$interest_stmt->close();

// === Update circle ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['circle_name'], $_POST['circle_description'], $_POST['rules'], $_POST['interest_id'])) {
        // Hanya creator yang boleh update circle
        if ($user_id !== $creator_id) {
            $msg = "❌ Hanya creator yang dapat mengedit pengaturan circle.";
        } else {
            $new_name        = trim($_POST['circle_name']);
            $new_desc        = trim($_POST['circle_description']);
            $new_rules       = trim($_POST['rules']);
            $new_interest_id = intval($_POST['interest_id']);
            $new_is_private  = isset($_POST['is_private']) ? 1 : 0;

            $is_data_changed = (
                $new_name !== $circle_name ||
                $new_desc !== $circle_description ||
                $new_rules !== $rules ||
                $new_is_private != $current_private_status ||
                $new_interest_id != $current_interest_id
            );

            if ($is_data_changed) {
                $update = $conn->prepare("
                    UPDATE circles
                    SET name = ?, description = ?, rules = ?, is_private = ?, interest_id = ?
                    WHERE id = ?
                ");
                $update->bind_param("sssiii", $new_name, $new_desc, $new_rules, $new_is_private, $new_interest_id, $circle_id);
                if ($update->execute()) {
                    $circle_name = $new_name;
                    $circle_description = $new_desc;
                    $rules = $new_rules;
                    $current_private_status = $new_is_private;
                    $is_private = $new_is_private;
                    $current_interest_id = $new_interest_id;

                    $msg = "✅ Circle berhasil diperbarui.";
                } else {
                    $msg = "❌ Gagal memperbarui circle.";
                }
                $update->close();
            } else {
                $msg = "ℹ️ Tidak ada data yang diubah.";
            }
        }
    }

    // Aksi terhadap anggota
    if (isset($_POST['action']) && isset($_POST['member_id'])) {
        $member_id = intval($_POST['member_id']);
        $action = $_POST['action'];

        // Cek role target
        $target_stmt = $conn->prepare("SELECT role FROM circle_members WHERE user_id = ? AND circle_id = ?");
        $target_stmt->bind_param("ii", $member_id, $circle_id);
        $target_stmt->execute();
        $target_stmt->bind_result($target_role);
        $target_stmt->fetch();
        $target_stmt->close();

        // Creator boleh melakukan semua tindakan
        if ($user_id === $creator_id) {
            $allowed = true;
        } else {
            // Moderator hanya boleh kick/mute member
            if ($target_role === 'member' && in_array($action, ['kick', 'mute'])) {
                $allowed = true;
            } else {
                $allowed = false;
            }
        }

        if (!$allowed) {
            $msg = "❌ Anda tidak memiliki izin melakukan aksi ini.";
        } else {
            if ($action === 'kick') {
                $del = $conn->prepare("DELETE FROM circle_members WHERE user_id = ? AND circle_id = ?");
                $del->bind_param("ii", $member_id, $circle_id);
                $del->execute();
                $del->close();
                $msg = "🚫 Anggota dikeluarkan.";

                // Jika yang keluar adalah creator, pindah kepemilikan ke moderator pertama
                if ($member_id === $creator_id) {
                    $next_stmt = $conn->prepare("
                        SELECT user_id
                        FROM circle_members
                        WHERE circle_id = ? AND role = 'moderator'
                        ORDER BY joined_at ASC
                        LIMIT 1
                    ");
                    $next_stmt->bind_param("i", $circle_id);
                    $next_stmt->execute();
                    $next_stmt->bind_result($new_creator_id);
                    if ($next_stmt->fetch()) {
                        $update_creator = $conn->prepare("UPDATE circles SET creator_id = ? WHERE id = ?");
                        $update_creator->bind_param("ii", $new_creator_id, $circle_id);
                        $update_creator->execute();
                        $update_creator->close();
                        $msg .= " Creator diganti ke moderator pertama.";
                    }
                    $next_stmt->close();
                }

            } elseif ($action === 'mute' && isset($_POST['mute_duration'])) {
                $duration = intval($_POST['mute_duration']);
                $until = date('Y-m-d H:i:s', strtotime("+$duration hour"));
                $mute = $conn->prepare("REPLACE INTO circle_mutes (user_id, circle_id, until_time) VALUES (?, ?, ?)");
                $mute->bind_param("iis", $member_id, $circle_id, $until);
                $mute->execute();
                $mute->close();
                $msg = "🔇 Anggota dimute selama $duration jam.";

            } elseif ($action === 'promote') {
                $promote = $conn->prepare("UPDATE circle_members SET role = 'moderator' WHERE user_id = ? AND circle_id = ?");
                $promote->bind_param("ii", $member_id, $circle_id);
                $promote->execute();
                $promote->close();
                $msg = "⭐ Anggota dipromosikan menjadi moderator.";

            } elseif ($action === 'demote') {
                $demote = $conn->prepare("UPDATE circle_members SET role = 'member' WHERE user_id = ? AND circle_id = ?");
                $demote->bind_param("ii", $member_id, $circle_id);
                $demote->execute();
                $demote->close();
                $msg = "🔽 Jabatan moderator telah dicabut.";
            }
        }
    }
}

// Ambil daftar anggota
$get_members = $conn->prepare("
    SELECT u.id, u.username, u.profile_picture, cm.role, cm.joined_at
    FROM circle_members cm
    JOIN users u ON cm.user_id = u.id
    WHERE cm.circle_id = ?
    ORDER BY cm.joined_at ASC
");
$get_members->bind_param("i", $circle_id);
$get_members->execute();
$res = $get_members->get_result();
while ($row = $res->fetch_assoc()) {
    $members[] = $row;
}
$get_members->close();

// Anggota paling aktif
$top_stmt = $conn->prepare("
    SELECT u.username
    FROM posts p
    JOIN users u ON u.id = p.user_id
    WHERE p.circle_id = ?
    GROUP BY p.user_id
    ORDER BY COUNT(*) DESC
    LIMIT 1
");
$top_stmt->bind_param("i", $circle_id);
$top_stmt->execute();
$top_stmt->bind_result($top_active_user);
$top_stmt->fetch();
$top_active = $top_active_user ?: null;
$top_stmt->close();

// Anggota terbaru
$new_stmt = $conn->prepare("
    SELECT u.username
    FROM circle_members cm
    JOIN users u ON cm.user_id = u.id
    WHERE cm.circle_id = ?
    ORDER BY cm.joined_at DESC
    LIMIT 1
");
$new_stmt->bind_param("i", $circle_id);
$new_stmt->execute();
$new_stmt->bind_result($newest_user);
$new_stmt->fetch();
$newest = $newest_user ?: null;
$new_stmt->close();
