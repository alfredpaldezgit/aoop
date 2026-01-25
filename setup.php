<?php
/**
 * Automated Project Setup Script
 * This script automates the initial setup of the Inventory Management System
 * It creates the database, tables, and initializes sample data
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Include database configuration
require_once __DIR__ . '/config/database.php';

class ProjectSetup {
    private $pdo;
    private $logs = [];
    private $errors = [];

    public function __construct() {
        $this->initialize();
    }

    /**
     * Initialize the setup process
     */
    private function initialize() {
        $this->log("Starting Project Setup...");
    }

    /**
     * Log messages
     */
    private function log($message) {
        $this->logs[] = [
            'message' => $message,
            'time' => date('Y-m-d H:i:s'),
            'type' => 'info'
        ];
    }

    /**
     * Log errors
     */
    private function error($message) {
        $this->errors[] = [
            'message' => $message,
            'time' => date('Y-m-d H:i:s'),
            'type' => 'error'
        ];
    }

    /**
     * Connect to MySQL
     */
    public function connectToDatabase() {
        try {
            $this->log("Attempting to connect to MySQL server...");
            
            // First connection without database
            $this->pdo = new PDO(
                "mysql:host=" . DB_HOST,
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            $this->log("✓ Successfully connected to MySQL server at " . DB_HOST);
            return true;
        } catch (PDOException $e) {
            $this->error("✗ Failed to connect to MySQL: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create the database
     */
    public function createDatabase() {
        try {
            $this->log("Creating database '" . DB_NAME . "'...");
            
            $this->pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` 
                             CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            $this->log("✓ Database '" . DB_NAME . "' created successfully");
            
            // Switch to the database
            $this->pdo->exec("USE `" . DB_NAME . "`");
            $this->log("✓ Switched to database '" . DB_NAME . "'");
            
            return true;
        } catch (PDOException $e) {
            $this->error("✗ Failed to create database: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create all required tables
     */
    public function createTables() {
        try {
            $this->log("Creating tables...");

            // Create categories table
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS `categories` (
                `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(255) NOT NULL UNIQUE,
                `description` TEXT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
            $this->log("✓ Created 'categories' table");

            // Create products table
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS `products` (
                `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(255) NOT NULL,
                `description` TEXT NULL,
                `quantity` INT(11) NOT NULL DEFAULT 0,
                `price` DECIMAL(10, 2) NOT NULL,
                `category_id` INT(11) UNSIGNED NULL,
                `image` VARCHAR(255) NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) 
                    REFERENCES `categories`(`id`) ON DELETE SET NULL,
                INDEX `idx_category` (`category_id`),
                INDEX `idx_name` (`name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
            $this->log("✓ Created 'products' table");

            // Create users table
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
                `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `username` VARCHAR(100) NOT NULL UNIQUE,
                `email` VARCHAR(255) NOT NULL UNIQUE,
                `password` VARCHAR(255) NOT NULL,
                `role` ENUM('admin', 'staff', 'viewer') DEFAULT 'staff',
                `status` ENUM('active', 'inactive') DEFAULT 'active',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX `idx_username` (`username`),
                INDEX `idx_email` (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
            $this->log("✓ Created 'users' table");

            // Create audit logs table
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS `audit_logs` (
                `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `user_id` INT(11) UNSIGNED,
                `action` VARCHAR(50) NOT NULL,
                `table_name` VARCHAR(100) NOT NULL,
                `record_id` INT(11) UNSIGNED,
                `old_values` JSON,
                `new_values` JSON,
                `ip_address` VARCHAR(45),
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX `idx_user_id` (`user_id`),
                INDEX `idx_action` (`action`),
                INDEX `idx_created_at` (`created_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
            $this->log("✓ Created 'audit_logs' table");

            return true;
        } catch (PDOException $e) {
            $this->error("✗ Failed to create tables: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Insert sample data
     */
    public function insertSampleData() {
        try {
            $this->log("Inserting sample data...");

            // Check if categories exist
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM `categories`");
            if ($stmt->fetchColumn() == 0) {
                $this->pdo->exec("
                    INSERT INTO `categories` (name, description) VALUES
                    ('Electronics', 'Electronic devices and gadgets'),
                    ('Peripherals', 'Computer peripherals and accessories'),
                    ('Furniture', 'Office furniture and equipment'),
                    ('Software', 'Software licenses and digital products')
                ");
                $this->log("✓ Inserted sample categories");
            } else {
                $this->log("→ Categories already exist, skipping...");
            }

            // Check if products exist
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM `products`");
            if ($stmt->fetchColumn() == 0) {
                $this->pdo->exec("
                    INSERT INTO `products` (name, description, quantity, price, category_id) VALUES
                    ('Laptop Pro', 'High-performance laptop for professionals', 10, 1200.50, 1),
                    ('Mouse', 'Wireless ergonomic mouse', 50, 25.00, 2),
                    ('Mechanical Keyboard', 'RGB mechanical keyboard', 30, 75.99, 2),
                    ('Monitor 27\"', '4K UHD Monitor', 15, 399.99, 1),
                    ('Office Chair', 'Ergonomic office chair with lumbar support', 8, 250.00, 3),
                    ('Desk Lamp', 'LED desk lamp with USB charging', 25, 35.50, 3),
                    ('Microsoft Office', 'Microsoft Office 365 Annual License', 100, 99.99, 4),
                    ('Adobe Creative Suite', 'Adobe Creative Cloud Monthly Subscription', 50, 54.99, 4)
                ");
                $this->log("✓ Inserted sample products");
            } else {
                $this->log("→ Products already exist, skipping...");
            }

            // Check if admin user exists
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM `users` WHERE role = 'admin'");
            if ($stmt->fetchColumn() == 0) {
                $adminPassword = password_hash('admin123', PASSWORD_BCRYPT);
                $this->pdo->prepare("
                    INSERT INTO `users` (username, email, password, role) 
                    VALUES (?, ?, ?, ?)
                ")->execute(['admin', 'admin@inventory.local', $adminPassword, 'admin']);
                
                $this->log("✓ Created default admin user (username: admin, password: admin123)");
                $this->log("⚠ WARNING: Change the default admin password immediately!");
            } else {
                $this->log("→ Admin user already exists, skipping...");
            }

            return true;
        } catch (PDOException $e) {
            $this->error("✗ Failed to insert sample data: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Run all setup steps
     */
    public function runSetup() {
        $steps = [
            'connectToDatabase',
            'createDatabase',
            'createTables',
            'insertSampleData'
        ];

        foreach ($steps as $step) {
            if (!$this->{$step}()) {
                $this->error("Setup stopped at: " . $step);
                return false;
            }
        }

        return true;
    }

    /**
     * Get logs
     */
    public function getLogs() {
        return $this->logs;
    }

    /**
     * Get errors
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Check if setup was successful
     */
    public function isSuccessful() {
        return empty($this->errors);
    }
}

// Handle setup request
$setupComplete = false;
$setup = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'run_setup') {
    $setup = new ProjectSetup();
    $setupComplete = $setup->runSetup();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System - Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .setup-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
            padding: 40px;
        }
        .setup-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .setup-header h1 {
            color: #667eea;
            margin-bottom: 10px;
        }
        .setup-header p {
            color: #666;
            margin: 0;
        }
        .log-entry {
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }
        .log-info {
            background-color: #e3f2fd;
            color: #1976d2;
            border-left: 4px solid #1976d2;
        }
        .log-error {
            background-color: #ffebee;
            color: #c62828;
            border-left: 4px solid #c62828;
        }
        .log-success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border-left: 4px solid #2e7d32;
        }
        .setup-logs {
            max-height: 400px;
            overflow-y: auto;
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
        }
        .status-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .btn-setup {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-setup:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .btn-setup:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #667eea;
        }
        .info-box h5 {
            color: #667eea;
            margin-bottom: 10px;
        }
        .info-box ul {
            margin: 0;
            padding-left: 20px;
        }
        .info-box li {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <div class="setup-header">
            <h1><i class="fas fa-cogs"></i> Setup Wizard</h1>
            <p>Inventory Management System - Initial Configuration</p>
        </div>

        <?php if (!$setupComplete && !$setup): ?>
            <!-- Initial setup screen -->
            <div class="info-box">
                <h5><i class="fas fa-info-circle"></i> Setup Requirements</h5>
                <ul>
                    <li>MySQL server must be running</li>
                    <li>Default credentials: root (no password)</li>
                    <li>Database will be created automatically</li>
                    <li>Sample data will be inserted</li>
                    <li>Default admin account: <strong>admin / admin123</strong></li>
                </ul>
            </div>

            <div class="info-box">
                <h5><i class="fas fa-warning"></i> Important Notes</h5>
                <ul>
                    <li>Change the default admin password after setup</li>
                    <li>Ensure you have proper database backups</li>
                    <li>Do not run setup multiple times in production</li>
                </ul>
            </div>

            <form method="POST" action="">
                <input type="hidden" name="action" value="run_setup">
                <button type="submit" class="btn btn-setup w-100" onclick="return confirm('This will initialize the database. Continue?')">
                    <i class="fas fa-play"></i> Start Setup
                </button>
            </form>

        <?php elseif ($setup): ?>
            <!-- Setup results screen -->
            <div class="setup-logs">
                <?php
                $logs = $setup->getLogs();
                $errors = $setup->getErrors();

                // Display logs
                foreach ($logs as $log) {
                    echo '<div class="log-entry log-info">';
                    echo htmlspecialchars($log['message']);
                    echo '</div>';
                }

                // Display errors
                foreach ($errors as $error) {
                    echo '<div class="log-entry log-error">';
                    echo htmlspecialchars($error['message']);
                    echo '</div>';
                }
                ?>
            </div>

            <?php if ($setup->isSuccessful()): ?>
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle"></i> Setup completed successfully!
                </div>
                <div class="status-badge status-success" style="width: 100%; text-align: center;">
                    ✓ Ready to Use
                </div>
                <a href="index.php" class="btn btn-setup w-100" style="margin-top: 15px;">
                    <i class="fas fa-arrow-right"></i> Go to Application
                </a>
            <?php else: ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-times-circle"></i> Setup encountered errors. Please check the logs above.
                </div>
                <div class="status-badge status-error" style="width: 100%; text-align: center;">
                    ✗ Setup Failed
                </div>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="run_setup">
                    <button type="submit" class="btn btn-setup w-100" style="margin-top: 15px;">
                        <i class="fas fa-redo"></i> Retry Setup
                    </button>
                </form>
            <?php endif; ?>

        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
