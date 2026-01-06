<?php


class Category {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }
    // CREATE - Tambah kategori baru
    public function create($userId, $name, $description = '') {
        if (empty($name)) {
            return [
                'success' => false,
                'message' => 'Nama kategori harus diisi!'
            ];
        }

        // Cek apakah nama kategori sudah ada untuk user ini
        $check = $this->db->single(
            "SELECT id FROM categories WHERE user_id = ? AND name = ?",
            [$userId, $name]
        );

        if ($check) {
            return [
                'success' => false,
                'message' => 'Kategori dengan nama ini sudah ada!'
            ];
        }

        $insert = $this->db->execute(
            "INSERT INTO categories (user_id, name, description) VALUES (?, ?, ?)",
            [$userId, $name, $description]
        );

        if ($insert) {
            return [
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan!',
                'id' => $this->db->lastInsertId()
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menambahkan kategori.'
            ];
        }
    }
    // READ - Ambil semua kategori milik user
    public function getAllByUser($userId) {
        return $this->db->fetchAll(
            "SELECT * FROM categories WHERE user_id = ? ORDER BY created_at DESC",
            [$userId]
        );
    }

    // READ - Ambil satu kategori berdasarkan ID
    public function getById($categoryId, $userId) {
        return $this->db->single(
            "SELECT * FROM categories WHERE id = ? AND user_id = ?",
            [$categoryId, $userId]
        );
    }
    // UPDATE - Update kategori
    public function update($categoryId, $userId, $name, $description = '') {
        if (empty($name)) {
            return [
                'success' => false,
                'message' => 'Nama kategori harus diisi!'
            ];
        }

        // Cek apakah kategori ini milik user
        $category = $this->getById($categoryId, $userId);
        if (!$category) {
            return [
                'success' => false,
                'message' => 'Kategori tidak ditemukan!'
            ];
        }

        // Cek apakah nama kategori sudah digunakan kategori lain
        $check = $this->db->single(
            "SELECT id FROM categories WHERE user_id = ? AND name = ? AND id != ?",
            [$userId, $name, $categoryId]
        );

        if ($check) {
            return [
                'success' => false,
                'message' => 'Kategori dengan nama ini sudah ada!'
            ];
        }

        $update = $this->db->execute(
            "UPDATE categories SET name = ?, description = ? WHERE id = ? AND user_id = ?",
            [$name, $description, $categoryId, $userId]
        );

        if ($update) {
            return [
                'success' => true,
                'message' => 'Kategori berhasil diupdate!'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal mengupdate kategori.'
            ];
        }
    }
}
?>