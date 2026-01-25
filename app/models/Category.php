<?php
// Defines the Category model, responsible for database operations related to categories.

if (!defined('DB_HOST')) {
    require_once __DIR__ . '/../../config/database.php';
}

class Category {
    private $db;
    private $table = 'categories';

    /**
     * Constructor to establish a database connection.
     */
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
     * Fetches all categories from the database.
     * @return array An array of all categories, ordered by name.
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
