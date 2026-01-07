<?php

require_once '../autoload.php';
requireLogin();

$categoryObj = new Category();
$userId = $_SESSION['user_id'];
$categoryId = $_GET['id'] ?? 0;

$result = $categoryObj->delete($categoryId, $userId);

$_SESSION['flash_message'] = $result['message'];
redirect('pages/category_list.php');
?>