<?php

require_once '../autoload.php';
requireLogin();

$expense = new Expense();
$category = new Category();

$userId = $_SESSION['user_id'];

// Filter periode
$period = $_GET['period'] ?? 'this_month';
$customDateFrom = $_GET['custom_from'] ?? '';
$customDateTo = $_GET['custom_to'] ?? '';

// Hitung tanggal berdasarkan periode
switch ($period) {
    case 'today':
        $dateFrom = date('Y-m-d');
        $dateTo = date('Y-m-d');
        $periodLabel = 'Hari Ini';
        break;
    case 'this_week':
        $dateFrom = date('Y-m-d', strtotime('monday this week'));
        $dateTo = date('Y-m-d', strtotime('sunday this week'));
        $periodLabel = 'Minggu Ini';
        break;
    case 'this_month':
        $dateFrom = date('Y-m-01');
        $dateTo = date('Y-m-t');
        $periodLabel = 'Bulan Ini';
        break;
    case 'this_year':
        $dateFrom = date('Y-01-01');
        $dateTo = date('Y-12-31');
        $periodLabel = 'Tahun Ini';
        break;
    case 'last_month':
        $dateFrom = date('Y-m-01', strtotime('last month'));
        $dateTo = date('Y-m-t', strtotime('last month'));
        $periodLabel = 'Bulan Lalu';
        break;
    case 'custom':
        $dateFrom = $customDateFrom;
        $dateTo = $customDateTo;
        $periodLabel = 'Custom';
        break;
    default:
        $dateFrom = date('Y-m-01');
        $dateTo = date('Y-m-t');
        $periodLabel = 'Bulan Ini';
}

// Ambil data
$expenses = $expense->getByDateRange($userId, $dateFrom, $dateTo);
$totalExpense = array_sum(array_column($expenses, 'amount'));
$expenseCount = count($expenses);
$expensesByCategory = $expense->getTotalByCategory($userId);

// Hitung pengeluaran per kategori untuk periode yang dipilih
$filteredCategoryStats = [];
$categories = $category->getAllByUser($userId);

foreach ($categories as $cat) {
    // Ambil pengeluaran kategori ini dalam range tanggal
    $catExpenses = array_filter($expenses, function($e) use ($cat) {
        return $e['category_id'] == $cat['id'];
    });
    
    $catTotal = array_sum(array_column($catExpenses, 'amount'));
    
    if ($catTotal > 0) {
        $filteredCategoryStats[] = [
            'name' => $cat['name'],
            'total' => $catTotal,
            'count' => count($catExpenses)
        ];
    }
}

// Sort by total descending
usort($filteredCategoryStats, function($a, $b) {
    return $b['total'] - $a['total'];
});

// Rata-rata per hari
$days = (strtotime($dateTo) - strtotime($dateFrom)) / (60 * 60 * 24) + 1;
$avgPerDay = $days > 0 ? $totalExpense / $days : 0;

// Pengeluaran terbesar & terkecil
$maxExpense = !empty($expenses) ? max(array_column($expenses, 'amount')) : 0;
$minExpense = !empty($expenses) ? min(array_column($expenses, 'amount')) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengeluaran - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div class="page-header">
            <h1>📊 Laporan Pengeluaran</h1>
        </div>

        <!-- Filter Periode -->
        <div class="dashboard-section">
            <h3>Pilih Periode</h3>
            <form method="GET" action="" class="filter-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="period">Periode</label>
                        <select name="period" id="period" onchange="toggleCustomDate()">
                            <option value="today" <?= ($period == 'today') ? 'selected' : '' ?>>Hari Ini</option>
                            <option value="this_week" <?= ($period == 'this_week') ? 'selected' : '' ?>>Minggu Ini</option>
                            <option value="this_month" <?= ($period == 'this_month') ? 'selected' : '' ?>>Bulan Ini</option>
                            <option value="last_month" <?= ($period == 'last_month') ? 'selected' : '' ?>>Bulan Lalu</option>
                            <option value="this_year" <?= ($period == 'this_year') ? 'selected' : '' ?>>Tahun Ini</option>
                            <option value="custom" <?= ($period == 'custom') ? 'selected' : '' ?>>Custom</option>
                        </select>
                    </div>
                    <div class="form-group" id="customDateGroup" style="<?= ($period == 'custom') ? '' : 'display:none;' ?>">
                        <label for="custom_from">Dari</label>
                        <input type="date" name="custom_from" id="custom_from" value="<?= $customDateFrom ?>">
                    </div>
                    <div class="form-group" id="customDateGroup2" style="<?= ($period == 'custom') ? '' : 'display:none;' ?>">
                        <label for="custom_to">Sampai</label>
                        <input type="date" name="custom_to" id="custom_to" value="<?= $customDateTo ?>">
                    </div>
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                        <a href="../exports/export_csv.php?period=<?= $period ?>&from=<?= $dateFrom ?>&to=<?= $dateTo ?>" class="btn btn-secondary">📥 Export CSV</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary Statistics -->
        <div class="dashboard-section">
            <h2>Ringkasan - <?= $periodLabel ?></h2>
            <p><em><?= date('d/m/Y', strtotime($dateFrom)) ?> - <?= date('d/m/Y', strtotime($dateTo)) ?></em></p>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-info">
                        <h3>Total Pengeluaran</h3>
                        <p class="stat-value">Rp <?= number_format($totalExpense, 0, ',', '.') ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">📝</div>
                    <div class="stat-info">
                        <h3>Jumlah Transaksi</h3>
                        <p class="stat-value"><?= $expenseCount ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">📊</div>
                    <div class="stat-info">
                        <h3>Rata-rata per Hari</h3>
                        <p class="stat-value">Rp <?= number_format($avgPerDay, 0, ',', '.') ?></p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">🔝</div>
                    <div class="stat-info">
                        <h3>Pengeluaran Tertinggi</h3>
                        <p class="stat-value">Rp <?= number_format($maxExpense, 0, ',', '.') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengeluaran per Kategori -->
        <div class="dashboard-section">
            <h2>Pengeluaran per Kategori</h2>
            
            <?php if (empty($filteredCategoryStats)): ?>
                <div class="empty-state">
                    <p>Tidak ada data pengeluaran untuk periode ini.</p>
                </div>
            <?php else: ?>
                <div class="category-stats">
                    <?php foreach ($filteredCategoryStats as $cat): ?>
                        <div class="category-item">
                            <div class="category-header">
                                <span class="category-name"><?= htmlspecialchars($cat['name']) ?></span>
                                <span class="category-total">Rp <?= number_format($cat['total'], 0, ',', '.') ?></span>
                            </div>
                            <div class="category-bar">
                                <?php
                                $percentage = ($totalExpense > 0) ? ($cat['total'] / $totalExpense * 100) : 0;
                                ?>
                                <div class="category-bar-fill" style="width: <?= $percentage ?>%"></div>
                            </div>
                            <div class="category-details">
                                <span><?= $cat['count'] ?> transaksi</span>
                                <span><?= number_format($percentage, 1) ?>%</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Detail Transaksi -->
        <div class="dashboard-section">
            <h2>Detail Transaksi</h2>
            
            <?php if (empty($expenses)): ?>
                <div class="empty-state">
                    <p>Tidak ada transaksi untuk periode ini.</p>
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
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr style="font-weight: bold; background: var(--light-color);">
                                <td colspan="4" style="text-align: right;">TOTAL:</td>
                                <td class="amount">Rp <?= number_format($totalExpense, 0, ',', '.') ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="../assets/js/script.js"></script>
</body>
</html>