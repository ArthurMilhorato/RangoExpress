<?php
require_once 'config/database.php';
require_once 'models/Item.php';

class ItemRepository {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new ItemRepository();
        }
        return self::$instance;
    }

    public function findAll() {
        $query = "SELECT * FROM items WHERE active = 1 ORDER BY name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $items = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $item = new Item();
            $item->id = $row['id'];
            $item->name = $row['name'];
            $item->description = $row['description'];
            $item->price = $row['price'];
            $item->image = $row['image'];
            $items[] = $item;
        }
        return $items;
    }

    public function findById($id) {
        $query = "SELECT * FROM items WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $item = new Item();
            $item->id = $row['id'];
            $item->name = $row['name'];
            $item->description = $row['description'];
            $item->price = $row['price'];
            $item->image = $row['image'];
            return $item;
        }
        return null;
    }

    public function create(Item $item) {
        $query = "INSERT INTO items (name, description, price, image) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$item->name, $item->description, $item->price, $item->image]);
    }

    public function update(Item $item) {
        $query = "UPDATE items SET name = ?, description = ?, price = ?, image = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$item->name, $item->description, $item->price, $item->image, $item->id]);
    }

    public function delete($id) {
        $query = "UPDATE items SET active = 0 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>