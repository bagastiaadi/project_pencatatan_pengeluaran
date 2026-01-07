<?php

require_once '../autoload.php';

// Proteksi harus login dulu
requireLogin();

// Cek session timeout
$user = new User();
$user->checkSessionTimeout();

// Instantiate class yang dibutuhkan
$expense = new Expense();
$category = new Category();

// Ambil data untuk dashboard
$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Statistik
$totalExpenses = $expense->getTotalByUser($userId);
$expenseCount = $expense->countByUser($userId);
$categoryCount = $category->countByUser($userId);

// Ambil pengeluaran terbaru (5 terakhir)
$recentExpenses = $expense->getAllByUser($userId, 5);

// Ambil total per kategori untuk chart
$expensesByCategory = $expense->getTotalByCategory($userId);

// Ambil total per bulan untuk tahun ini
$currentYear = date('Y');
$expensesByMonth = $expense->getTotalByMonth($userId, $currentYear);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div class="page-header">
            <h1>Dashboard</h1>
            <p>Selamat datang, <strong><?= htmlspecialchars($username) ?></strong>!</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-info">
                    <h3>Total Pengeluaran</h3>
                    <p class="stat-value">Rp <?= number_format($totalExpenses, 0, ',', '.') ?></p>
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
                <div class="stat-icon">📂</div>
                <div class="stat-info">
                    <h3>Kategori</h3>
                    <p class="stat-value"><?= $categoryCount ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-info">
                    <h3>Bulan Ini</h3>
                    <p class="stat-value">
                        <?php
                        $currentMonth = date('Y-m');
                        $monthStart = $currentMonth . '-01';
                        $monthEnd = date('Y-m-t', strtotime($monthStart));
                        $monthExpenses = $expense->getByDateRange($userId, $monthStart, $monthEnd);
                        $monthTotal = array_sum(array_column($monthExpenses, 'amount'));
                        echo 'Rp ' . number_format($monthTotal, 0, ',', '.');
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Recent Expenses -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2>Pengeluaran Terbaru</h2>
                <a href="expense_list.php" class="btn btn-sm">Lihat Semua</a>
            </div>

            <?php if (empty($recentExpenses)): ?>
                <div class="empty-state">
                    <p>Belum ada pengeluaran tercatat.</p>
                    <a href="expense_add.php" class="btn btn-primary">Tambah Pengeluaran Pertama</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Deskripsi</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentExpenses as $exp): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($exp['expense_date'])) ?></td>
                                    <td>
                                        <span class="badge"><?= htmlspecialchars($exp['category_name']) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($exp['description']) ?></td>
                                    <td class="amount">Rp <?= number_format($exp['amount'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Expenses by Category -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2>Pengeluaran per Kategori</h2>
                <a href="report.php" class="btn btn-sm">Lihat Laporan</a>
            </div>

            <?php if (empty($expensesByCategory)): ?>
                <div class="empty-state">
                    <p>Belum ada data pengeluaran per kategori.</p>
                </div>
            <?php else: ?>
                <div class="category-stats">
                    <?php foreach ($expensesByCategory as $cat): ?>
                        <?php if ($cat['total'] > 0): ?>
                            <div class="category-item">
                                <div class="category-header">
                                    <span class="category-name"><?= htmlspecialchars($cat['name']) ?></span>
                                    <span class="category-total">Rp <?= number_format($cat['total'], 0, ',', '.') ?></span>
                                </div>
                                <div class="category-bar">
                                    <?php
                                    $percentage = ($totalExpenses > 0) ? ($cat['total'] / $totalExpenses * 100) : 0;
                                    ?>
                                    <div class="category-bar-fill" style="width: <?= $percentage ?>%"></div>
                                </div>
                                <div class="category-details">
                                    <span><?= $cat['count'] ?> transaksi</span>
                                    <span><?= number_format($percentage, 1) ?>%</span>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Quick Actions -->
        <div class="dashboard-section">
            <h2>Aksi Cepat</h2>
            <div class="quick-actions">
                <a href="expense_add.php" class="action-card">
                    <div class="action-icon">➕</div>
                    <div class="action-text">
                        <h3>Tambah Pengeluaran</h3>
                        <p>Catat pengeluaran baru</p>
                    </div>
                </a>

                <a href="category_add.php" class="action-card">
                    <div class="action-icon">📂</div>
                    <div class="action-text">
                        <h3>Tambah Kategori</h3>
                        <p>Buat kategori baru</p>
                    </div>
                </a>

                <a href="report.php" class="action-card">
                    <div class="action-icon">📊</div>
                    <div class="action-text">
                        <h3>Lihat Laporan</h3>
                        <p>Analisis pengeluaran</p>
                    </div>
                </a>

                <a href="expense_list.php" class="action-card">
                    <div class="action-icon">📋</div>
                    <div class="action-text">
                        <h3>Semua Pengeluaran</h3>
                        <p>Kelola data pengeluaran</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <script src="../assets/js/script.js"></script>
</body>
</html>