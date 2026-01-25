<?php
// Defines the Stats model, responsible for gathering inventory statistics.

require_once __DIR__ . '/../../config/database.php';

class Stats {
    private $db;

    public function __construct() {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
        try {
            $this->db = new PDO($dsn, DB_USER, DB_PASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Database Connection Failed: ' . $e->getMessage());
        }
    }

    /**
     * Get dashboard statistics
     * @return array Statistics data
     */
    public function getDashboardStats() {
        $stats = [];

        // Total products
        $query = "SELECT COUNT(*) as total_products FROM products";
        $stmt = $this->db->query($query);
        $stats['total_products'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_products'];

        // Total inventory value
        $query = "SELECT SUM(quantity * price) as total_value FROM products";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total_value'] = $result['total_value'] ?? 0;

        // Low stock items (below 10)
        $query = "SELECT COUNT(*) as low_stock_count FROM products WHERE quantity < 10";
        $stmt = $this->db->query($query);
        $stats['low_stock_count'] = $stmt->fetch(PDO::FETCH_ASSOC)['low_stock_count'];

        // Out of stock items
        $query = "SELECT COUNT(*) as out_of_stock FROM products WHERE quantity = 0";
        $stmt = $this->db->query($query);
        $stats['out_of_stock'] = $stmt->fetch(PDO::FETCH_ASSOC)['out_of_stock'];

        // Average product price
        $query = "SELECT AVG(price) as avg_price FROM products";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['avg_price'] = $result['avg_price'] ?? 0;

        // Total quantity in stock
        $query = "SELECT SUM(quantity) as total_quantity FROM products";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total_quantity'] = $result['total_quantity'] ?? 0;

        // Low stock items details
        $query = "SELECT id, name, quantity, price, category_id FROM products WHERE quantity < 10 ORDER BY quantity ASC LIMIT 5";
        $stmt = $this->db->query($query);
        $stats['low_stock_items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }

    /**
     * Get inventory by category
     * @return array Category inventory data
     */
    public function getInventoryByCategory() {
        $query = "SELECT c.id, c.name, COUNT(p.id) as product_count, SUM(p.quantity) as total_quantity, 
                         SUM(p.quantity * p.price) as category_value
                  FROM categories c
                  LEFT JOIN products p ON c.id = p.category_id
                  GROUP BY c.id, c.name
                  ORDER BY category_value DESC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get top products by value
     * @param int $limit
     * @return array Top products
     */
    public function getTopProductsByValue($limit = 5) {
        $query = "SELECT id, name, quantity, price, (quantity * price) as total_value 
                  FROM products 
                  ORDER BY total_value DESC 
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
