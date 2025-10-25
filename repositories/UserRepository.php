<?php
require_once 'config/database.php';
require_once 'models/User.php';

class UserRepository {
    private $conn;

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function create(User $user) {
        $query = "INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $hashedPassword = password_hash($user->password, PASSWORD_DEFAULT);
        return $stmt->execute([$user->name, $user->email, $hashedPassword, 0]);
    }

    public function findByEmail($email) {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $user = new User();
            $user->id = $row['id'];
            $user->name = $row['name'];
            $user->email = $row['email'];
            $user->password = $row['password'];
            $user->is_admin = $row['is_admin'];
            return $user;
        }
        return null;
    }

    public function findById($id) {
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $user = new User();
            $user->id = $row['id'];
            $user->name = $row['name'];
            $user->email = $row['email'];
            $user->password = $row['password'];
            $user->is_admin = $row['is_admin'];
            return $user;
        }
        return null;
    }

    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
}
?>