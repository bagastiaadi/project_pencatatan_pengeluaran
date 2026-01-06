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

        }

        return [
            'success' => true,
            'message' => 'Login berhasil!'
        ];
    }

    public function logout() {
        // Hapus semua session
        session_unset();
        session_destroy();

        // Hapus cookie remember me
        if (isset($_COOKIE[COOKIE_NAME])) {
            setcookie(COOKIE_NAME, '', time() - 3600, '/');
        }

        return [
            'success' => true,
            'message' => 'Logout berhasil!'
        ];
    }

    public function getUserById($userId) {
        return $this->db->single(
            "SELECT id, username, email, created_at FROM users WHERE id = ?",
            [$userId]
        );
    }

    public function updateProfile($userId, $username, $email) {
        // Validasi input kosong
        if (empty($username) || empty($email)) {
            return [
                'success' => false,
                'message' => 'Username dan email harus diisi!'
            ];
        }

        // Validasi format email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Format email tidak valid!'
            ];
        }

        // Cek apakah username sudah digunakan user lain
        $checkUsername = $this->db->single(
            "SELECT id FROM users WHERE username = ? AND id != ?",
            [$username, $userId]
        );

        if ($checkUsername) {
            return [
                'success' => false,
                'message' => 'Username sudah digunakan!'
            ];
        }

        // Cek apakah email sudah digunakan user lain
        $checkEmail = $this->db->single(
            "SELECT id FROM users WHERE email = ? AND id != ?",
            [$email, $userId]
        );

        if ($checkEmail) {
            return [
                'success' => false,
                'message' => 'Email sudah terdaftar!'
            ];
        }

        // Update data user
        $update = $this->db->execute(
            "UPDATE users SET username = ?, email = ? WHERE id = ?",
            [$username, $email, $userId]
        );

        if ($update) {
            // Update session dengan data baru
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;

            return [
                'success' => true,
                'message' => 'Profile berhasil diupdate!'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat update profile.'
            ];
        }
    }

    public function changePassword($userId, $oldPassword, $newPassword) {
        // Validasi input kosong
        if (empty($oldPassword) || empty($newPassword)) {
            return [
                'success' => false,
                'message' => 'Password lama dan baru harus diisi!'
            ];
        }

        // Validasi panjang password baru minimal 6 karakter
        if (strlen($newPassword) < 6) {
            return [
                'success' => false,
                'message' => 'Password baru minimal 6 karakter!'
            ];
        }

        // Ambil password lama dari database
        $user = $this->db->single(
            "SELECT password FROM users WHERE id = ?",
            [$userId]
        );

        // Verifikasi password lama
        if (!password_verify($oldPassword, $user['password'])) {
            return [
                'success' => false,
                'message' => 'Password lama salah!'
            ];
        }

        // Hash password baru
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password di database
        $update = $this->db->execute(
            "UPDATE users SET password = ? WHERE id = ?",
            [$hashedPassword, $userId]
        );

        if ($update) {
            return [
                'success' => true,
                'message' => 'Password berhasil diubah!'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah password.'
            ];
        }
    }

    public function checkSessionTimeout() {
        if (isset($_SESSION['login_time'])) {
            $elapsed = time() - $_SESSION['login_time'];
            
            if ($elapsed > SESSION_LIFETIME) {
                $this->logout();
                return false;
            }
            
            // Update login time
            $_SESSION['login_time'] = time();
        }
        
        return true;
    }
}