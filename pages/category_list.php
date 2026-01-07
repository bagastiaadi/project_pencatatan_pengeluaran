<?php

require_once '../autoload.php';
requireLogin();

$category = new Category();
$expense = new Expense();

$userId = $_SESSION['user_id'];
$categories = $category->getAllByUser($userId);

// Hitung jumlah pengeluaran per kategori
$expense = new Expense();
foreach ($categories as &$cat) {
    $expensesByCategory = $expense->getByCategory($userId, $cat['id']);
    $cat['expense_count'] = count($expensesByCategory);
    $cat['expense_total'] = array_sum(array_column($expensesByCategory, 'amount'));
}
unset($cat); // Unset reference untuk menghindari bug
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kategori - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div class="page-header">
            <h1>Daftar Kategori</h1>
            <a href="category_add.php" class="btn btn-primary">➕ Tambah Kategori</a>
        </div>

        <!-- Flash Message -->
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['flash_message']) ?>
            </div>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>

        <div class="dashboard-section">
            <?php if (empty($categories)): ?>
                <div class="empty-state">
                    <p>Belum ada kategori. Buat kategori untuk mulai mencatat pengeluaran.</p>
                    <a href="category_add.php" class="btn btn-primary">Tambah Kategori Pertama</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th>Jumlah Transaksi</th>
                                <th>Total Pengeluaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($categories as $cat): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= htmlspecialchars($cat['name']) ?></strong></td>
                                    <td><?= htmlspecialchars($cat['description']) ?: '-' ?></td>
                                    <td><?= $cat['expense_count'] ?> transaksi</td>
                                    <td class="amount">Rp <?= number_format($cat['expense_total'], 0, ',', '.') ?></td>
                                    <td>
                                        <a href="category_edit.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                                        <?php if ($cat['expense_count'] == 0): ?>
                                            <a href="category_delete.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</a>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-danger" disabled title="Kategori masih digunakan">Hapus</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 20px;">
                    <p><strong>Total Kategori:</strong> <?= count($categories) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/script.js"></script>
</body>
</html>