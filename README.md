# EXPENSE TRACKER

# Deskripsi Singkat
Expense Tracker adalah aplikasi web berbasis PHP untuk mencatat dan mengelola pengeluaran pribadi. Aplikasi ini memungkinkan pengguna untuk melacak pengeluaran mereka berdasarkan kategori, melihat statistik pengeluaran, dan menghasilkan laporan dalam berbagai periode waktu. Fitur-fitur utama meliputi autentikasi pengguna, manajemen kategori, pencatatan pengeluaran, dashboard statistik, dan ekspor data ke format CSV.
Project ini dikembangkan sebagai tugas mata kuliah Pengembangan Sistem Backend dengan menggunakan PHP Native (tanpa framework) dan mengimplementasikan konsep Object-Oriented Programming (OOP), autoloading, session management, dan keamanan aplikasi web.

# Daftar Anggota
| Nama                              | Nim         | Akun Github   | Tugas                                                                                     |
| --------------------------------- | ----------- | ------------  | ----------------------------------------------------------------------------------------- |
| **I Made Bagastia Adi Pramana**   | 240030157   | bagastiaadi   | Membuat class Database, config, autoload, pages dashboard, report, header, footer, js     |
| **I Putu Arya Wijaya Kusuma**     | 240030139   | AryaWIjaya777 | Membuat class User, pages login, register, logout, logic export, profile, change password | 
| **I Komang Gede Diva Adistanaya** | 240030140   | komangdiva    | Membuat class Expense, pages CRUD (list, add, edit, delete) expense, css                  |
| **I Dewa Gede Andi Gunawan**      | 240030109   | dewaandi      | Membuat class Category, pages CRUD (list, add, edit, delete) category                     |

# Lingkungan Pengembangan
### Bahasa Pemrograman (Backend)
* PHP 8.4.14
### Bahasa Pemrograman (Frontend)
* HTML5
* CSS3 
* JavaScript
### Tools & Development Environment
* Visual Studio Code - Code editor
* PHP Built-in Server - Local development server
* Git & GitHub - Version control system
* phpMyAdmin - Database management tool
* Google Chrome DevTools - Browser debugging

# Hasil Pengembangan
## Autentikasi Pengguna
* Register: Form pendaftaran user baru dengan validasi dan password hashing
* Login: Autentikasi menggunakan username/email dan password, dengan fitur "Remember Me"
* Logout: Menghapus session dan redirect ke halaman login
* Session Management: Session timeout otomatis setelah 1 jam tidak aktif

## Pencatatan Pengeluaran (CRUD)
* Tambah Pengeluaran: Form input dengan field tanggal, kategori, jumlah, dan deskripsi
* Lihat Pengeluaran: Menampilkan daftar pengeluaran dengan filter kategori dan range tanggal
* Edit Pengeluaran: Mengubah data pengeluaran yang sudah ada
* Hapus Pengeluaran: Menghapus data pengeluaran dengan konfirmasi

## Manajemen Kategori (CRUD)
* Tambah Kategori: Membuat kategori pengeluaran baru (nama dan deskripsi)
* Lihat Kategori: Menampilkan daftar kategori dengan jumlah transaksi dan total pengeluaran
* Edit Kategori: Mengubah nama dan deskripsi kategori
* Hapus Kategori: Menghapus kategori yang tidak digunakan (validasi cascade)

## Laporan Pengeluaran
* Filter Periode: Menampilkan laporan berdasarkan periode (Hari Ini, Minggu Ini, Bulan Ini, Bulan Lalu, Tahun Ini, Custom)
* Statistik: Menampilkan total pengeluaran, jumlah transaksi, rata-rata per hari, dan pengeluaran tertinggi
* Visualisasi per Kategori: Progress bar menunjukkan proporsi pengeluaran per kategori
* Export CSV: Mengekspor data pengeluaran ke format CSV

## Dashboard
* Statistics Cards: Menampilkan total pengeluaran, jumlah transaksi, jumlah kategori, dan pengeluaran bulan ini
* Pengeluaran Terbaru: Menampilkan 5 transaksi pengeluaran terakhir
* Chart Kategori: Visualisasi pengeluaran per kategori dengan progress bar
* Quick Actions: Shortcut untuk menambah pengeluaran dan kategori

## Pengaturan Profil
* Edit Profil: Mengubah username dan email
* Ganti Password: Mengganti password dengan validasi password lama

## Fitur Keamanan
* Password hashing menggunakan password_hash() dan password_verify()
* Prepared statement untuk mencegah SQL Injection
* Output encoding dengan htmlspecialchars() untuk mencegah XSS
* Session-based authentication dengan validasi kepemilikan data
* Route protection untuk halaman yang memerlukan autentikasi



# Struktur Folder
```text
project_pencatatan_pengeluaran/
│
├── assets/                     # Asset statis
│   ├── css/
│   │   └── style.css           # Main stylesheet
│   ├── js/
│       └── script.js           # Main JavaScript
│
├── class/                      # PHP Classes (OOP)
│   ├── Database.php            # Database connection & helper methods
│   ├── User.php                # User authentication & management
│   ├── Expense.php             # Expense CRUD & analytics
│   └── Category.php            # Category CRUD operations
│
├── config/                     # Konfigurasi aplikasi
│   ├── config.php              # Database credentials & app config
│
├── exports/                    # Export functionality
│   └── export_csv.php          # CSV export logic
│
├── includes/                   # Reusable components
│   ├── header.php              # Navbar / Header
│   └── footer.php              # Footer
│
├── pages/                      # Halaman aplikasi
│   ├── login.php               # Halaman login
│   ├── register.php            # Halaman registrasi
│   ├── logout.php              # Proses logout
│   ├── dashboard.php           # Dashboard utama
│   │
│   ├── expense_list.php        # List semua pengeluaran
│   ├── expense_add.php         # Form tambah pengeluaran
│   ├── expense_edit.php        # Form edit pengeluaran
│   ├── expense_delete.php      # Proses hapus pengeluaran
│   │
│   ├── category_list.php       # List semua kategori
│   ├── category_add.php        # Form tambah kategori
│   ├── category_edit.php       # Form edit kategori
│   ├── category_delete.php     # Proses hapus kategori
│   │
│   ├── report.php              # Halaman laporan & analytics
│   ├── profile.php             # Pengaturan profile user
│   └── change_password.php     # Ganti password
│
├── autoload.php                # Autoloader untuk class
├── index.php                   # Entry point aplikasi
├── schema.sql                  # SQL schema untuk database
└── README.md                   # Dokumentasi project
```

# Cara Instalasi dan Menjalankan Aplikasi
### Pastikan sudah terinstall:

* PHP 8.0 atau lebih tinggi
* MySQL 8.0 atau MariaDB 10.6+
* Web browser modern (Chrome, Firefox, Edge)
* Git (untuk clone repository)

## Langkah 1: Clone Repository
``` bash
git clone https://github.com/bagastiaadi/project_pencatatan_pengeluaran.git
cd expense-tracker
```
## Langkah 2: Setup Database
### Buat database baru:
```sql
CREATE DATABASE IF NOT EXISTS expense_tracker;
USE expense_tracker;


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    description TEXT NOT NULL,
    expense_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);


CREATE INDEX idx_user_id ON expenses(user_id);
CREATE INDEX idx_category_id ON expenses(category_id);
CREATE INDEX idx_expense_date ON expenses(expense_date);
CREATE INDEX idx_user_categories ON categories(user_id);
```

## Langkah 3: Konfigurasi Database
### Edit config/config.php dan sesuaikan:
``` php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');              // Username MySQL Anda
   define('DB_PASS', '');                  // Password MySQL Anda
   define('DB_NAME', 'expense_tracker');
```

## Langkah 4: Jalankan Aplikasi
### Jika menggunakan built-in PHP server:
``` nginx
php -S localhost:8000
```
Akses melalui browser:
``` bash
http://localhost:8000/index.php
```

## Contoh Skenario Uji Singkat
### Register User Baru
* Klik "Daftar di sini" di halaman login
* Isi form registrasi
* Login dengan akun yang baru dibuat
  
### Mulai Menggunakan Aplikasi
Buat Kategori:
* Dashboard → Tambah Kategori
* Contoh: Makanan, Transportasi, Hiburan
  
Tambah Pengeluaran:
* Dashboard → Tambah Pengeluaran
* Isi tanggal, pilih kategori, masukkan jumlah dan deskripsi
  
Lihat Laporan:
* Menu Laporan → Pilih periode
* Export ke CSV jika diperlukan


