<!DOCTYPE html>
<html>
<head>
    <title>Library Management System - User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .action-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }
        .borrow-btn {
            background: #2196F3;
            color: white;
        }
        .return-btn {
            background: #4CAF50;
            color: white;
        }
        .fine {
            color: #f44336;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: Login.html');
        exit();
    }
    
    $db = new mysqli('localhost', 'root', 'root', 'librarydb');
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    $user_id = $_SESSION['user_id'];

    // Get user's borrowed books
    $borrowed_books = $db->query("
        SELECT b.*, br.borrow_date, br.return_date, br.actual_return_date,
        DATEDIFF(CURDATE(), br.return_date) as days_overdue
        FROM books b
        JOIN borrowingst br ON b.isbn = br.book_isbn
        WHERE br.user_id = $user_id AND br.actual_return_date IS NULL
    ");

    // Get available books
    $available_books = $db->query("
        SELECT * FROM books 
        WHERE status = 'available'
        ORDER BY book_id ASC
    ");
    ?>

    <div class="container">
        <div class="header">
            <h1>Welcome to Library Management System</h1>
            <a href="logout.php" class="action-btn">Logout</a>
        </div>

        <h2>Your Borrowed Books</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Borrow Date</th>
                    <th>Due Date</th>
                    <th>Fine</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($book = $borrowed_books->fetch_assoc()) {
                    $fine = 0;
                    if ($book['days_overdue'] > 0) {
                        $fine = $book['days_overdue'] * 1; // $1 per day
                    }
                    
                    echo "<tr>";
                    echo "<td>{$book['user_id']}</td>";
                    echo "<td>{$book['book_isbn']}</td>";
                    echo "<td>{$book['borrow_date']}</td>";
                    echo "<td>{$book['return_date']}</td>";
                    echo "<td class='fine'>" . ($fine > 0 ? "$".$fine : "-") . "</td>";
                    echo "<td>
                            <button class='action-btn return-btn' onclick='returnBook({$book['id']})'>Return</button>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Available Books</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>ISBN</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($book = $available_books->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$book['title']}</td>";
                    echo "<td>{$book['author']}</td>";
                    echo "<td>{$book['isbn']}</td>";
                    echo "<td>
                            <button class='action-btn borrow-btn' onclick='borrowBook({$book['book_id']})'>Borrow</button>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
    function borrowBook(id) {
        if (confirm('Do you want to borrow this book?')) {
            window.location.href = `borrow_book.php?id=${id}`;
        }
    }

    function returnBook(id) {
        if (confirm('Do you want to return this book?')) {
            window.location.href = `return_book.php?id=${id}`;
        }
    }
    </script>

    <?php $db->close(); ?>
</body>
</html>
