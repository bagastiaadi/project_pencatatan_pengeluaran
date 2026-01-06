<?php

require_once 'autoload.php';

// Cek apakah user sudah login
if (isLoggedIn()) {
    redirect('pages/dashboard.php');
} else {
    redirect('pages/login.php');
}
?>