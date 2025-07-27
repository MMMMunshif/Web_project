<?php
require 'config.php'; // Include your database connection

// Fetch all contact entries from the database
$stmt = $pdo->prepare('SELECT * FROM contacts ORDER BY created_at DESC');
$stmt->execute();
$contacts = $stmt->fetchAll();

// Handle deletion of a contact entry
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare('DELETE FROM contacts WHERE id = ?');
    $stmt->execute([$id]);
    header('Location: admin-dashboard.php');
    exit;
}

// Handle form submission for editing contact entries
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $stmt = $pdo->prepare('UPDATE contacts SET name = ?, email = ?, message = ? WHERE id = ?');
    $stmt->execute([$name, $email, $message, $id]);
    header('Location: admin-dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="Styles/admin-dashboard.css">
</head>
<body>

<h1>Admin Dashboard - Contact Entries</h1>

<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($contacts as $contact): ?>
            <tr>
                <td><?= $contact['id'] ?></td>
                <td><?= htmlspecialchars($contact['name']) ?></td>
                <td><?= htmlspecialchars($contact['email']) ?></td>
                <td><?= htmlspecialchars($contact['message']) ?></td>
                <td><?= $contact['created_at'] ?></td>
                <td>
                    <a href="admin-dashboard.php?edit=<?= $contact['id'] ?>">Edit</a> |
                    <a href="admin-dashboard.php?delete=<?= $contact['id'] ?>" onclick="return confirm('Are you sure you want to delete this entry?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if (isset($_GET['edit'])): 
    $editId = $_GET['edit'];
    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE id = ?');
    $stmt->execute([$editId]);
    $contactToEdit = $stmt->fetch();
?>
    <h2>Edit Contact Entry</h2>
    <form method="POST" action="admin-dashboard.php">
        <input type="hidden" name="id" value="<?= $contactToEdit['id'] ?>">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($contactToEdit['name']) ?>" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($contactToEdit['email']) ?>" required><br><br>

        <label for="message">Message:</label><br>
        <textarea name="message" id="message" rows="5" required><?= htmlspecialchars($contactToEdit['message']) ?></textarea><br><br>

        <input type="submit" value="Update">
    </form>
<?php endif; ?>

</body>
</html>
