<?php
session_start();
require 'config.php'; // Include your database connection

// Ensure the seller is logged in
if (!isset($_SESSION['seller_id'])) {
    header('Location: seller-login.php');
    exit;
}

$seller_id = $_SESSION['seller_id'];
$property_id = $_GET['id'];

// Delete the property ad
$stmt = $pdo->prepare('DELETE FROM property_ads WHERE id = ? AND seller_id = ?');
$stmt->execute([$property_id, $seller_id]);

header('Location: property-listings.php');
exit;
?>
