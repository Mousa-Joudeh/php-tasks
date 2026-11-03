<?php
require_once __DIR__ . '/includes/bootstrap.php';
requireAdmin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: dashboard.php#categories'); exit; }
verifyCsrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(400); exit('Invalid category ID.'); }
if (!$categoryModel->find($id)) { http_response_code(404); exit('Category not found.'); }
if ($categoryModel->productCount($id) > 0) { http_response_code(409); exit('Cannot delete category because it contains products. Move or delete those products first.'); }
$categoryModel->delete($id);
header('Location: dashboard.php#categories');
exit;