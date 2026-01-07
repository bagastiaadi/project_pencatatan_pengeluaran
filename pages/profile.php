<?php
require_once '../autoload.php';
requireLogin();

$user = new User();
$userId = $_SESSION['user_id'];

// Ambil data user
$userData = $user->getUserById($userId);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');

    $result = $user->updateProfile($userId, $username, $email);

    if ($result['success']) {
        $success = $result['message'];
        // Refresh data user
        $userData = $user->getUserById($userId);
    } else {
        $error = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Profil - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div class="page-header">
            <h1>⚙️ Pengaturan Profil</h1>
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
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" 
                           value="<?= htmlspecialchars($userData['username']) ?>" 
                           required autofocus>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" 
                           value="<?= htmlspecialchars($userData['email']) ?>" 
                           required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Update Profil</button>
            </form>

            <div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid var(--border-color);">
                <h3>Informasi Akun</h3>
                <p><strong>Akun dibuat:</strong> <?= date('d/m/Y H:i', strtotime($userData['created_at'])) ?></p>
                <p><strong>User ID:</strong> #<?= $userData['id'] ?></p>
            </div>

            <div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid var(--border-color);">
                <h3>Keamanan</h3>
                <p>Ingin mengubah password?</p>
                <a href="change_password.php" class="btn btn-secondary">🔒 Ganti Password</a>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/script.js"></script>
</body>
</html>