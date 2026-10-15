<?php

session_start();
require_once "csrf.php";
require "db.php";

// Check admin permission
if (!isset($_SESSION["user_id"]) || empty($_SESSION["is_admin"])) {
    http_response_code(403);
    exit("Access denied");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    verifyCsrf();

    $name = trim($_POST["name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $price = $_POST["price"] ?? "";
    $quantity = $_POST["quantity"] ?? "";

    if ($name == "" || $description == "" || $price === "" || $quantity === "") {
        $message = "Please fill all fields";

    } elseif (!is_numeric($price) || $price < 0 ||
              filter_var($quantity, FILTER_VALIDATE_INT) === false || $quantity < 0) {
        $message = "Please enter a valid price and quantity";

    } else {

        $sql = "INSERT INTO products (name, description, price, quantity)
                VALUES (?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "ssdi",
            $name,
            $description,
            $price,
            $quantity
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: dashboard.php#products");
            exit();
        } else {
            $message = "Failed to add product";
        }

        mysqli_stmt_close($stmt);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h2>Add New Product</h2>

    <p><?php echo htmlspecialchars($message); ?></p>

    <form method="POST">

        <input type="hidden" name="csrf_token"
       value="<?php echo htmlspecialchars($_SESSION["csrf_token"]); ?>">

        <input type="text" name="name"
               placeholder="Product Name" required>

        <textarea name="description"
                  placeholder="Description" required></textarea>

        <input type="number" name="price"
               placeholder="Price" min="0" step="0.01" required>

        <input type="number" name="quantity"
               placeholder="Quantity" min="0" step="1" required>

        <button type="submit">Add Product</button>

    </form>

    <p><a href="dashboard.php#products">Back to Dashboard</a></p>

</div>

</body>
</html>
