<?php
// Database connection
$db = new mysqli('localhost', 'root', 'root', 'librarydb');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Check if ID is provided and is numeric
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid user ID");
}

$id = (int)$_GET['id'];

// Delete the book
$query = "DELETE FROM users WHERE id = $id";
if ($db->query($query)) {
    header(header: "Location: admin.php?msg=deleted");
    exit();
} else {
    echo "Error deleting user";
}

$db->close();
?>





