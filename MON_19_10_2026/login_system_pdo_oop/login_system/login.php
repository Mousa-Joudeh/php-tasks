<?php
require_once __DIR__ . '/includes/bootstrap.php';
$message = '';
$email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $user = filter_var($email, FILTER_VALIDATE_EMAIL) ? $userModel->findByEmail($email) : null;
    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['is_admin'] = (int)$user['is_admin'];
        header('Location: ' . ((int)$user['is_admin'] === 1 ? 'dashboard.php' : 'profile.php'));
        exit;
    }
    $message = 'Invalid email or password.';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Login</h2>
        <?php if ($message): ?><p role="alert"><?= e($message) ?></p><?php endif; ?>
        <form method="POST"><input type="email" name="email" placeholder="Email" value="<?= e($email) ?>"
                required><input type="password" name="password" placeholder="Password" required><button
                type="submit">Login</button></form>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
</body>

</html>