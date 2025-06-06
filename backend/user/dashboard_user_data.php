<?php
include_once '../backend/auth/auth_check.php'; // cek login
include '../includes/db.php';

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
