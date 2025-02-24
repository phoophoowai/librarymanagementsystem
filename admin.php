<!DOCTYPE html>
<html>

<head>
    <title>Library Management System - Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .nav-menu {
            display: flex;
            gap: 20px;
        }

        .nav-menu a {
            text-decoration: none;
            color: #333;
            padding: 8px 16px;
            border-radius: 4px;
            background: #eee;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .action-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }

        .edit-btn {
            background: #2196F3;
            color: white;
        }

        .delete-btn {
            background: #f44336;
            color: white;
        }

        .add-btn {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <script>
        function deleteBook(id) {
            if (confirm('Are you sure you want to delete this book?')) {
                window.location.href = `delete_book.php?id=${id}`;
            }
        }

        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                window.location.href = `delete_user.php?id=${id}`;
            }
        }
    </script>

    <?php
    session_start();
    // if (!isset($_SESSION['user_type'])) {
    //     header('Location: login.html');
    //     exit();
    // }
    
    $db = new mysqli('localhost', 'root', 'root', 'librarydb');
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Get statistics
    $total_books = $db->query("SELECT COUNT(*) as count FROM books")->fetch_assoc()['count'];
    $total_users = $db->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
    $total_borrowed = $db->query("SELECT COUNT(*) as count FROM books where status = 'borrowing' ")->fetch_assoc()['count'];
    $total_overdue = $db->query("SELECT COUNT(*) as count FROM borrowingst WHERE return_date < CURDATE() AND due_date IS NULL")->fetch_assoc()['count'];
    ?>

    <div class="container">
        <div class="header">
            <h1>Admin Dashboard</h1>
            <div class="nav-menu">
                <a href="#books">Manage Books</a>
                <a href="#users">Manage Users</a>
                <a href="#borrowings">Borrowings</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>

        <!-- Dashboard Panel -->
        <div class="stats-container">
            <div class="stat-box">
                <h3>Total Books</h3>
                <div class="stat-number"><?php echo $total_books; ?></div>
            </div>
            <div class="stat-box">
                <h3>Total Users</h3>
                <div class="stat-number"><?php echo $total_users; ?></div>
            </div>
            <div class="stat-box">
                <h3>Books Borrowed</h3>
                <div class="stat-number"><?php echo $total_borrowed; ?></div>
            </div>
            <div class="stat-box">
                <h3>Overdue Books</h3>
                <div class="stat-number"><?php echo $total_overdue; ?></div>
            </div>
        </div>

        <!-- Manage Book Section -->
        <section id="books">
            <div class="books-section">
                <div class="header">
                    <h2>Manage Books</h2>
                    <button class="add-btn" onclick="window.location.href='add_book.php'">Add New Book</button>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Genre</th>
                            <th>Publishcation Year</th>
                            <th>ISBN</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $books = $db->query("SELECT * FROM books ORDER BY book_id ASC LIMIT 10");
                        while ($book = $books->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$book['book_id']}</td>";
                            echo "<td>{$book['title']}</td>";
                            echo "<td>{$book['author']}</td>";
                            echo "<td>{$book['genre']}</td>";
                            echo "<td>{$book['publication_year']}</td>";
                            echo "<td>{$book['isbn']}</td>";
                            echo "<td>{$book['status']}</td>";
                            echo "<td>
                                <button class='action-btn edit-btn' name='edit' onclick='window.location.href=\"edit_book.php?id={$book['book_id']}\"'>Edit</button>
                                <button class='action-btn delete-btn' onclick='deleteBook({$book['book_id']})'>Delete</button>
                              </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Users Management Section -->
        <section id="users">
            <div class="users-section" style="margin-top: 40px;">
                <div class="header">
                    <h2>Manage Users</h2>
                    <button class="add-btn" onclick="window.location.href='UserRegistration.php'">Add New User</button>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Birthday</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Actions</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $users = $db->query("SELECT * FROM users ORDER BY id ASC LIMIT 100");
                        while ($user = $users->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$user['id']}</td>";
                            echo "<td>{$user['Name']}</td>";
                            echo "<td>{$user['Birthday']}</td>";
                            echo "<td>{$user['Email']}</td>";
                            echo "<td>{$user['Gender']}</td>";
                            echo "<td>{$user['Address']}</td>";
                            echo "<td>{$user['Phone']}</td>";
                            echo "<td>
                                <button class='action-btn edit-btn' name='edit_user' onclick='window.location.href=\"edit_user.php?id={$user['id']}\"'>Edit</button>
                                <button class='action-btn delete-btn' name='delete_user' onclick='deleteUser({$user['id']})'>Delete</button>
                              </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Borrowings Section -->
        <section id="borrowings">
            <h2>Borrowings Management</h2>
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Book</th>
                        <th>Borrow Date</th>
                        <th>Due Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT b.*, u.name as user_name, bk.title as book_title 
                             FROM borrowingst b 
                             JOIN users u ON b.user_id = u.id 
                             JOIN books bk ON b.book_isbn = bk.isbn";
                    $result = $db->query($query);
                    while ($borrowing = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$borrowing['user_name']}</td>";
                        echo "<td>{$borrowing['book_title']}</td>";
                        echo "<td>{$borrowing['borrow_date']}</td>";
                        echo "<td>{$borrowing['due_date']}</td>";
                        echo "<td>{$borrowing['status']}</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>