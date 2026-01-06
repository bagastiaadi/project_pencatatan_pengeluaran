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
}
?>