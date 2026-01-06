<?php

class Expense {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }
    //Ambil semua pengeluaran milik user dengan join category
    public function getAllByUser($userId, $limit = null, $offset = 0) {
        $sql = "SELECT e.*, c.name as category_name 
                FROM expenses e 
                JOIN categories c ON e.category_id = c.id 
                WHERE e.user_id = ? 
                ORDER BY e.expense_date DESC, e.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            return $this->db->fetchAll($sql, [$userId, $limit, $offset]);
        }
        
        return $this->db->fetchAll($sql, [$userId]);
    }

    //Ambil satu pengeluaran berdasarkan ID
    public function getById($expenseId, $userId) {
        return $this->db->single(
            "SELECT e.*, c.name as category_name 
             FROM expenses e 
             JOIN categories c ON e.category_id = c.id 
             WHERE e.id = ? AND e.user_id = ?",
            [$expenseId, $userId]
        );
    }
      //Ambil pengeluaran berdasarkan kategori
    public function getByCategory($userId, $categoryId) {
        return $this->db->fetchAll(
            "SELECT e.*, c.name as category_name 
             FROM expenses e 
             JOIN categories c ON e.category_id = c.id 
             WHERE e.user_id = ? AND e.category_id = ? 
             ORDER BY e.expense_date DESC",
            [$userId, $categoryId]
        );
    }

    //Ambil pengeluaran berdasarkan range tanggal
    public function getByDateRange($userId, $startDate, $endDate) {
        return $this->db->fetchAll(
            "SELECT e.*, c.name as category_name 
             FROM expenses e 
             JOIN categories c ON e.category_id = c.id 
             WHERE e.user_id = ? AND e.expense_date BETWEEN ? AND ? 
             ORDER BY e.expense_date DESC",
            [$userId, $startDate, $endDate]
        );
    }

    public function create($userId, $categoryId, $amount, $description, $expenseDate) {
        // Validasi input
        if (empty($categoryId) || empty($amount) || empty($description) || empty($expenseDate)) {
            return [
                'success' => false,
                'message' => 'Semua field harus diisi!'
            ];
        }

        if ($amount <= 0) {
            return [
                'success' => false,
                'message' => 'Jumlah pengeluaran harus lebih dari 0!'
            ];
        }

        $insert = $this->db->execute(
            "INSERT INTO expenses (user_id, category_id, amount, description, expense_date) VALUES (?, ?, ?, ?, ?)",
            [$userId, $categoryId, $amount, $description, $expenseDate]
        );

        if ($insert) {
            return [
                'success' => true,
                'message' => 'Pengeluaran berhasil ditambahkan!',
                'id' => $this->db->lastInsertId()
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menambahkan pengeluaran.'
            ];
        }
    }

    //Update pengeluaran
    public function update($expenseId, $userId, $categoryId, $amount, $description, $expenseDate) {
        // Validasi input
        if (empty($categoryId) || empty($amount) || empty($description) || empty($expenseDate)) {
            return [
                'success' => false,
                'message' => 'Semua field harus diisi!'
            ];
        }

        if ($amount <= 0) {
            return [
                'success' => false,
                'message' => 'Jumlah pengeluaran harus lebih dari 0!'
            ];
        }

        // Cek apakah pengeluaran ini milik user
        $expense = $this->getById($expenseId, $userId);
        if (!$expense) {
            return [
                'success' => false,
                'message' => 'Pengeluaran tidak ditemukan!'
            ];
        }

        $update = $this->db->execute(
            "UPDATE expenses SET category_id = ?, amount = ?, description = ?, expense_date = ? 
             WHERE id = ? AND user_id = ?",
            [$categoryId, $amount, $description, $expenseDate, $expenseId, $userId]
        );

        if ($update) {
            return [
                'success' => true,
                'message' => 'Pengeluaran berhasil diupdate!'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal mengupdate pengeluaran.'
            ];
        }
    }
    //Hapus pengeluaran
    public function delete($expenseId, $userId) {
        // Cek apakah pengeluaran ini milik user
        $expense = $this->getById($expenseId, $userId);
        if (!$expense) {
            return [
                'success' => false,
                'message' => 'Pengeluaran tidak ditemukan!'
            ];
        }

        $delete = $this->db->execute(
            "DELETE FROM expenses WHERE id = ? AND user_id = ?",
            [$expenseId, $userId]
        );

        if ($delete) {
            return [
                'success' => true,
                'message' => 'Pengeluaran berhasil dihapus!'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal menghapus pengeluaran.'
            ];
        }
    }
    // Total pengeluaran user
    public function getTotalByUser($userId) {
        $result = $this->db->single(
            "SELECT COALESCE(SUM(amount), 0) as total FROM expenses WHERE user_id = ?",
            [$userId]
        );
        return $result['total'];
    }

    // Total pengeluaran per kategori
    public function getTotalByCategory($userId) {
        return $this->db->fetchAll(
            "SELECT c.name, COALESCE(SUM(e.amount), 0) as total, COUNT(e.id) as count
             FROM categories c
             LEFT JOIN expenses e ON c.id = e.category_id
             WHERE c.user_id = ?
             GROUP BY c.id, c.name
             ORDER BY total DESC",
            [$userId]
        );
    }

    // Total pengeluaran per bulan
    public function getTotalByMonth($userId, $year) {
        return $this->db->fetchAll(
            "SELECT MONTH(expense_date) as month, COALESCE(SUM(amount), 0) as total
             FROM expenses
             WHERE user_id = ? AND YEAR(expense_date) = ?
             GROUP BY MONTH(expense_date)
             ORDER BY month ASC",
            [$userId, $year]
        );
    }
}
?>