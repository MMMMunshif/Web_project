<?php
session_start();
require 'config.php'; // Include your database connection settings

// Initialize variables
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch the seller from the database
    $stmt = $pdo->prepare('SELECT * FROM sellers WHERE email = ?');
    $stmt->execute([$email]);
    $seller = $stmt->fetch();

    // Verify password
    if ($seller && password_verify($password, $seller['password'])) {
        // Set session variables
        $_SESSION['seller_id'] = $seller['id'];
        $_SESSION['seller_name'] = $seller['business_name'];

        // Redirect to the dashboard
        header('Location: seller_dashboard.php');
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Login</title>
    <link rel="stylesheet" href="Styles/seller-login-styles.css">
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
    
    <h2>Seller Login</h2>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?= $error ?></p>
<?php endif; ?>

<form method="POST" action="seller_login.php">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required><br><br>

    <input type="submit" value="Login">
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
