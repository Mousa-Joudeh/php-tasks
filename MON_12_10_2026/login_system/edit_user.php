<?php

session_start();
require_once "csrf.php";
require "db.php";

// Check admin permission
if (!isset($_SESSION["user_id"]) || empty($_SESSION["is_admin"])) {
    http_response_code(403);
    exit("Access denied");
}

// Get user ID
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    exit("Invalid user ID");
}

$message = "";

// Get user information
$sql = "SELECT name, email FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $name, $email);

if (!mysqli_stmt_fetch($stmt)) {
    exit("User not found");
}

mysqli_stmt_close($stmt);

// Update user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    verifyCsrf();

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");

    if (empty($name) || empty($email)) {
        $message = "Please fill all fields";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email";

    } else {

        // Check email is not used by another user
        $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $email, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $message = "Email already exists";
        } else {
            mysqli_stmt_close($stmt);

            $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssi", $name, $email, $id);

            if (mysqli_stmt_execute($stmt)) {

                // Update session if admin edits their own account
                if ($id == $_SESSION["user_id"]) {
                    $_SESSION["user_name"] = $name;
                }

                header("Location: dashboard.php");
                exit();

            } else {
                $message = "Failed to update user";
            }
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Edit User</h2>

    <p><?php echo htmlspecialchars($message); ?></p>

    <form method="POST">

        <input type="hidden" name="csrf_token"
       value="<?php echo htmlspecialchars($_SESSION["csrf_token"]); ?>">

        <input type="text" name="name"
               value="<?php echo htmlspecialchars($name); ?>"
               required>

        <input type="email" name="email"
               value="<?php echo htmlspecialchars($email); ?>"
               required>

        <button type="submit">Save Changes</button>
    </form>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</div>

</body>
</html>
