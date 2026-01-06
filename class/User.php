<?php

class User {
    private $db;

    // Constructor
    public function __construct() {
        $this->db = new Database();
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