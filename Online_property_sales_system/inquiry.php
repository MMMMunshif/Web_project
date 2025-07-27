<?php
session_start();
require 'config.php'; // Your database connection file

// Check if the user is logged in (use 'id' instead of 'user_id')
if (!isset($_SESSION['id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit;
}

// Now we are sure that the user is logged in
$user_id = $_SESSION['id']; // Use 'id' from session as the user ID

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $property_id = $_POST['property'];
    $message = $_POST['message'];

    // Validate form data (you can add more validation as needed)
    if (empty($name) || empty($email) || empty($property_id) || empty($message)) {
        $error = 'All fields are required.';
    } else {
        // Prepare and execute the insert query (save the inquiry in the database)
        $stmt = $pdo->prepare('INSERT INTO inquiries (user_id, name, email, property_id, message) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$user_id, $name, $email, $property_id, $message]);

        if ($stmt) {
            $success = "Your inquiry has been submitted successfully!";
        } else {
            $error = "There was an error submitting your inquiry.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Inquiry</title>
    <link rel="stylesheet" href="Styles/inquiry.css">
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

<main>
    <h1>Property Inquiry</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <p style="color: green;"><?= $success ?></p>
    <?php endif; ?>

    <form action="inquiry.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="property">Property ID:</label>
        <input type="text" id="property" name="property" required><br><br>

        <textarea id="message" name="message" rows="5" required placeholder="Enter your inquiry..."></textarea><br><br>

        <input type="submit" value="Submit Inquiry">
    </form>
</main>

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
