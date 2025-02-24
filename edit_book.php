<!DOCTYPE html>
<html>

<head>
    <title>Library Management System - Book Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

        .success {
            color: green;
            margin-bottom: 10px;
            text-align: center;
        }

        a {
            text-decoration: None;
            color: white;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php
        // Database connection
        $db = new mysqli('localhost', 'root', 'root', 'librarydb');
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }
        // Edit Form
        if (isset($_GET['id'])) {
            $id = (int) $_GET['id'];
            $query = "SELECT * FROM books WHERE book_id=$id";
            $result = $db->query($query);
            $book = $result->fetch_assoc();

        }

        // Edit Book
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_book'])) {
            // $id = (int) $_POST['book_id'];
            $title = $db->real_escape_string($_POST['title']);
            $author = $db->real_escape_string($_POST['author']);
            $genre = $db->real_escape_string($_POST['genre']);
            $publication_year = $db->real_escape_string($_POST['publication_year']);
            $isbn = $db->real_escape_string($_POST['isbn']);
            $quantity = (int) $_POST['quantity'];

            $query = "UPDATE books SET title='$title', author='$author', genre='$genre', publication_year='$publication_year', isbn='$isbn', quantity=$quantity WHERE book_id=$id";

            if ($db->query($query)) {
                echo "<div class='success'>Book updated successfully!</div>";
            } else {
                echo "<div class='error'>Error updating book.</div>";
            }
        }
        $db->close();
        ?>

        <h2>Edit Book</h2>
        <form method="POST">
            <!-- <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>"> -->
            <div class="form-group">
                <label>Title:</label>
                <input type="text" name="title" value="<?php echo $book['title']; ?>" required>
            </div>
            <div class="form-group">
                <label>Author:</label>
                <input type="text" name="author" value="<?php echo $book['author']; ?>" required>
            </div>
            <div class="form-group">
                <label>Genre:</label>
                <input type="text" name="genre" value="<?php echo $book['genre']; ?>" required>
            </div>
            <div class="form-group">
                <label>Publication Year:</label>
                <input type="text" name="publication_year" value="<?php echo $book['publication_year']; ?>" required>
            </div>
            <div class="form-group">
                <label>ISBN:</label>
                <input type="text" name="isbn" value="<?php echo $book['isbn']; ?>" required>
            </div>
            <div class="form-group">
                <label>Quantity:</label>
                <input type="number" name="quantity" value="<?php echo $book['quantity']; ?>" required min="0">
            </div>
            <button type="submit" name="edit_book">Update Book</button>
            <button><a href="admin.php">Back to dashboard</a></button>
        </form>
    </div>
</body>

</html>