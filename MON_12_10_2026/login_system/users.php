<?php

session_start();

require "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}
if (empty($_SESSION["is_admin"])) {
    http_response_code(403);
    exit("Access denied. Admins only.");
}

$sql = "SELECT id, name, email FROM users";

$result = mysqli_query($conn, $sql);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Users</title>
</head>
<body>

    <div class="users-container">
        <h2>
            Welcome,
            <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
        </h2>
        <h3>Registered Users</h3>

        <table>

            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
            </tr>

            <?php while ($user = mysqli_fetch_assoc($result)): ?>

                <tr>
                    <td><?php echo $user["id"]; ?></td>
                    <td><?php echo htmlspecialchars($user["name"]); ?></td>
                    <td><?php echo htmlspecialchars($user["email"]); ?></td>
                </tr>

            <?php endwhile; ?>

        </table>

        <br>

        <a href="logout.php">Logout</a>
    </div>

</body>
</html>
