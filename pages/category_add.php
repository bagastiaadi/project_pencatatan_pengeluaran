<?php

require_once '../autoload.php';
requireLogin();

$category = new Category();
$userId = $_SESSION['user_id'];

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    $result = $category->create($userId, $name, $description);

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
    <title>Tambah Kategori - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div class="page-header">
            <h1>Tambah Kategori</h1>
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
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" 
                           placeholder="Contoh: Makanan, Transportasi, Hiburan" required autofocus>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" 
                              placeholder="Deskripsi singkat tentang kategori ini (opsional)"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                    <small>Opsional - bisa dikosongkan</small>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Simpan Kategori</button>
            </form>
        </div>

        <!-- Contoh Kategori -->
        <div class="dashboard-section" style="max-width: 600px; margin: 30px auto 0;">
            <h3>💡 Contoh Kategori</h3>
            <p>Berikut beberapa contoh kategori yang bisa Anda buat:</p>
            <ul style="line-height: 2;">
                <li><strong>Makanan & Minuman</strong> - Pengeluaran untuk makan sehari-hari</li>
                <li><strong>Transportasi</strong> - Biaya transportasi, bensin, parkir</li>
                <li><strong>Hiburan</strong> - Nonton, jalan-jalan, hobi</li>
                <li><strong>Kesehatan</strong> - Obat, dokter, rumah sakit</li>
                <li><strong>Pendidikan</strong> - Buku, kursus, biaya kuliah</li>
                <li><strong>Tagihan</strong> - Listrik, air, internet, pulsa</li>
                <li><strong>Belanja</strong> - Pakaian, elektronik, kebutuhan rumah</li>
            </ul>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/script.js"></script>
</body>
</html>