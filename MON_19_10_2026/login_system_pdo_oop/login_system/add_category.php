<?php
require_once __DIR__ . '/includes/bootstrap.php';
requireAdmin();
$message = '';
$name = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $name = trim($_POST['name'] ?? '');
    if ($name === '') $message = 'Please enter category name.';
    elseif (mb_strlen($name) > 100) $message = 'Category name is too long.';
    elseif ($categoryModel->nameExists($name)) $message = 'Category already exists.';
    else {
        try { $categoryModel->create($name); header('Location: dashboard.php#categories'); exit; }
        catch (PDOException $e) { error_log($e->getMessage()); $message = 'Failed to add category.'; }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Category</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Add New Category</h2>
        <?php if ($message): ?><p role="alert"><?= e($message) ?></p><?php endif; ?>
        <form method="POST"><input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>"><input
                type="text" name="name" placeholder="Category Name" maxlength="100" value="<?= e($name) ?>"
                required><button type="submit">Add Category</button></form>
        <p><a href="dashboard.php#categories">Back to Dashboard</a></p>
    </div>
</body>

</html>