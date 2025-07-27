<?php
session_start();
require 'config.php'; // Include your database connection settings

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

// Get the logged-in user's ID and the inquiry ID from the URL
$user_id = $_SESSION['id'];
$inquiry_id = $_GET['id'] ?? null;

// Fetch the inquiry to be edited
$stmt = $pdo->prepare('SELECT * FROM inquiries WHERE id = ? AND user_id = ?');
$stmt->execute([$inquiry_id, $user_id]);
$inquiry = $stmt->fetch();

// Handle form submission for updating the inquiry
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_message = $_POST['message'];
    
    if (!empty($new_message)) {
        // Update the inquiry message
        $stmt = $pdo->prepare('UPDATE inquiries SET message = ? WHERE id = ? AND user_id = ?');
        $stmt->execute([$new_message, $inquiry_id, $user_id]);
        header('Location: user-inquiries.php'); // Redirect back to the inquiries page after updating
        exit;
    } else {
        $error = "Message cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Inquiry</title>
    <link rel="stylesheet" href="Styles/inquiry.css">
</head>
<body>

<h2>Edit Inquiry</h2>

<!-- Display error message if there is any -->
<?php if (isset($error)): ?>
    <p style="color: red;"><?= $error ?></p>
<?php endif; ?>

<!-- Form for editing the inquiry -->
<form method="POST">
    <label for="message">Message:</label>
    <textarea name="message" id="message" rows="5" required><?= htmlspecialchars($inquiry['message']) ?></textarea><br><br>
    <input type="submit" value="Update Inquiry">
</form>

<a href="user-inquiries.php">Back to Inquiries</a>

</body>
</html>
