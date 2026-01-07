<?php

require_once '../autoload.php';
requireLogin();

$categoryObj = new Category();
$userId = $_SESSION['user_id'];
$categoryId = $_GET['id'] ?? 0;

// Ambil data kategori
$cat = $categoryObj->getById($categoryId, $userId);

if (!$cat) {
    $_SESSION['flash_message'] = 'Kategori tidak ditemukan!';
    redirect('pages/category_list.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    $result = $categoryObj->update($categoryId, $userId, $name, $description);

    if ($result['success']) {
        $_SESSION['flash_message'] = $result['message'];
        redirect('pages/category_list.php');
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
    <title>Edit Kategori - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div class="page-header">
            <h1>Edit Kategori</h1>
            <a href="category_list.php" class="btn btn-secondary">← Kembali</a>
        </div>

        <div class="dashboard-section" style="max-width: 600px; margin: 0 auto;">
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Nama Kategori *</label>
                    <input type="text" id="name" name="name" 
                           value="<?= htmlspecialchars($cat['name']) ?>" required autofocus>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" rows="4"><?= htmlspecialchars($cat['description']) ?></textarea>
                    <small>Opsional - bisa dikosongkan</small>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Update Kategori</button>
            </form>
        </div>

        <!-- Info -->
        <div class="dashboard-section" style="max-width: 600px; margin: 30px auto 0;">
            <h3>ℹ️ Informasi</h3>
            <p>Kategori ini dibuat pada: <strong><?= date('d/m/Y H:i', strtotime($cat['created_at'])) ?></strong></p>
            <?php if ($cat['updated_at'] != $cat['created_at']): ?>
                <p>Terakhir diupdate: <strong><?= date('d/m/Y H:i', strtotime($cat['updated_at'])) ?></strong></p>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/script.js"></script>
</body>
</html>