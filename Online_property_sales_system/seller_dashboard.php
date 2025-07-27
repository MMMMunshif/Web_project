<?php
session_start();
require 'config.php'; // Include your database connection settings

// Ensure the seller is logged in
if (!isset($_SESSION['seller_id'])) {
    header('Location: seller_login.php');
    exit;
}

$seller_id = $_SESSION['seller_id'];

// Fetch seller data from the database
$stmt = $pdo->prepare('SELECT * FROM sellers WHERE id = ?');
$stmt->execute([$seller_id]);
$seller = $stmt->fetch();

// Handle form submission for updating seller information
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_business_name = $_POST['business_name'];
    $new_email = $_POST['email'];

    // Validate input
    if (empty($new_business_name) || empty($new_email)) {
        $error = "All fields are required.";
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Update seller information
        $stmt = $pdo->prepare('UPDATE sellers SET business_name = ?, email = ? WHERE id = ?');
        $stmt->execute([$new_business_name, $new_email, $seller_id]);

        if ($stmt) {
            $success = "Information updated successfully!";
            $_SESSION['seller_name'] = $new_business_name; // Update session name if needed
        } else {
            $error = "Error updating information.";
        }
    }
}

// Handle account deletion
if (isset($_POST['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM sellers WHERE id = ?');
    $stmt->execute([$seller_id]);

    session_destroy();
    header('Location: seller-register.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
    <link rel="stylesheet" href="Styles/seller-dashboard-style.css">
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
<h2>Seller Dashboard</h2>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?= $error ?></p>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <p style="color: green;"><?= $success ?></p>
<?php endif; ?>

<h3>Update Information</h3>

<form method="POST" action="seller_dashboard.php">
    <label for="business_name">Business Name:</label>
    <input type="text" name="business_name" id="business_name" value="<?= htmlspecialchars($seller['business_name']) ?>" required><br><br>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="<?= htmlspecialchars($seller['email']) ?>" required><br><br>

    <input type="submit" value="Update Information">
</form>

<h3>Create a New Ad</h3>
    <a href="create-property.php" class="create-ad-btn">Create an Ad</a>


<h3>Delete Account</h3>

<form method="POST">
    <input type="submit" name="delete" value="Delete Account" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
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
