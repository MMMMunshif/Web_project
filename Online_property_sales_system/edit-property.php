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

// Fetch the property ad to edit
$stmt = $pdo->prepare('SELECT * FROM property_ads WHERE id = ? AND seller_id = ?');
$stmt->execute([$property_id, $seller_id]);
$property = $stmt->fetch();

if (!$property) {
    die('Property ad not found or you do not have permission to edit this ad.');
}

// Handle form submission for updating the property ad
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Validate input
    if (!empty($title) && !empty($description) && !empty($price)) {
        // Update the property ad in the database
        $stmt = $pdo->prepare('UPDATE property_ads SET title = ?, description = ?, price = ? WHERE id = ? AND seller_id = ?');
        $stmt->execute([$title, $description, $price, $property_id, $seller_id]);
        $success = "Property ad updated successfully!";
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
    <title>Edit Property Ad</title>
    <link rel="stylesheet" href="Styles/edit_account.css">
</head>
<body>

<h2>Edit Property Ad</h2>

<?php if (!empty($success)): ?>
    <p style="color: green;"><?= $success ?></p>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <p style="color: red;"><?= $error ?></p>
<?php endif; ?>

<form method="POST" action="edit-property.php?id=<?= $property_id ?>">
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" value="<?= htmlspecialchars($property['title']) ?>" required><br><br>

    <label for="description">Description:</label>
    <textarea name="description" id="description" rows="5" required><?= htmlspecialchars($property['description']) ?></textarea><br><br>

    <label for="price">Price (Rs):</label>
    <input type="number" step="0.01" name="price" id="price" value="<?= htmlspecialchars($property['price']) ?>" required><br><br>

    <input type="submit" value="Update Ad">
</form>

</body>
</html>
