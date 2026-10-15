<?php

session_start();

// Check login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$name = htmlspecialchars($_SESSION["user_name"]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h1>My Profile</h1>

    <h2>Welcome, <?php echo $name; ?>!</h2>

    <p>You are logged in successfully.</p>

    <a href="logout.php">Logout</a>

</div>

</body>
</html>
