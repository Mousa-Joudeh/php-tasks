<?php
require_once __DIR__ . '/includes/bootstrap.php';
requireAdmin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: dashboard.php'); exit; }
verifyCsrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { http_response_code(400); exit('Invalid user ID.'); }
if ($id === (int)$_SESSION['user_id']) { http_response_code(400); exit('You cannot delete your own account.'); }
$userModel->delete($id);
header('Location: dashboard.php');
exit;