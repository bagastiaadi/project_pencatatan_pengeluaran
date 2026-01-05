<?php
// Load config
require_once __DIR__ . '/config/config.php';

// Autoloader
spl_autoload_register(function ($class_name) {
    $file = __DIR__ . '/class/' . $class_name . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    } else {
        die("Class file tidak ditemukan: " . $class_name);
    }
});

?>