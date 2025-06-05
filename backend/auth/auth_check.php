<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    headerheader("Location: ../../auth/login.php");    
    exit;
}
