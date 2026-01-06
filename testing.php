<?php
require_once 'autoload.php';

$db = new Database();
$conn = $db->getConnection();

echo "Database connected successfully!";

$db = new Database();
$data = $db->fetchAll("SELECT * FROM users");
print_r($data);

?>