<?php
session_start();
require 'config.php'; // Include your database connection

// Ensure the seller is logged in
if (!isset($_SESSION['seller_id'])) {
    header('Location: seller_login.php');
    exit;
}

$seller_id = $_SESSION['seller_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Validate input
    if (!empty($title) && !empty($description) && !empty($price)) {
        // Insert the new property ad into the database
        $stmt = $pdo->prepare('INSERT INTO property_ads (seller_id, title, description, price) VALUES (?, ?, ?, ?)');
        $stmt->execute([$seller_id, $title, $description, $price]);
        $success = "Property ad created successfully!";
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Property Ad</title>
    <link rel="stylesheet" href="Styles/create-property-style.css">
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

<h2>Create Property Ad</h2>

<?php if (!empty($success)): ?>
    <p style="color: green;"><?= $success ?></p>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <p style="color: red;"><?= $error ?></p>
<?php endif; ?>

<form method="POST" action="create-property.php">
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" required><br><br>

    <label for="description">Description:</label>
    <textarea name="description" id="description" rows="5" required></textarea><br><br>

    <label for="price">Price (Rs):</label>
    <input type="number" step="0.01" name="price" id="price" required><br><br>

    <input type="submit" value="Create Ad">
</form>

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
