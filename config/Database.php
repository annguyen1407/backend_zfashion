<?php
class Database
{
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $database = 'z_fashion';
    private $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $dsn = "mysql:host=$this->host;dbname=$this->database;charset=UTF8";
            $this->conn = new PDO($dsn, $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Kết nối thất bại: " . $e->getMessage();
        }
        return $this->conn;
    }
}
