<?php
require_once __DIR__ . '/includes/bootstrap.php';
$message = ''; $name = ''; $email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? ''); $email = trim($_POST['email'] ?? ''); $password = $_POST['password'] ?? '';
    if ($name === '' || $email === '' || $password === '') $message = 'Please fill all fields.';
    elseif (mb_strlen($name) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) $message = 'Please enter a valid name and email.';
    elseif (strlen($password) < 8) $message = 'Password must be at least 8 characters.';
    elseif ($userModel->emailExists($email)) $message = 'Email already exists.';
    else {
        try { $userModel->create($name, $email, $password); $message = 'Registration successful. You can now log in.'; $name = ''; $email = ''; }
        catch (PDOException $e) { error_log($e->getMessage()); $message = 'Registration failed.'; }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Create Account</h2>
        <?php if ($message): ?><p role="status"><?= e($message) ?></p><?php endif; ?>
        <form method="POST"><input type="text" name="name" placeholder="Full Name" maxlength="100"
                value="<?= e($name) ?>" required><input type="email" name="email" placeholder="Email"
                value="<?= e($email) ?>" required><input type="password" name="password"
                placeholder="Password (at least 8 characters)" minlength="8" required><button
                type="submit">Register</button></form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>

</html>