<!DOCTYPE html>
<html>
<head>
    <title>Add Book - Library Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, select {
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
        a {
            text-decoration: None;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Book</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label>Title:</label>
                <input type="text" name="title" required>
            </div>
            <div class="form-group">
                <label>Author:</label>
                <input type="text" name="author" required>
            </div>
            <div class="form-group">
                <label>Genre:</label>
                <input type="text" name="genre" required>
            </div>
            <div class="form-group">
                <label>Publication Year:</label>
                <input type="text" name="publication_year" required>
            </div>
            <div class="form-group">
                <label>ISBN:</label>
                <input type="text" name="isbn" required>
            </div>
            <div class="form-group">
                <label>Quantity:</label>
                <input type="number" name="quantity" min="1" required>
            </div>
            
            <button type="submit">Add Book</button>
            <button><a href="admin.php">Back to dashboard</a></button>
        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $db = new mysqli('localhost', 'root', 'root', 'librarydb');
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }

        $title = $db->real_escape_string($_POST['title']);
        $author = $db->real_escape_string($_POST['author']);
        $genre = $db->real_escape_string($_POST['genre']);
        $pubyear = $db->real_escape_string($_POST['publication_year']);
        $isbn = $db->real_escape_string($_POST['isbn']);
        $quantity = (int)$_POST['quantity'];


        // Get number of borrowed copies
        $borrowed_query = "SELECT COUNT(*) as borrowed FROM borrowingst WHERE book_isbn = '$isbn' AND return_date IS NULL";
        $borrowed_result = $db->query($borrowed_query);
        $borrowed = $borrowed_result->fetch_assoc()['borrowed'];

        // Calculate remaining books
        $remaining = $quantity - $borrowed;
        
        // Set status based on remaining books
        $status = ($remaining > 0) ? 'available' : 'borrowing';

        $query = "INSERT INTO books (title, author, genre, publication_year, isbn, quantity, status) 
                 VALUES ('$title', '$author','$genre', '$pubyear', '$isbn', $quantity, '$status')";
        
        if ($db->query(query: $query)) {
            echo "<div style='color: green; margin-top: 20px; text-align: center;'>
                    Book added successfully!
                  </div>";
        } else {
            echo "<div style='color: red; margin-top: 20px; text-align: center;'>
                    Error adding book. Please try again.
                  </div>";
        }

        $db->close();
    }
    ?>
</body>
</html>
