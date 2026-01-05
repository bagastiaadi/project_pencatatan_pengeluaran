<?php
require_once 'autoload.php';

$db = new Database();
$conn = $db->getConnection();

echo "Database connected successfully!";
