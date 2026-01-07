<?php
require_once '../autoload.php';
requireLogin();

$expense = new Expense();
$category = new Category();

$userId = $_SESSION['user_id'];

// Filter
$filterCategory = $_GET['category'] ?? '';
$filterDateFrom = $_GET['date_from'] ?? '';
$filterDateTo = $_GET['date_to'] ?? '';

// Ambil data berdasarkan filter
if ($filterCategory) {
    $expenses = $expense->getByCategory($userId, $filterCategory);
} elseif ($filterDateFrom && $filterDateTo) {
    $expenses = $expense->getByDateRange($userId, $filterDateFrom, $filterDateTo);
} else {
    $expenses = $expense->getAllByUser($userId);
}

$categories = $category->getAllByUser($userId);
$total = array_sum(array_column($expenses, 'amount'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengeluaran - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div class="page-header">
            <h1>Daftar Pengeluaran</h1>
            <a href="expense_add.php" class="btn btn-primary">➕ Tambah Pengeluaran</a>
        </div>

        <!-- Filter -->
        <div class="dashboard-section">
            <h3>Filter Pengeluaran</h3>
            <form method="GET" action="" class="filter-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="category">Kategori</label>
                        <select name="category" id="category">
                            <option value="">Semua Kategori</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($filterCategory == $cat['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_from">Dari Tanggal</label>
                        <input type="date" name="date_from" id="date_from" value="<?= $filterDateFrom ?>">
                    </div>
                    <div class="form-group">
                        <label for="date_to">Sampai Tanggal</label>
                        <input type="date" name="date_to" id="date_to" value="<?= $filterDateTo ?>">
                    </div>
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="expense_list.php" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary -->
        <div class="dashboard-section">
            <h3>Total: Rp <?= number_format($total, 0, ',', '.') ?> (<?= count($expenses) ?> transaksi)</h3>
        </div>

        <!-- Table -->
        <div class="dashboard-section">
            <?php if (empty($expenses)): ?>
                <div class="empty-state">
                    <p>Tidak ada pengeluaran ditemukan.</p>
                    <a href="expense_add.php" class="btn btn-primary">Tambah Pengeluaran</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Deskripsi</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($expenses as $exp): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= date('d/m/Y', strtotime($exp['expense_date'])) ?></td>
                                    <td><span class="badge"><?= htmlspecialchars($exp['category_name']) ?></span></td>
                                    <td><?= htmlspecialchars($exp['description']) ?></td>
                                    <td class="amount">Rp <?= number_format($exp['amount'], 0, ',', '.') ?></td>
                                    <td>
                                        <a href="expense_edit.php?id=<?= $exp['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                                        <a href="expense_delete.php?id=<?= $exp['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/script.js"></script>
</body>
</html>