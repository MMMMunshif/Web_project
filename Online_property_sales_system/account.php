<?php
session_start();
require 'config.php'; // Include your database connection settings

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

// Fetch the user's details from the database using the session's `id`
$user_id = $_SESSION['id'];

$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    // If user data is not found, redirect to login
    header('Location: login.php');
    exit;
}

// Handle account deletion
if (isset($_POST['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $stmt->execute([$user_id]);

    // If successful, destroy the session and redirect to login
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Account</title>
    <link rel="stylesheet" href="Styles/account.css">
    <link rel="stylesheet" href="Styles/header.css">
    <link rel="stylesheet" href="Styles/footer.css">
    <script>
        // JavaScript function to confirm deletion
        function confirmDelete(event) {
            event.preventDefault(); // Prevent form submission
            const confirmed = confirm('Are you sure you want to delete your account? This action cannot be undone.');
            if (confirmed) {
                document.getElementById('deleteForm').submit(); // Submit form if confirmed
            }
        }
    </script>
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

<h2>Your Account</h2>

<div class="account-details">
    <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>

    <!-- Link to edit account details -->
    <a href="edit-account.php" class="edit-btn">Edit Account</a>
    
    <!-- Form to delete account -->
    <form id="deleteForm" method="POST" action="delete-account.php">
        <input type="submit" name="delete" value="Delete Account" class="delete-btn" onclick="confirmDelete(event)">
    </form>

    <script>
    function confirmDelete(event) {
        const confirmed = confirm("Are you sure you want to delete your account?");
        if (!confirmed) {
            event.preventDefault(); // Prevent form submission if user cancels
        }
    }
</script>


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
