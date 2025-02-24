<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$db = new mysqli('localhost', 'root', 'root', 'librarydb');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$book_id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$book_id) {
    die("Book ID not provided");
}

// Check if book is available
$book = $db->query("SELECT * FROM books WHERE book_id = $book_id AND status = 'available'")->fetch_assoc();
if (!$book) {
    die("Book not available for borrowing");
}

// Set borrow period (e.g., 14 days)
$borrow_date = date(format: 'Y-m-d');
$return_date = date('Y-m-d', strtotime('+14 days'));

// Create borrowing record
$stmt = $db->prepare("INSERT INTO borrowingst (user_id, book_isbn, borrow_date, return_date) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiss", $user_id, $book_id, $borrow_date, $return_date);

if ($stmt->execute()) {
    // Update book status
    $db->query("UPDATE books SET status = 'borrowed' WHERE book_id = $book_id");
    header('Location: userpanel.php?success=1');
} else {
    echo "Error borrowing book: " . $db->error;
}

$stmt->close();
$db->close();
?>
