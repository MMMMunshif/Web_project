<?php
session_start();
require 'config.php'; // Include your database connection settings

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

// Get the logged-in user's ID from the session
$user_id = $_SESSION['id'];

// Fetch the user's inquiries from the database
$stmt = $pdo->prepare('SELECT * FROM inquiries WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$user_id]);
$inquiries = $stmt->fetchAll();

// Handle inquiry deletion
if (isset($_GET['delete'])) {
    $inquiry_id = $_GET['delete'];
    $stmt = $pdo->prepare('DELETE FROM inquiries WHERE id = ? AND user_id = ?');
    $stmt->execute([$inquiry_id, $user_id]);
    header('Location: user-inquiries.php'); // Refresh the page after deletion
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Inquiries</title>
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

<h2>Your Inquiries</h2>

<!-- Check if there are any inquiries -->
<?php if (empty($inquiries)): ?>
    <p>You have not submitted any inquiries yet.</p>
<?php else: ?>
    <!-- Display the inquiries in a table -->
    <table>
        <thead>
            <tr>
                <th>Message</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inquiries as $inquiry): ?>
                <tr>
                    <td><?= htmlspecialchars($inquiry['message']) ?></td>
                    <td><?= htmlspecialchars($inquiry['created_at']) ?></td>
                    <td>
                        <!-- Edit and Delete Links -->
                        <a href="edit-inquiry.php?id=<?= $inquiry['id'] ?>" class="edit-btn">Edit</a>
                        <a href="user-inquiries.php?delete=<?= $inquiry['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this inquiry?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</body>

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

</html>
