<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../classes/ImageUploader.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/../csrf.php';

try {
    $database = new Database();
    $pdo = $database->connect();
    $categoryModel = new Category($pdo);
    $productModel = new Product($pdo);
    $userModel = new User($pdo);
    $imageUploader = new ImageUploader();
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit('Database connection failed. Check config/Database.php.');
}
