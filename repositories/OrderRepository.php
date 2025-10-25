<?php
require_once 'config/database.php';
require_once 'models/Order.php';

class OrderRepository {
    private $conn;

    public function __construct() {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    public function create(Order $order) {
        $this->conn->beginTransaction();
        try {
            $query = "INSERT INTO orders (user_id, total) VALUES (?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$order->user_id, $order->total]);

            $orderId = $this->conn->lastInsertId();

            foreach ($order->items as $item) {
                $query = "INSERT INTO order_items (order_id, item_id, quantity, price) VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
            }

            $this->conn->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function findAll() {
        $query = "SELECT o.*, u.name as user_name FROM orders o
                  JOIN users u ON o.user_id = u.id
                  ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $order = new Order();
            $order->id = $row['id'];
            $order->user_id = $row['user_id'];
            $order->total = $row['total'];
            $order->status = $row['status'];
            $order->created_at = $row['created_at'];
            $order->user_name = $row['user_name'];
            $orders[] = $order;
        }
        return $orders;
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$status, $id]);
    }

    public function getSalesReport($period = 'day') {
        $query = "";
        $params = [];

        switch ($period) {
            case 'day':
                $query = "SELECT DATE(created_at) as date, COUNT(*) as total_orders, SUM(total) as total_revenue,
                                 SUM((SELECT SUM(oi.quantity) FROM order_items oi WHERE oi.order_id = o.id)) as total_items
                          FROM orders o
                          WHERE DATE(created_at) = CURDATE()
                          GROUP BY DATE(created_at)";
                break;
            case 'week':
                $query = "SELECT YEAR(created_at) as year, WEEK(created_at) as week,
                                 CONCAT('Semana ', WEEK(created_at), ' de ', YEAR(created_at)) as period_label,
                                 COUNT(*) as total_orders, SUM(total) as total_revenue,
                                 SUM((SELECT SUM(oi.quantity) FROM order_items oi WHERE oi.order_id = o.id)) as total_items
                          FROM orders o
                          WHERE YEARWEEK(created_at) = YEARWEEK(CURDATE())
                          GROUP BY YEAR(created_at), WEEK(created_at), CONCAT('Semana ', WEEK(created_at), ' de ', YEAR(created_at))";
                break;
            case 'month':
                $query = "SELECT YEAR(created_at) as year, MONTH(created_at) as month,
                                 CONCAT(MONTHNAME(created_at), ' ', YEAR(created_at)) as period_label,
                                 COUNT(*) as total_orders, SUM(total) as total_revenue,
                                 SUM((SELECT SUM(oi.quantity) FROM order_items oi WHERE oi.order_id = o.id)) as total_items
                          FROM orders o
                          WHERE YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE())
                          GROUP BY YEAR(created_at), MONTH(created_at), CONCAT(MONTHNAME(created_at), ' ', YEAR(created_at))";
                break;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTopSellingItems($period = 'month', $limit = 10) {
        $dateCondition = "";

        switch ($period) {
            case 'day':
                $dateCondition = "AND DATE(o.created_at) = CURDATE()";
                break;
            case 'week':
                $dateCondition = "AND YEARWEEK(o.created_at) = YEARWEEK(CURDATE())";
                break;
            case 'month':
                $dateCondition = "AND YEAR(o.created_at) = YEAR(CURDATE()) AND MONTH(o.created_at) = MONTH(CURDATE())";
                break;
        }

        $query = "SELECT i.name, i.price, SUM(oi.quantity) as total_quantity,
                         SUM(oi.quantity * oi.price) as total_revenue
                  FROM order_items oi
                  JOIN orders o ON oi.order_id = o.id
                  JOIN items i ON oi.item_id = i.id
                  WHERE 1=1 $dateCondition
                  GROUP BY i.id, i.name, i.price
                  ORDER BY total_quantity DESC
                  LIMIT $limit";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrdersByStatus($status) {
        $query = "SELECT COUNT(*) as count FROM orders WHERE status = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$status]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    public function getTotalRevenue() {
        $query = "SELECT SUM(total) as total_revenue FROM orders";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_revenue'] ?? 0;
    }

    public function getSalesReportByFilter($filterType, $day, $month, $year) {
        $query = "";
        $params = [];

        switch ($filterType) {
            case 'today':
                $query = "SELECT DATE(created_at) as date, COUNT(*) as total_orders, SUM(total) as total_revenue,
                                 SUM((SELECT SUM(oi.quantity) FROM order_items oi WHERE oi.order_id = o.id)) as total_items
                          FROM orders o
                          WHERE DATE(created_at) = CURDATE()
                          GROUP BY DATE(created_at)";
                break;
            case 'day':
                $query = "SELECT DATE(created_at) as date, COUNT(*) as total_orders, SUM(total) as total_revenue,
                                 SUM((SELECT SUM(oi.quantity) FROM order_items oi WHERE oi.order_id = o.id)) as total_items
                          FROM orders o
                          WHERE DAY(created_at) = ? AND MONTH(created_at) = ? AND YEAR(created_at) = ?
                          GROUP BY DATE(created_at)";
                $params = [$day, $month, $year];
                break;
            case 'month':
                $query = "SELECT YEAR(created_at) as year, MONTH(created_at) as month,
                                 CONCAT(MONTHNAME(created_at), ' ', YEAR(created_at)) as period_label,
                                 COUNT(*) as total_orders, SUM(total) as total_revenue,
                                 SUM((SELECT SUM(oi.quantity) FROM order_items oi WHERE oi.order_id = o.id)) as total_items
                          FROM orders o
                          WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?
                          GROUP BY YEAR(created_at), MONTH(created_at), CONCAT(MONTHNAME(created_at), ' ', YEAR(created_at))";
                $params = [$month, $year];
                break;
            case 'all':
                $query = "SELECT 'all' as period_type, COUNT(*) as total_orders, SUM(total) as total_revenue,
                                 SUM((SELECT SUM(oi.quantity) FROM order_items oi WHERE oi.order_id = o.id)) as total_items
                          FROM orders o";
                break;
        }

        if (!empty($query)) {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return null;
    }

    public function getTopSellingItemsByFilter($filterType, $day, $month, $year, $limit = 10) {
        $dateCondition = "";

        switch ($filterType) {
            case 'today':
                $dateCondition = "AND DATE(o.created_at) = CURDATE()";
                break;
            case 'day':
                $dateCondition = "AND DAY(o.created_at) = $day AND MONTH(o.created_at) = $month AND YEAR(o.created_at) = $year";
                break;
            case 'month':
                $dateCondition = "AND MONTH(o.created_at) = $month AND YEAR(o.created_at) = $year";
                break;
            case 'all':
                $dateCondition = "";
                break;
        }

        $query = "SELECT i.name, i.price, SUM(oi.quantity) as total_quantity,
                         SUM(oi.quantity * oi.price) as total_revenue
                  FROM order_items oi
                  JOIN orders o ON oi.order_id = o.id
                  JOIN items i ON oi.item_id = i.id
                  WHERE 1=1 $dateCondition
                  GROUP BY i.id, i.name, i.price
                  ORDER BY total_quantity DESC
                  LIMIT $limit";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserOrders($userId) {
        $query = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$userId]);

        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $order = new Order();
            $order->id = $row['id'];
            $order->user_id = $row['user_id'];
            $order->total = $row['total'];
            $order->status = $row['status'];
            $order->created_at = $row['created_at'];
            $orders[] = $order;
        }
        return $orders;
    }

    public function getOrderItems($orderId) {
        $query = "SELECT oi.*, i.name FROM order_items oi
                  JOIN items i ON oi.item_id = i.id
                  WHERE oi.order_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
