<?php
// Defines the Product model, responsible for all database operations related to products.

// Fetches the database configuration constants (only if not already defined)
if (!defined('DB_HOST')) {
    require_once __DIR__ . '/../../config/database.php';
}

class Product {
    private $db;
    private $table = 'products';

    public function __construct() {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
        try {
            $this->db = new PDO($dsn, DB_USER, DB_PASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Database Connection Failed: ' . $e->getMessage() . 
                '<br><br>Please ensure the database is created by running the `config/setup.php` script in your browser.');
        }
    }

    /**
     * Fetches all products from the database, including category and image information.
     * @return array An array of all products.
     */
    public function getAll() {
        $query = "SELECT p.id, p.name, p.quantity, p.price, p.category_id, p.image, c.name as category_name 
                  FROM {$this->table} p
                  LEFT JOIN categories c ON p.category_id = c.id
                  ORDER BY p.id DESC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetches a single product by its ID, including category and image information.
     * @param int $id The ID of the product.
     * @return mixed The product data or false if not found.
     */
    public function getById($id) {
        $query = "SELECT p.id, p.name, p.quantity, p.price, p.category_id, p.image, c.name as category_name
                  FROM {$this->table} p
                  LEFT JOIN categories c ON p.category_id = c.id
                  WHERE p.id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Creates a new product in the database.
     * @param array $data The data for the new product.
     * @return mixed The ID of the new product on success, false on failure.
     */
    public function create($data) {
        $query = "INSERT INTO {$this->table} (name, quantity, price, category_id, image) 
                  VALUES (:name, :quantity, :price, :category_id, :image)";
        $stmt = $this->db->prepare($query);

        $categoryId = filter_var($data['category_id'], FILTER_SANITIZE_NUMBER_INT);
        $image = !empty($data['image']) ? htmlspecialchars(strip_tags($data['image'])) : null;

        if ($stmt->execute([
            'name' => htmlspecialchars(strip_tags($data['name'])),
            'quantity' => filter_var($data['quantity'], FILTER_SANITIZE_NUMBER_INT),
            'price' => filter_var($data['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
            'category_id' => $categoryId,
            'image' => $image
        ])) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Updates an existing product.
     * @param int $id The ID of the product to update.
     * @param array $data The new data for the product.
     * @return bool True on success, false on failure.
     */
    public function update($id, $data) {
        $query = "UPDATE {$this->table} 
                  SET name = :name, quantity = :quantity, price = :price, category_id = :category_id";
        
        $params = [
            'id' => $id,
            'name' => htmlspecialchars(strip_tags($data['name'])),
            'quantity' => filter_var($data['quantity'], FILTER_SANITIZE_NUMBER_INT),
            'price' => filter_var($data['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
            'category_id' => filter_var($data['category_id'], FILTER_SANITIZE_NUMBER_INT)
        ];

        // Conditionally add image to the update query ONLY if it's being changed
        if (array_key_exists('image', $data)) {
            $query .= ", image = :image";
            $params['image'] = $data['image'] ? htmlspecialchars(strip_tags($data['image'])) : null;
        }
        
        $query .= " WHERE id = :id";
        $stmt = $this->db->prepare($query);

        return $stmt->execute($params);
    }

    /**
     * Deletes a product from the database.
     * @param int $id The ID of the product to delete.
     * @return bool True on success, false on failure.
     */
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
}
