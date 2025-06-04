<?php
$host = "localhost";        // biasanya localhost
$user = "root";             // default user XAMPP
$pass = "";                 // password kosong (default XAMPP)
$db   = "connectcircle_db"; // nama database yang kamu buat

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Opsional: atur charset ke UTF-8
$conn->set_charset("utf8");
?>