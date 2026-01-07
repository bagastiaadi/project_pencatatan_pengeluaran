<?php
// Ambil nama file saat ini untuk active menu
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<header class="header">
    <nav class="navbar">
        <div class="nav-container">
            <!-- Logo / Brand -->
            <div class="nav-brand">
                <a href="dashboard.php">
                    <span class="logo-icon">💰</span>
                    <span class="logo-text"><?= SITE_NAME ?></span>
                </a>
            </div>

            <!-- Hamburger Menu (untuk mobile) -->
            <button class="nav-toggle" id="navToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <!-- Navigation Menu -->
            <ul class="nav-menu" id="navMenu">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link <?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
                        <span class="nav-icon">🏠</span>
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a href="expense_list.php" class="nav-link <?= (strpos($currentPage, 'expense') !== false) ? 'active' : '' ?>">
                        <span class="nav-icon">💸</span>
                        Pengeluaran
                    </a>
                </li>

                <li class="nav-item">
                    <a href="category_list.php" class="nav-link <?= (strpos($currentPage, 'category') !== false) ? 'active' : '' ?>">
                        <span class="nav-icon">📂</span>
                        Kategori
                    </a>
                </li>

                <li class="nav-item">
                    <a href="report.php" class="nav-link <?= ($currentPage == 'report.php') ? 'active' : '' ?>">
                        <span class="nav-icon">📊</span>
                        Laporan
                    </a>
                </li>

                <!-- User Menu Dropdown -->
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="userDropdown">
                        <span class="nav-icon">👤</span>
                        <?= htmlspecialchars($_SESSION['username']) ?>
                    </a>
                    <ul class="dropdown-menu" id="userDropdownMenu">
                        <li>
                            <a href="profile.php" class="dropdown-item">
                                <span class="dropdown-icon">⚙️</span>
                                Pengaturan Profil
                            </a>
                        </li>
                        <li>
                            <a href="change_password.php" class="dropdown-item">
                                <span class="dropdown-icon">🔒</span>
                                Ganti Password
                            </a>
                        </li>
                        <li class="dropdown-divider"></li>
                        <li>
                            <a href="logout.php" class="dropdown-item logout">
                                <span class="dropdown-icon">🚪</span>
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<script>
// Toggle mobile menu
document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');
    
    if (navToggle) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            navToggle.classList.toggle('active');
        });
    }

    // User dropdown toggle
    const userDropdown = document.getElementById('userDropdown');
    const userDropdownMenu = document.getElementById('userDropdownMenu');
    
    if (userDropdown) {
        userDropdown.addEventListener('click', function(e) {
            e.preventDefault();
            userDropdownMenu.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!userDropdown.contains(e.target)) {
                userDropdownMenu.classList.remove('show');
            }
        });
    }
});
</script>