<?php
require_once '../autoload.php';
requireLogin();

$expense = new Expense();
$category = new Category();

$userId = $_SESSION['user_id'];
$categories = $category->getAllByUser($userId);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = $_POST['category_id'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $expenseDate = $_POST['expense_date'] ?? '';

    $result = $expense->create($userId, $categoryId, $amount, $description, $expenseDate);

    if ($result['success']) {
        $_SESSION['flash_message'] = $result['message'];
        redirect('pages/expense_list.php');
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
    <title>Tambah Pengeluaran - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div class="page-header">
            <h1>Tambah Pengeluaran</h1>
            <a href="expense_list.php" class="btn btn-secondary">← Kembali</a>
        </div>

        <div class="dashboard-section" style="max-width: 600px; margin: 0 auto;">
            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (empty($categories)): ?>
                <div class="alert alert-warning">
                    Anda belum memiliki kategori. <a href="category_add.php">Buat kategori dulu</a>
                </div>
            <?php else: ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="expense_date">Tanggal *</label>
                        <input type="date" id="expense_date" name="expense_date" 
                               value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="category_id">Kategori *</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">Pilih Kategori</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>">
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="amount">Jumlah (Rp) *</label>
                        <input type="number" id="amount" name="amount" min="1" step="1" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi *</label>
                        <textarea id="description" name="description" rows="4" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Simpan Pengeluaran</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/script.js"></script>
</body>
</html>
