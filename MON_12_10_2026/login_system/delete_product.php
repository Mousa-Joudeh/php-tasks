<?php

session_start();
require_once "csrf.php";
require "db.php";

// Check admin
if (!isset($_SESSION["user_id"]) || empty($_SESSION["is_admin"])) {
    http_response_code(403);
    exit("Access denied");
}

// Delete only through POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: dashboard.php#products");
    exit();
}
verifyCsrf();

// Get product ID
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    exit("Invalid product ID");
}

// Delete product
$sql = "DELETE FROM products WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

header("Location: dashboard.php#products");
exit();

?>
