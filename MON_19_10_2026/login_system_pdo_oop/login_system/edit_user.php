<?php
require_once __DIR__ . '/includes/bootstrap.php';
requireAdmin();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(400); exit('Invalid user ID.'); }
$user = $userModel->find($id);
if (!$user) { http_response_code(404); exit('User not found.'); }
$name = $user['name']; $email = $user['email']; $message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf(); $name = trim($_POST['name'] ?? ''); $email = trim($_POST['email'] ?? '');
    if ($name === '' || $email === '') $message = 'Please fill all fields.';
    elseif (mb_strlen($name) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) $message = 'Invalid name or email.';
    elseif ($userModel->emailExists($email, $id)) $message = 'Email already exists.';
    else {
        try {
            $userModel->update($id, $name, $email);
            if ($id === (int)$_SESSION['user_id']) $_SESSION['user_name'] = $name;
            header('Location: dashboard.php#users'); exit;
        } catch (PDOException $e) { error_log($e->getMessage()); $message = 'Failed to update user.'; }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Edit User</h2>
        <?php if ($message): ?><p role="alert"><?= e($message) ?></p><?php endif; ?>
        <form method="POST"><input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>"><input
                type="text" name="name" maxlength="100" value="<?= e($name) ?>" required><input type="email"
                name="email" value="<?= e($email) ?>" required><button type="submit">Save Changes</button></form>
        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>

</html>