<?php

session_start();
require_once "csrf.php";
require "db.php";

// Only admins can add users
if (!isset($_SESSION["user_id"]) || empty($_SESSION["is_admin"])) {
    http_response_code(403);
    exit("Access denied");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    verifyCsrf();

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (empty($name) || empty($email) || empty($password)) {
        $message = "Please fill all fields";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email";

    } else {

        // Check duplicate email
        $sql = "SELECT id FROM users WHERE email = ?";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $message = "Email already exists";

        } else {

            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Add user
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
                header("Location: dashboard.php");
                exit();
            } else {
                $message = "Failed to add user";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h2>Add New User</h2>

    <p><?php echo htmlspecialchars($message); ?></p>

    <form method="POST">

        <input type="hidden" name="csrf_token"
       value="<?php echo htmlspecialchars($_SESSION["csrf_token"]); ?>">

        <input type="text" name="name"
               placeholder="Full Name" required>

        <input type="email" name="email"
               placeholder="Email" required>

        <input type="password" name="password"
               placeholder="Password" required>

        <button type="submit">Add User</button>

    </form>

    <p><a href="dashboard.php">Back to Dashboard</a></p>

</div>

</body>
</html>
