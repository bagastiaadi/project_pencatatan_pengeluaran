<?php

class User {
    private $db;

    // Constructor
    public function __construct() {
        $this->db = new Database();
    }

    public function register($username, $email, $password) {
        // Validasi input kosong
        if (empty($username) || empty($email) || empty($password)) {
            return [
                'success' => false,
                'message' => 'Semua field harus diisi!'
            ];
        }

        // Validasi format email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Format email tidak valid!'
            ];
        }

        // Validasi panjang password minimal 6 karakter
        if (strlen($password) < 6) {
            return [
                'success' => false,
                'message' => 'Password minimal 6 karakter!'
            ];
        }

        // Cek apakah username sudah ada
        $checkUsername = $this->db->single(
            "SELECT id FROM users WHERE username = ?",
            [$username]
        );

        if ($checkUsername) {
            return [
                'success' => false,
                'message' => 'Username sudah digunakan!'
            ];
        }

        // Cek apakah email sudah ada
        $checkEmail = $this->db->single(
            "SELECT id FROM users WHERE email = ?",
            [$email]
        );

        if ($checkEmail) {
            return [
                'success' => false,
                'message' => 'Email sudah terdaftar!'
            ];
        }

        // Hash password untuk keamanan
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user baru ke database
        $insert = $this->db->execute(
            "INSERT INTO users (username, email, password) VALUES (?, ?, ?)",
            [$username, $email, $hashedPassword]
        );

        if ($insert) {
            return [
                'success' => true,
                'message' => 'Registrasi berhasil! Silakan login.'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat registrasi.'
            ];
        }
    }

    public function login($username, $password, $remember = false) {
        // Validasi input kosong
        if (empty($username) || empty($password)) {
            return [
                'success' => false,
                'message' => 'Username dan password harus diisi!'
            ];
        }

        // Cari user berdasarkan username atau email
        $user = $this->db->single(
            "SELECT * FROM users WHERE username = ? OR email = ?",
            [$username, $username]
        );

        // Cek apakah user ditemukan
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Username atau password salah!'
            ];
        }

        // Verifikasi password
        if (!password_verify($password, $user['password'])) {
            return [
                'success' => false,
                'message' => 'Username atau password salah!'
            ];
        }

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['login_time'] = time();

        // Set cookie jika remember me dicentang
        if ($remember) {
            $token = bin2hex(random_bytes(32)); // Generate random token
            $expiry = time() + COOKIE_LIFETIME;

            // Simpan cookie di browser
            setcookie(
                COOKIE_NAME,
                $token,
                $expiry,
                '/',
                '',
                false,
                true // HttpOnly flag untuk keamanan
            );

            // Simpan token di database (opsional, untuk validasi lebih secure)
            // Bisa ditambahkan tabel remember_tokens jika mau lebih aman
        }

        return [
            'success' => true,
            'message' => 'Login berhasil!'
        ];
    }
}