<?php
// Defines the Product model, responsible for all database operations related to products.

// Fetches the database configuration constants.
require_once __DIR__ . '/../../config/database.php';

class Product {
    private $db;
    private $table = 'products';

    /**
     * Constructor to establish a database connection.
     * Dies and displays an error message if the connection fails.
     */
    public function __construct() {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
        try {
            $this->db = new PDO($dsn, DB_USER, DB_PASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // If the database connection fails, stop the script and show an error.
            // This often happens if the database `inventory_db` has not been created yet.
            // Ensure you have run the setup script at /config/setup.php
            die('Database Connection Failed: ' . $e->getMessage() . 
                '<br><br>Please ensure the database is created by running the `config/setup.php` script in your browser.');
        }
    }

    /**
     * Fetches all products from the database.
     * @return array An array of all products.
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetches a single product by its ID.
     * @param int $id The ID of the product.
     * @return mixed The product data or false if not found.
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Creates a new product in the database.
     * @param array $data The data for the new product (name, quantity, price).
     * @return bool True on success, false on failure.
     */
    public function create($data) {
        $query = "INSERT INTO {$this->table} (name, quantity, price) VALUES (:name, :quantity, :price)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'name' => htmlspecialchars(strip_tags($data['name'])),
            'quantity' => filter_var($data['quantity'], FILTER_SANITIZE_NUMBER_INT),
            'price' => filter_var($data['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)
        ]);
    }

    /**
     * Updates an existing product.
     * @param int $id The ID of the product to update.
     * @param array $data The new data for the product.
     * @return bool True on success, false on failure.
     */
    public function update($id, $data) {
        $query = "UPDATE {$this->table} SET name = :name, quantity = :quantity, price = :price WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'id' => $id,
            'name' => htmlspecialchars(strip_tags($data['name'])),
            'quantity' => filter_var($data['quantity'], FILTER_SANITIZE_NUMBER_INT),
            'price' => filter_var($data['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)
        ]);
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
