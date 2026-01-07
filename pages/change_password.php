<?php
require_once '../autoload.php';
requireLogin();

$user = new User();
$userId = $_SESSION['user_id'];

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validasi konfirmasi password
    if ($newPassword !== $confirmPassword) {
        $error = 'Password baru dan konfirmasi tidak cocok!';
    } else {
        $result = $user->changePassword($userId, $oldPassword, $newPassword);

        if ($result['success']) {
            $success = $result['message'];
            // Clear form
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
    <title>Ganti Password - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div class="page-header">
            <h1>🔒 Ganti Password</h1>
            <a href="profile.php" class="btn btn-secondary">← Kembali ke Profil</a>
        </div>

        <div class="dashboard-section" style="max-width: 600px; margin: 0 auto;">
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="old_password">Password Lama</label>
                    <input type="password" id="old_password" name="old_password" 
                           required autofocus>
                </div>

                <div class="form-group">
                    <label for="new_password">Password Baru</label>
                    <input type="password" id="new_password" name="new_password" 
                           minlength="6" required>
                    <small>Minimal 6 karakter</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password Baru</label>
                    <input type="password" id="confirm_password" name="confirm_password" 
                           minlength="6" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Ganti Password</button>
            </form>

            <div style="margin-top: 30px; padding: 20px; background: #fff3cd; border-radius: 5px; border-left: 4px solid var(--warning-color);">
                <h4 style="margin-top: 0;">⚠️ Tips Keamanan Password</h4>
                <ul style="margin-bottom: 0; line-height: 1.8;">
                    <li>Gunakan kombinasi huruf besar, kecil, angka, dan simbol</li>
                    <li>Minimal 8 karakter (lebih panjang lebih baik)</li>
                    <li>Jangan gunakan informasi pribadi (tanggal lahir, nama, dll)</li>
                    <li>Jangan gunakan password yang sama dengan akun lain</li>
                    <li>Ganti password secara berkala</li>
                </ul>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/script.js"></script>
</body>
</html>