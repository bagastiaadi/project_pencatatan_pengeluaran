<?php

?>
<footer class="footer">
    <div class="footer-container">
        <div class="footer-content">
            <div class="footer-section">
                <h3><?= SITE_NAME ?></h3>
                <p>Aplikasi pencatatan pengeluaran pribadi untuk membantu Anda mengelola keuangan dengan lebih baik.</p>
            </div>

            <div class="footer-section">
                <h4>Menu</h4>
                <ul class="footer-links">
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="expense_list.php">Pengeluaran</a></li>
                    <li><a href="category_list.php">Kategori</a></li>
                    <li><a href="report.php">Laporan</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Akun</h4>
                <ul class="footer-links">
                    <li><a href="profile.php">Profil Saya</a></li>
                    <li><a href="change_password.php">Ganti Password</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Statistik</h4>
                <div class="footer-stats">
                    <?php
                    // Tampilkan quick stats di footer
                    if (isset($_SESSION['user_id'])) {
                        $footerExpense = new Expense();
                        $footerCategory = new Category();
                        
                        $totalExp = $footerExpense->getTotalByUser($_SESSION['user_id']);
                        $countExp = $footerExpense->countByUser($_SESSION['user_id']);
                        $countCat = $footerCategory->countByUser($_SESSION['user_id']);
                    ?>
                        <p><strong><?= $countExp ?></strong> Transaksi</p>
                        <p><strong><?= $countCat ?></strong> Kategori</p>
                        <p><strong>Rp <?= number_format($totalExp, 0, ',', '.') ?></strong></p>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Tugas Project Sistem Backend.</p>
        </div>
    </div>
</footer>