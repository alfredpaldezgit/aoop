<?php
// User model for handling user authentication

if (!defined('DB_HOST')) {
    require_once __DIR__ . '/../../config/database.php';
}

class User {
    private $db;
    private $table = 'users';

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
     * Register a new user
     */
    public function register($data) {
        // Check if user already exists
        $query = "SELECT id FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['email' => $data['email']]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Email already registered'];
        }

        // Hash password and insert user
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $query = "INSERT INTO {$this->table} (username, email, password, role) VALUES (:username, :email, :password, :role)";
        $stmt = $this->db->prepare($query);

        if ($stmt->execute([
            'username' => htmlspecialchars(strip_tags($data['username'])),
            'email' => htmlspecialchars(strip_tags($data['email'])),
            'password' => $hashedPassword,
            'role' => $data['role'] ?? 'staff'
        ])) {
            return ['success' => true, 'message' => 'User registered successfully'];
        }
        return ['success' => false, 'message' => 'Registration failed'];
    }

    /**
     * Login user
     */
    public function login($email, $password) {
        $query = "SELECT id, username, email, role FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $this->getPasswordHash($email))) {
            return $user;
        }
        return false;
    }

    /**
     * Get password hash for verification
     */
    private function getPasswordHash($email) {
        $query = "SELECT password FROM {$this->table} WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['password'] ?? '';
    }

    /**
     * Get user by ID
     */
    public function getById($id) {
        $query = "SELECT id, username, email, role FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Check if table exists
     */
    public static function tableExists($db) {
        try {
            $result = $db->query("SELECT 1 FROM users LIMIT 1");
            return $result !== false;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
