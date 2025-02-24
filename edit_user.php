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
            $query = "SELECT * FROM users WHERE id=$id";
            $result = $db->query($query);
            $user = $result->fetch_assoc();

        }

        // Edit Book
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_user'])) {
            // $id = (int) $_POST['book_id'];
            $name = $db->real_escape_string(string: $_POST['name']);
            $birthday = $db->real_escape_string($_POST['birthday']);
            $email = $db->real_escape_string($_POST['email']);
            $gender = $db->real_escape_string($_POST['gender']);
            $address = $db->real_escape_string($_POST['address']);
            $phone = (int) $_POST['phone'];

            $query = "UPDATE users SET Name='$name', Birthday='$birthday', Email='$email', Gender='$gender', Address='$address', Phone=$phone WHERE id=$id";

            if ($db->query($query)) {
                echo "<div class='success'>User updated successfully!</div>";
            } else {
                echo "<div class='error'>Error updating user.</div>";
            }
        }
        $db->close();
        ?>

        <h2>Edit Book</h2>
        <form method="POST">
            <!-- <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>"> -->
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" value="<?php echo $user['Name']; ?>" required>
            </div>
            <div class="form-group">
                <label>Birthday:</label>
                <input type="text" name="birthday" value="<?php echo $user['Birthday']; ?>" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="text" name="email" value="<?php echo $user['Email']; ?>" required>
            </div>
            <div class="form-group">
                <label>Gender:</label>
                <input type="text" name="gender" value="<?php echo $user['Gender']; ?>" required>
            </div>
            <div class="form-group">
                <label>Address:</label>
                <input type="text" name="address" value="<?php echo $user['Address']; ?>" required>
            </div>
            <div class="form-group">
                <label>Phone:</label>
                <input type="tel" name="phone" value="<?php echo $user['Phone']; ?>" required>
            </div>
            <button type="submit" name="edit_user">Update User</button>
            <button><a href="admin.php">Back to dashboard</a></button>
        </form>
    </div>
</body>
</html>