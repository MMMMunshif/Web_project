<?php
session_start(); // Ensure session is started

require 'config.php'; // Include your database connection

// Fetch all property ads from the database
$stmt = $pdo->prepare('SELECT property_ads.*, sellers.business_name FROM property_ads JOIN sellers ON property_ads.seller_id = sellers.id ORDER BY created_at DESC');
$stmt->execute();
$properties = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listings</title>
    <link rel="stylesheet" href="Styles/property.css">
    <link rel="stylesheet" href="Styles/header.css">
    <link rel="stylesheet" href="Styles/footer.css">
</head>
<body>

<header>
        <div class="logo">
            <img src="Images/logo.png" alt="Logo">
        </div>
        <nav class="main-menu">
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="property-listings.php">Property Listing</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
        <div class="auth-links">
            <a href="login.php" class="login-btn">Login</a>
            <a href="register.php" class="register-btn">Register</a>
        </div>
    </header>

<h2>Property Listings</h2>

<div class="property-grid">
    <?php foreach ($properties as $property): ?>
        <div class="property">
            <h3><?= htmlspecialchars($property['title']) ?></h3>
            <p><?= htmlspecialchars($property['description']) ?></p>
            <p><strong>Price:</strong> Rs <?= htmlspecialchars($property['price']) ?></p>
            <p><strong>Seller:</strong> <?= htmlspecialchars($property['business_name']) ?></p>
            <p><strong>Posted on:</strong> <?= $property['created_at'] ?></p>

            <?php if (isset($_SESSION['seller_id']) && $_SESSION['seller_id'] == $property['seller_id']): ?>
                <!-- Show edit and delete options only for the seller who created the ad -->
                <a href="edit-property.php?id=<?= $property['id'] ?>">Edit</a> |
                <a href="delete-property.php?id=<?= $property['id'] ?>" onclick="return confirm('Are you sure you want to delete this property?');">Delete</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<footer>
        <div class="footer-container">
            <div class="footer-column">
                <img src="Images/logo.png" alt="Logo" class="footer-logo">
                <p>Your property classifieds website for buying and selling properties easily.</p>
            </div>
    
            <div class="footer-column">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="property-listings.php">Property Listings</a></li>
                    <li><a href="inquiry.php">inquiry</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
            </div>
    
            <div class="footer-column">
                <h3>Seller Links</h3>
                <ul class="social-links">
                    <li><a href="seller_login.php">Seller Login</a></li>
                    <li><a href="seller-register.php">Seller Register</a></li>
                    <li><a href="seller_dashboard.php">Seller Dashboard</a></li>
                    <li><a href="user-inquiries.php">Inquiries</a></li>
                </ul>
            </div>
    
            <div class="footer-column">
                <h3>Contact Info</h3>
                <ul class="contact-info">
                    <li><strong>Email:</strong> info@real.com</li>
                    <li><strong>Phone:</strong> +1 234 567 890</li>
                    <li><strong>Address:</strong> 123 First Lane, Colombo</li>
                </ul>
            </div>
        </div>
    </footer>

</body>
</html>
