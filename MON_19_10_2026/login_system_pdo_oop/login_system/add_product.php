<?php
require_once __DIR__ . '/includes/bootstrap.php';
requireAdmin();

$message = '';
$categories = $categoryModel->all();
$name = $description = $price = $quantity = '';
$categoryId = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');
    $categoryId = filter_var($_POST['category_id'] ?? null, FILTER_VALIDATE_INT);

    if ($name === '' || $description === '' || $price === '' || $quantity === '' || !$categoryId) {
        $message = 'Please fill all fields and select a category.';
    } elseif (mb_strlen($name) > 100 || !is_numeric($price) || (float)$price < 0 ||
        filter_var($quantity, FILTER_VALIDATE_INT) === false || (int)$quantity < 0) {
        $message = 'Invalid product information.';
    } elseif (!$categoryModel->exists((int)$categoryId)) {
        $message = 'Selected category does not exist.';
    } else {
        $imagePath = null;
        try {
            $imagePath = $imageUploader->upload($_FILES['image'] ?? []);
            if ($imagePath === null) {
                throw new RuntimeException('Please select a product image.');
            }
            $productModel->create([
                'name' => $name,
                'description' => $description,
                'price' => number_format((float)$price, 2, '.', ''),
                'quantity' => (int)$quantity,
                'category_id' => (int)$categoryId,
                'image' => $imagePath,
            ]);
            header('Location: dashboard.php#products');
            exit;
        } catch (RuntimeException $e) {
            $message = $e->getMessage();
        } catch (PDOException $e) {
            if ($imagePath) $imageUploader->delete($imagePath);
            error_log($e->getMessage());
            $message = 'Could not save product.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Product</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Add New Product</h2>
        <?php if ($message): ?><p role="alert"><?= e($message) ?></p><?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
            <input type="text" name="name" placeholder="Product Name" maxlength="100" value="<?= e($name) ?>" required>
            <textarea name="description" placeholder="Description" required><?= e($description) ?></textarea>
            <input type="number" name="price" placeholder="Price" min="0" step="0.01" value="<?= e($price) ?>" required>
            <input type="number" name="quantity" placeholder="Quantity" min="0" step="1" value="<?= e($quantity) ?>"
                required>
            <select name="category_id" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $category): ?><option value="<?= (int)$category['id'] ?>"
                    <?= (string)$categoryId === (string)$category['id'] ? 'selected' : '' ?>><?= e($category['name']) ?>
                </option><?php endforeach; ?>
            </select>
            <label for="image">Product image (JPG, PNG, GIF, WEBP; max 5 MB)</label><input id="image" type="file"
                name="image" required accept=".jpg,.jpeg,.png,.gif,.webp,image/jpeg,image/png,image/gif,image/webp">
            <button type="submit">Add Product</button>
        </form>
        <p><a href="dashboard.php#products">Back to Dashboard</a></p>
    </div>
</body>

</html>