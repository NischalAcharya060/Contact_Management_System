<?php
require_once 'server.php';

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Destroy the session and redirect to the login page
session_destroy();
header("Location: admin_login.php");
exit();
