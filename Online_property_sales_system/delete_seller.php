<?php
// Include the database connection and start session
include 'db_connect.php';
session_start();

// Check if seller is logged in
if (!isset($_SESSION['seller_id'])) {
    header('Location: seller_login.php');
    exit();
}

$seller_id = $_SESSION['seller_id'];

// Delete seller account
$sql = "DELETE FROM sellers WHERE id = $seller_id";

if (mysqli_query($conn, $sql)) {
    // Destroy session and redirect to login
    session_destroy();
    header('Location: seller_login.php');
    exit();
} else {
    echo "Error deleting account: " . mysqli_error($conn);
}
