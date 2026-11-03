<?php
require_once __DIR__ . '/includes/bootstrap.php';
requireAdmin();
$users = $userModel->all();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Users</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="users-container">
        <h2>Welcome, <?= e($_SESSION['user_name']) ?></h2>
        <h3>Registered Users</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
            </tr>
            <?php foreach ($users as $user): ?><tr>
                <td><?= (int)$user['id'] ?></td>
                <td><?= e($user['name']) ?></td>
                <td><?= e($user['email']) ?></td>
            </tr><?php endforeach; ?>
        </table><br><a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a>
    </div>
</body>

</html>