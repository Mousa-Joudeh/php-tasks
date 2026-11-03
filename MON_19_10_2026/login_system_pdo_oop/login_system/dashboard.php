<?php
require_once __DIR__ . '/includes/bootstrap.php';
requireAdmin();

$users = $userModel->all();
$products = $productModel->allWithCategories();
$categories = $categoryModel->all();
$totalUsers = $userModel->count();
$totalProducts = $productModel->count();
$totalCategories = count($categories);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="dashboard">
        <h1>Admin Dashboard</h1>
        <h2>Welcome, <?= e($_SESSION['user_name'] ?? '') ?></h2>
        <nav><a href="#users">Users</a><a href="#products">Products</a><a href="#categories">Categories</a><a
                href="logout.php">Logout</a></nav>
        <div class="stats">
            <div class="stat-card">
                <h3>Total Users</h3>
                <h2><?= $totalUsers ?></h2>
            </div>
            <div class="stat-card">
                <h3>Total Products</h3>
                <h2><?= $totalProducts ?></h2>
            </div>
            <div class="stat-card">
                <h3>Total Categories</h3>
                <h2><?= $totalCategories ?></h2>
            </div>
        </div>
        <section id="users">
            <h2>User Management</h2><a href="add_user.php">+ Add New User</a>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($users as $user): ?><tr>
                    <td><?= e($user['name']) ?></td>
                    <td><?= e($user['email']) ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= (int)$user['id'] ?>">Edit</a>
                        <form action="delete_user.php" method="POST" style="display:inline"
                            onsubmit="return confirm('Delete this user?');">
                            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>"><input
                                type="hidden" name="id" value="<?= (int)$user['id'] ?>"><button
                                type="submit">Delete</button>
                        </form>
                    </td>
                </tr><?php endforeach; ?>
            </table>
        </section>
        <section id="products">
            <h2>Product Management</h2><a href="add_product.php">+ Add New Product</a>
            <table>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Category</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($products as $product): ?><tr>
                    <td><?php if (!empty($product['image']) && is_file(__DIR__ . '/' . $product['image'])): ?><img
                            src="<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>"
                            style="width:70px;height:70px;object-fit:cover"><?php else: ?><span>No
                            image</span><?php endif; ?></td>
                    <td><?= e($product['name']) ?></td>
                    <td><?= e($product['description']) ?></td>
                    <td><?= e(number_format((float)$product['price'], 2)) ?></td>
                    <td><?= (int)$product['quantity'] ?></td>
                    <td><?= e($product['category_name']) ?></td>
                    <td><?= e($product['created_at']) ?></td>
                    <td><a href="edit_product.php?id=<?= (int)$product['id'] ?>">Edit</a>
                        <form action="delete_product.php" method="POST" style="display:inline"
                            onsubmit="return confirm('Delete this product?');">
                            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>"><input
                                type="hidden" name="id" value="<?= (int)$product['id'] ?>"><button
                                type="submit">Delete</button>
                        </form>
                    </td>
                </tr><?php endforeach; ?>
            </table>
        </section>
        <section id="categories">
            <h2>Category Management</h2><a href="add_category.php">+ Add New Category</a>
            <table>
                <tr>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($categories as $category): ?><tr>
                    <td><?= e($category['name']) ?></td>
                    <td><a href="edit_category.php?id=<?= (int)$category['id'] ?>">Edit</a>
                        <form action="delete_category.php" method="POST" style="display:inline"
                            onsubmit="return confirm('Delete this category?');">
                            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>"><input
                                type="hidden" name="id" value="<?= (int)$category['id'] ?>"><button
                                type="submit">Delete</button>
                        </form>
                    </td>
                </tr><?php endforeach; ?>
            </table>
        </section>
    </div>
</body>

</html>