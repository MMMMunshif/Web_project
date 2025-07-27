<?php
session_start();
require 'config.php'; // Include your database connection

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    echo "User not logged in. Redirecting to login...";
    header('Location: login.php');
    exit;
}

// Fetch the user's ID from the session
$user_id = $_SESSION['id'];

echo "User ID to be deleted: " . $user_id . "<br>"; // Debugging

// Check if the form is submitted (i.e., the delete button is clicked)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    echo "Delete form submitted.<br>"; // Debugging

    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Delete related records from the inquiries table
        $stmt = $pdo->prepare('DELETE FROM inquiries WHERE user_id = ?');
        $stmt->execute([$user_id]);
        echo "Related inquiries deleted.<br>"; // Debugging

        // Now delete the user from the users table
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $deleteResult = $stmt->execute([$user_id]);

        if ($deleteResult) {
            echo "Account deleted successfully.<br>"; // Debugging
            // Destroy the session to log the user out
            session_destroy();
            echo "Session destroyed. Redirecting to login...<br>"; // Debugging

            // Commit the transaction
            $pdo->commit();

            // Redirect to login page
            header('Location: login.php');
            exit;
        } else {
            echo "Error: Failed to delete your account."; // Debugging
        }
    } catch (Exception $e) {
        // Rollback the transaction if an error occurs
        $pdo->rollBack();
        echo "Error: " . $e->getMessage(); // Debugging
    }
} else {
    echo "Form not submitted correctly."; // Debugging
}
?>
