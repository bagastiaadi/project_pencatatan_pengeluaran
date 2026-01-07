<?php
require_once '../autoload.php';

$user = new User();
$user->logout();

redirect('pages/login.php');
?>