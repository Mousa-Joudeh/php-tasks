<?php

session_start();

require "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    
    $sql = "SELECT id, name, password, is_admin FROM users WHERE email = ?";
    
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);

    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $id, $name, $hashedPassword, $isAdmin);    
    
    if (mysqli_stmt_fetch($stmt)) {

        if (password_verify($password, $hashedPassword)) {
            
            session_regenerate_id(true);

            $_SESSION["user_id"] = $id;
            $_SESSION["user_name"] = $name;
            $_SESSION["is_admin"] = $isAdmin;

            if ($isAdmin == 1) {
                header("Location: dashboard.php");
            } else {
                header("Location: profile.php");
            }

            exit();

        } else {
            echo "Invalid password";
        }

    } else {
        echo "Email not found";
    }



}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body>
    <div class="container">

        <h2>Login</h2>

        <form method="POST">

            <input
                type="email"
                name="email"
                placeholder="Email"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Password"
                required
            >

            <button type="submit">Login</button>

        </form>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>

</body>
</html>
