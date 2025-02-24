<!DOCTYPE html>
<html>

<head>
    <title>User Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f4f4f4;
        }

        h2 {
            text-align: center;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        input[type="tel"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .radio-group label {
            display: inline;
            margin-right: 20px;
            font-weight: normal;
        }

        button {
            background: hsl(204, 36%, 37%);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background: hwb(203 35% 48% / 0.599);
        }

        .error-message {
            color: red;
            font-size: 12px;
            position: absolute;
            bottom: -18px;
            left: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>User Registration</h2>
        <?php
        $emailErr = $confirmPasswordErr = "";
        $name = $email = $password = $confirmPassword = $phone = "" ;
        $isValid = true;

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
            $name = test_input($_POST["name"]);
            $birthday = test_input($_POST["birthday"]);
            $password = test_input($_POST["password"]);
            $gender = test_input(data: $_POST["gender"]);
            $address = test_input(data: $_POST["address"]);
            $phone = $_POST["phone"];

            // Validate email
            if (!empty($_POST["email"])) {
                $email = test_input($_POST["email"]);
                if (!preg_match("/@gmail.com$/", $email)) {
                    $emailErr = "Only @gmail.com email addresses are allowed";
                    $isValid = false;
                }
            }

            // Validate confirm password
            if (!empty($_POST["confirm_password"])) {
                $confirmPassword = test_input($_POST["confirm_password"]);
                if ($password != $confirmPassword) {
                    $confirmPasswordErr = "Passwords do not match";
                    $isValid = false;
                }
            }

            // If all validations pass
            if ($isValid) {
                $db = new mysqli('localhost', 'root', 'root', 'librarydb');

                if ($db->connect_error) {
                    die("Connection failed: " . $db->connect_error);
                }

                $name = $db->real_escape_string($name);
                $birthday = $db->real_escape_string($birthday);
                $email = $db->real_escape_string($email);
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $gender = $db->real_escape_string($gender);
                $address = $db->real_escape_string($address);
                $phone = $db->real_escape_string($phone);

                $stmt = $db->prepare("INSERT INTO users (Name, Birthday, Email, Password, Gender, Address, Phone) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssss", $name, $birthday, $email, $hashedPassword, $gender, $address, $phone);
                if ($stmt->execute()) {
                    echo "<script>alert('Registration successful! Redirecting to login...');</script>";
                    echo "<script>window.location.href = 'Login.html';</script>";
                    exit();
                }
                $db->close();
            }
        }

        function test_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" value="<?php echo $name; ?>" required>
            </div>

            <div class="form-group">
                <label>Birthdate:</label>
                <input type="date" name="birthday" value="<?php echo $birthday; ?>" required>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo $email; ?>" required>
                <?php if ($emailErr)
                    echo "<span class='error-message'>$emailErr</span>"; ?>
            </div>

            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" required>
                <?php if ($confirmPasswordErr)
                    echo "<span class='error-message'>$confirmPasswordErr</span>"; ?>
            </div>

            <div class="form-group">
                <label>Gender:</label>
                <div class="radio-group">
                    <input type="radio" name="gender" value="male" required>
                    <label>M</label>
                    <input type="radio" name="gender" value="female" required>
                    <label>F</label>
                    <input type="radio" name="gender" value="other" required>
                    <label>Other</label>
                </div>
            </div>

            <div class="form-group">
                <label>Address:</label>
                <textarea name="address" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <label>Phone Number:</label>
                <input type="tel" name="phone" pattern="[0-9]{11}" title="Please enter valid 10-digit phone number"
                    value="<?php echo $phone; ?>" required>
            </div>

            <button type="submit" name="register">Register</button>
        </form>
    </div>
</body>

</html>