<?php


class Database {
    private $host;
    private $user;
    private $pass;
    private $dbname;
    private $conn;
    private $error;

    // Constructor - otomatis dipanggil saat class di-instantiate
    public function __construct() {
        $this->host = DB_HOST;
        $this->user = DB_USER;
        $this->pass = DB_PASS;
        $this->dbname = DB_NAME;
        
        $this->connect();
    }

    // Method untuk koneksi ke database
    private function connect() {
        try {
            // DSN (Data Source Name) untuk MySQL
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            
            // PDO Options untuk keamanan dan performa
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        
                PDO::ATTR_EMULATE_PREPARES   => false,                   
            ];
            
            // Buat koneksi PDO
            $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
            
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die("Database Connection Error: " . $this->error);
        }
    }

    // Method untuk mendapatkan koneksi PDO
    public function getConnection() {
        return $this->conn;
    }

    // Method untuk execute query (SELECT)
    public function query($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("Query Error: " . $e->getMessage());
        }
    }

    // Method untuk fetch single row
    public function single($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    // Method untuk fetch multiple rows
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    // Method untuk execute INSERT, UPDATE, DELETE
    public function execute($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            die("Execute Error: " . $e->getMessage());
        }
    }
        // Method untuk mendapatkan ID terakhir yang di-insert
    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }

    // Method untuk hitung jumlah row yang terpengaruh
    public function rowCount($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    // Method untuk begin transaction
    public function beginTransaction() {
        return $this->conn->beginTransaction();
    }

    // Method untuk commit transaction
    public function commit() {
        return $this->conn->commit();
    }

    // Method untuk rollback transaction
    public function rollback() {
        return $this->conn->rollBack();
    }
}
?>