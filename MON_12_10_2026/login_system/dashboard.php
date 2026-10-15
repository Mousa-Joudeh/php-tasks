
<?php

session_start();
require_once "csrf.php";

require "db.php";

// Check login
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (empty($_SESSION["is_admin"])) {
    http_response_code(403);
    exit("Access denied. Admins only.");
}

// Get users
$users = mysqli_query(
    $conn,
    "SELECT id, name, email FROM users"
);

// Get products
$products = mysqli_query(
    $conn,
    "SELECT * FROM products"
);

// Count users
$totalUsers = mysqli_num_rows($users);

// Count products
$totalProducts = mysqli_num_rows($products);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="dashboard">

    <h1>Admin Dashboard</h1>

    <h2>
        Welcome,
        <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
    </h2>

    <nav>
        <a href="#users">Users</a>
        <a href="#products">Products</a>
        <a href="logout.php">Logout</a>
    </nav>

    <div class="stats">
        <div class="stat-card">
            <h3>Total Users</h3>
            <h2><?php echo $totalUsers; ?></h2>
        </div>

        <div class="stat-card">
            <h3>Total Products</h3>
            <h2><?php echo $totalProducts; ?></h2>
        </div>
    </div>

    <section id="users">
        <h2>User Management</h2>
        <a href="add_user.php">+ Add New User</a>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>

            <?php while ($user = mysqli_fetch_assoc($users)): ?>
                <tr>
                    <td><?php echo $user["id"]; ?></td>
                    <td><?php echo htmlspecialchars($user["name"]); ?></td>
                    <td><?php echo htmlspecialchars($user["email"]); ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>">
                            Edit
                        </a>

                        <form action="delete_user.php"
                            method="POST"
                            style="display:inline;"
                            onsubmit="return confirm('Delete this user?');">
                            
                            <input type="hidden" name="csrf_token"
                                value="<?php echo htmlspecialchars($_SESSION["csrf_token"]); ?>">

                            <input type="hidden" name="id"
                                value="<?php echo $user['id']; ?>">

                            <button type="submit">Delete</button>
                        </form>
                    </td>

                </tr>
            <?php endwhile; ?>
            
        </table>
    </section>

    <section id="products">
        <h2>Product Management</h2>
        <a href="add_product.php">+ Add New Product</a>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>

            <?php while ($product = mysqli_fetch_assoc($products)): ?>
                <tr>
                    <td><?php echo $product["id"]; ?></td>
                    <td><?php echo htmlspecialchars($product["name"]); ?></td>
                    <td><?php echo htmlspecialchars($product["description"]); ?></td>
                    <td><?php echo $product["price"]; ?></td>
                    <td><?php echo $product["quantity"]; ?></td>
                    <td><?php echo $product["created_at"]; ?></td> 
                    <td>
                        <a href="edit_product.php?id=<?php echo $product['id']; ?>">
                            Edit
                        </a>

                        <form action="delete_product.php"
                            method="POST"
                            style="display:inline;"
                            onsubmit="return confirm('Delete this product?');">

                            <input type="hidden" name="csrf_token"
                                value="<?php echo htmlspecialchars($_SESSION["csrf_token"]); ?>">

                            <input type="hidden" name="id"
                                value="<?php echo $product['id']; ?>">

                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </section>

</div>

</body>
</html>
