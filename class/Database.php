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
}
?>