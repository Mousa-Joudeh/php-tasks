<?php
require_once __DIR__ . '/includes/bootstrap.php';
requireAdmin();
$message = ''; $name = ''; $email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf(); $name = trim($_POST['name'] ?? ''); $email = trim($_POST['email'] ?? ''); $password = $_POST['password'] ?? '';
    if ($name === '' || $email === '' || $password === '') $message = 'Please fill all fields.';
    elseif (mb_strlen($name) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) $message = 'Please enter a valid name and email.';
    elseif ($userModel->emailExists($email)) $message = 'Email already exists.';
    else {
        try { $userModel->create($name, $email, $password); header('Location: dashboard.php#users'); exit; }
        catch (PDOException $e) { error_log($e->getMessage()); $message = 'Failed to add user.'; }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add User</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Add New User</h2>
        <?php if ($message): ?><p role="alert"><?= e($message) ?></p><?php endif; ?>
        <form method="POST"><input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>"><input
                type="text" name="name" placeholder="Full Name" maxlength="100" value="<?= e($name) ?>" required><input
                type="email" name="email" placeholder="Email" value="<?= e($email) ?>" required><input type="password"
                name="password" placeholder="Password" required><button type="submit">Add User</button></form>
        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>

</html>