<?php
require_once __DIR__ . '/includes/bootstrap.php';
requireAdmin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: dashboard.php#products'); exit; }
verifyCsrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(400); exit('Invalid product ID.'); }
$product = $productModel->find($id);
if ($product) {
    $productModel->delete($id);
    if (!empty($product['image'])) $imageUploader->delete($product['image']);
}
header('Location: dashboard.php#products');
exit;