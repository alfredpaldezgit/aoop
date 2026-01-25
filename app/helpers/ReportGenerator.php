<?php
/**
 * Report Generator for inventory reports and analytics
 */

require_once __DIR__ . '/../../config/database.php';

class ReportGenerator {
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
     * Generate inventory report for PDF/HTML
     */
    public function generateInventoryReport() {
        $stats = $this->getStats();
        $products = $this->getProductsForReport();
        $categoryBreakdown = $this->getCategoryBreakdown();

        return [
            'title' => 'Inventory Report',
            'generated_at' => date('Y-m-d H:i:s'),
            'stats' => $stats,
            'products' => $products,
            'categories' => $categoryBreakdown
        ];
    }

    /**
     * Generate low stock alert report
     */
    public function generateLowStockReport($threshold = 10) {
        $query = "SELECT p.id, p.name, p.quantity, p.price, (p.quantity * p.price) as total_value, c.name as category
                  FROM products p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.quantity < :threshold
                  ORDER BY p.quantity ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute(['threshold' => $threshold]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Generate sales/value report by category
     */
    public function generateCategoryValueReport() {
        $query = "SELECT c.id, c.name, COUNT(p.id) as product_count, 
                         SUM(p.quantity) as total_quantity,
                         AVG(p.price) as avg_price,
                         SUM(p.quantity * p.price) as total_value
                  FROM categories c
                  LEFT JOIN products p ON c.id = p.category_id
                  GROUP BY c.id, c.name
                  ORDER BY total_value DESC";
        
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Generate ABC analysis report
     * A: High value items, B: Medium value, C: Low value
     */
    public function generateABCAnalysis() {
        $query = "SELECT id, name, quantity, price, (quantity * price) as total_value
                  FROM products
                  ORDER BY total_value DESC";
        
        $stmt = $this->db->query($query);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $totalValue = 0;
        foreach ($products as $product) {
            $totalValue += $product['total_value'];
        }

        $aItems = [];
        $bItems = [];
        $cItems = [];
        $cumulativeValue = 0;

        foreach ($products as $product) {
            $cumulativeValue += $product['total_value'];
            $percentage = ($cumulativeValue / $totalValue) * 100;

            if ($percentage <= 80) {
                $product['category'] = 'A';
                $aItems[] = $product;
            } elseif ($percentage <= 95) {
                $product['category'] = 'B';
                $bItems[] = $product;
            } else {
                $product['category'] = 'C';
                $cItems[] = $product;
            }
        }

        return [
            'a_items' => $aItems,
            'b_items' => $bItems,
            'c_items' => $cItems,
            'total_value' => $totalValue
        ];
    }

    /**
     * Generate reorder report
     */
    public function generateReorderReport() {
        $query = "SELECT p.id, p.name, p.quantity, p.price, c.name as category
                  FROM products p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.quantity < 20
                  ORDER BY p.quantity ASC";
        
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Helper: Get inventory stats
     */
    private function getStats() {
        $query = "SELECT 
                    COUNT(*) as total_products,
                    SUM(quantity) as total_quantity,
                    SUM(quantity * price) as total_value,
                    AVG(price) as avg_price,
                    MIN(price) as min_price,
                    MAX(price) as max_price
                  FROM products";
        
        $stmt = $this->db->query($query);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Helper: Get products for report
     */
    private function getProductsForReport() {
        $query = "SELECT p.id, p.name, p.quantity, p.price, (p.quantity * p.price) as total_value, c.name as category
                  FROM products p
                  LEFT JOIN categories c ON p.category_id = c.id
                  ORDER BY p.id ASC";
        
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Helper: Get category breakdown
     */
    private function getCategoryBreakdown() {
        $query = "SELECT c.name, COUNT(p.id) as product_count, SUM(p.quantity) as total_quantity,
                         SUM(p.quantity * p.price) as total_value
                  FROM categories c
                  LEFT JOIN products p ON c.id = p.category_id
                  GROUP BY c.id, c.name";
        
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
