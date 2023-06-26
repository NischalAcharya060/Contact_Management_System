<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "contact_management_system";

// Create the database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
