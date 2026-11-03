<?php
require_once __DIR__ . '/includes/bootstrap.php';
requireAdmin();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(400); exit('Invalid category ID.'); }
$category = $categoryModel->find($id);
if (!$category) { http_response_code(404); exit('Category not found.'); }
$name = $category['name']; $message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf(); $name = trim($_POST['name'] ?? '');
    if ($name === '') $message = 'Please enter category name.';
    elseif (mb_strlen($name) > 100) $message = 'Category name is too long.';
    elseif ($categoryModel->nameExists($name, $id)) $message = 'Category name already exists.';
    else {
        try { $categoryModel->update($id, $name); header('Location: dashboard.php#categories'); exit; }
        catch (PDOException $e) { error_log($e->getMessage()); $message = 'Failed to update category.'; }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Category</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Edit Category</h2>
        <?php if ($message): ?><p role="alert"><?= e($message) ?></p><?php endif; ?>
        <form method="POST"><input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>"><input
                type="text" name="name" value="<?= e($name) ?>" maxlength="100" required><button type="submit">Save
                Changes</button></form>
        <p><a href="dashboard.php#categories">Back to Dashboard</a></p>
    </div>
</body>

</html>