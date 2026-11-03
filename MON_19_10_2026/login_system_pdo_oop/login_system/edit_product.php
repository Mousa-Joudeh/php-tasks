<?php
require_once __DIR__ . '/includes/bootstrap.php';
requireAdmin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(400); exit('Invalid product ID.'); }
$product = $productModel->find($id);
if (!$product) { http_response_code(404); exit('Product not found.'); }

$message = '';
$categories = $categoryModel->all();
$name = $product['name'];
$description = $product['description'];
$price = $product['price'];
$quantity = $product['quantity'];
$categoryId = (int)$product['category_id'];

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
        $newImagePath = null;
        try {
            $newImagePath = $imageUploader->upload($_FILES['image'] ?? []);
            $imageToSave = $newImagePath ?? $product['image']; // Keep old image if no replacement was uploaded.
            $productModel->update($id, [
                'name' => $name,
                'description' => $description,
                'price' => number_format((float)$price, 2, '.', ''),
                'quantity' => (int)$quantity,
                'category_id' => (int)$categoryId,
                'image' => $imageToSave,
            ]);
            if ($newImagePath && !empty($product['image'])) $imageUploader->delete($product['image']);
            header('Location: dashboard.php#products');
            exit;
        } catch (RuntimeException $e) {
            $message = $e->getMessage();
        } catch (PDOException $e) {
            if ($newImagePath) $imageUploader->delete($newImagePath);
            error_log($e->getMessage());
            $message = 'Could not update product.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Edit Product</h2>
        <?php if ($message): ?><p role="alert"><?= e($message) ?></p><?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
            <input type="text" name="name" maxlength="100" value="<?= e($name) ?>" required>
            <textarea name="description" required><?= e($description) ?></textarea>
            <input type="number" name="price" min="0" step="0.01" value="<?= e($price) ?>" required>
            <input type="number" name="quantity" min="0" step="1" value="<?= e($quantity) ?>" required>
            <select name="category_id" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $category): ?><option value="<?= (int)$category['id'] ?>"
                    <?= (int)$category['id'] === (int)$categoryId ? 'selected' : '' ?>><?= e($category['name']) ?>
                </option><?php endforeach; ?>
            </select>
            <?php if (!empty($product['image']) && is_file(__DIR__ . '/' . $product['image'])): ?><p>Current image:</p>
            <img src="<?= e($product['image']) ?>" alt="Current product image"
                style="max-width:160px;max-height:160px;object-fit:contain"><?php endif; ?>
            <label for="image">Replace image (optional; leave empty to keep current image)</label><input id="image"
                type="file" name="image" accept=".jpg,.jpeg,.png,.gif,.webp,image/jpeg,image/png,image/gif,image/webp">
            <button type="submit">Save Changes</button>
        </form>
        <p><a href="dashboard.php#products">Back to Dashboard</a></p>
    </div>
</body>

</html>