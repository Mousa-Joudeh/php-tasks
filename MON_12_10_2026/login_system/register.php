<?php

require "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($name) || empty($email) || empty($password)) {
        echo "Please fill all fields";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email";

    } else {

        // Check if email already exists
        $sql = "SELECT id FROM users WHERE email = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {

            echo "Email already exists";

        } else {

            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $sql = "INSERT INTO users (name, email, password)
                    VALUES (?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "sss",
                $name,
                $email,
                $hashedPassword
            );

            if (mysqli_stmt_execute($stmt)) {
                echo "User registered successfully";
            } else {
                echo "Registration failed";
            }
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
</head>
<body>
    <div class="container">

        <h2>Create Account</h2>

        <form action="" method="POST">

            <input type="text" name="name" placeholder="Full Name" required>

            <input type="email" name="email" placeholder="Email" required>

            <input type="password" name="password" placeholder="Password" required>

            <button type="submit">Register</button>

        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>

</body>
</html>
