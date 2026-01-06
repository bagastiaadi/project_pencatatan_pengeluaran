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

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}

// method untuk verifikasi login
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('pages/login.php');
    }
}

function requireGuest() {
    if (isLoggedIn()) {
        redirect('pages/dashboard.php');
    }
}

?>