<?php
class Database {
    private static $instance = null;
    private $host = 'localhost';
    private $db_name = 'rango_do_rei';
    private $username = 'root';
    private $password = '3114528100';
    private $conn;

    private function __construct() {}

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        if ($this->conn == null) {
            try {
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                                    $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                echo "Connection error: " . $e->getMessage();
            }
        }
        return $this->conn;
    }
}
?>
