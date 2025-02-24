<?php

$db = new mysqli('localhost', 'root', 'root', 'librarydb');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $db->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // First check admin table
    $admin_query = "SELECT * FROM admins WHERE Username = '$email'";
    $admin_result = $db->query($admin_query);

    if ($admin_result->num_rows > 0) {
        $admin = $admin_result->fetch_assoc();
        $hashed_adminpwd = password_hash($admin['Password'], PASSWORD_DEFAULT);
        if (password_verify($password, $hashed_adminpwd)) {
            $_SESSION['user_type'] = 'admin';
            $_SESSION['user_id'] = $admin['id'];
            header('Location: admin.php');
            exit();
        }
    }

    // Not admin ... Check user table
    $query = "SELECT id,password FROM users WHERE email = '$email'";
    $result = $db->query(query: $query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $storedHash = $user['password'];

        if (password_verify($password, $storedHash)) {
            echo "<script>alert('Password matches! Redirecting...');</script>";
            session_start();
            $_SESSION['user_id'] = $user['id'];
            header("refresh:0.5;url=userpanel.php");
            exit();
        } else {
            echo "<script>alert('Password does not match! ❌ ');</script>";
            echo "<script>window.location.href = 'UserLogin.html';</script>";

        }
    } else {
        echo "<script>alert('User not found! Please register first...');</script>";
        header("refresh:0.5;url=UserRegistration.php");
        exit();
    }

    $db->close();
}


?>