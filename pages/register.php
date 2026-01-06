<?php

require_once '../autoload.php';

// Redirect ke dashboard jika sudah login
requireGuest();

$error = '';
$success = '';

// Proses registrasi ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validasi konfirmasi password
    if ($password !== $confirmPassword) {
        $error = 'Konfirmasi password tidak cocok!';
    } else {
        $user = new User();
        $result = $user->register($username, $email, $password);

        if ($result['success']) {
            $success = $result['message'];
            // Clear form setelah sukses
            $_POST = [];
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="auth-box">
            <h1>Registrasi</h1>
            <p class="subtitle">Buat akun baru untuk mulai mencatat pengeluaran</p>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($success) ?>
                    <br><a href="login.php">Klik di sini untuk login</a>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" 
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" 
                           required autofocus>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" 
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" 
                           minlength="6" required>
                    <small>Minimal 6 karakter</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" 
                           minlength="6" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Daftar</button>
            </form>

            <div class="auth-footer">
                <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
            </div>
        </div>
    </div>
    <script src="../assets/js/script.js"></script>
</body>
</html>