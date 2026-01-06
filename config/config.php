<?php
// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'expense_tracker');

// Konfigurasi App
define('BASE_URL', 'http://localhost:8000/');
define('SITE_NAME', 'Expense Tracker');

// Display Error
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Konfigurasi Session
define('SESSION_NAME', 'expense_tracker_session');
define('SESSION_LIFETIME', 3600);

// Konfigurasi Cookie
define('COOKIE_NAME', 'expense_tracker_remember');
define('COOKIE_LIFETIME', 30 * 24 * 60 * 60);

// Timezone
date_default_timezone_set('Asia/Jakarta');

?>