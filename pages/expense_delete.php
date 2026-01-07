<?php
require_once '../autoload.php';
requireLogin();

$expenseObj = new Expense();

$userId = $_SESSION['user_id'];
$expenseId = $_GET['id'] ?? 0;

$result = $expenseObj->delete($expenseId, $userId);

$_SESSION['flash_message'] = $result['message'];
redirect('pages/expense_list.php');
?>