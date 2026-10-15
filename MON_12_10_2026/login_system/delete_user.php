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
    header("Location: dashboard.php");
    exit();
}

verifyCsrf();

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id) {
    exit("Invalid user ID");
}

// Prevent deleting your own account
if ($id == $_SESSION["user_id"]) {
    exit("You cannot delete your own account");
}

$sql = "DELETE FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

header("Location: dashboard.php");
exit();
