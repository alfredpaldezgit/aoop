<?php
/**
 * Command Line Setup Script
 * Run from terminal: php setup-cli.php
 * This script automates the database setup without requiring a web browser
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
require_once __DIR__ . '/config/database.php';

class CLISetup {
    private $pdo;

    /**
     * Print colored output
     */
    public function output($message, $color = 'white') {
        $colors = [
            'white' => "\033[0;37m",
            'green' => "\033[0;32m",
            'red' => "\033[0;31m",
            'yellow' => "\033[1;33m",
            'blue' => "\033[0;34m",
            'reset' => "\033[0m"
        ];

        $colorCode = $colors[$color] ?? $colors['white'];
        echo $colorCode . $message . $colors['reset'] . "\n";
    }

    /**
     * Connect to MySQL
     */
    public function connectToDatabase() {
        $this->output("[*] Connecting to MySQL server at " . DB_HOST . "...", 'blue');

        try {
            $this->pdo = new PDO(
                "mysql:host=" . DB_HOST,
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            $this->output("[✓] Connected successfully", 'green');
            return true;
        } catch (PDOException $e) {
            $this->output("[✗] Connection failed: " . $e->getMessage(), 'red');
            return false;
        }
    }

    /**
     * Create the database
     */
    public function createDatabase() {
        $this->output("[*] Creating database '" . DB_NAME . "'...", 'blue');

        try {
            $this->pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` 
                             CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            $this->output("[✓] Database created", 'green');

            $this->pdo->exec("USE `" . DB_NAME . "`");
            $this->output("[✓] Switched to database '" . DB_NAME . "'", 'green');

            return true;
        } catch (PDOException $e) {
            $this->output("[✗] Failed to create database: " . $e->getMessage(), 'red');
            return false;
        }
    }

    /**
     * Create all required tables
     */
    public function createTables() {
        $this->output("[*] Creating tables...", 'blue');

        try {
            // Categories table
            $this->pdo->exec("CREATE TABLE IF NOT EXISTS `categories` (
                `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(255) NOT NULL UNIQUE,
                `description` TEXT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
            $this->output("  [✓] Created 'categories' table", 'green');

            // Products table
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
            $this->output("  [✓] Created 'products' table", 'green');

            // Users table
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
            $this->output("  [✓] Created 'users' table", 'green');

            // Audit logs table
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
            $this->output("  [✓] Created 'audit_logs' table", 'green');

            return true;
        } catch (PDOException $e) {
            $this->output("[✗] Failed to create tables: " . $e->getMessage(), 'red');
            return false;
        }
    }

    /**
     * Insert sample data
     */
    public function insertSampleData() {
        $this->output("[*] Inserting sample data...", 'blue');

        try {
            // Categories
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM `categories`");
            if ($stmt->fetchColumn() == 0) {
                $this->pdo->exec("
                    INSERT INTO `categories` (name, description) VALUES
                    ('Electronics', 'Electronic devices and gadgets'),
                    ('Peripherals', 'Computer peripherals and accessories'),
                    ('Furniture', 'Office furniture and equipment'),
                    ('Software', 'Software licenses and digital products')
                ");
                $this->output("  [✓] Inserted sample categories", 'green');
            } else {
                $this->output("  [→] Categories already exist", 'yellow');
            }

            // Products
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
                $this->output("  [✓] Inserted sample products", 'green');
            } else {
                $this->output("  [→] Products already exist", 'yellow');
            }

            // Admin user
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM `users` WHERE role = 'admin'");
            if ($stmt->fetchColumn() == 0) {
                $adminPassword = password_hash('admin123', PASSWORD_BCRYPT);
                $this->pdo->prepare("
                    INSERT INTO `users` (username, email, password, role) 
                    VALUES (?, ?, ?, ?)
                ")->execute(['admin', 'admin@inventory.local', $adminPassword, 'admin']);

                $this->output("  [✓] Created default admin user", 'green');
                $this->output("  [!] Username: admin, Password: admin123", 'yellow');
                $this->output("  [!] CHANGE THIS PASSWORD IMMEDIATELY", 'red');
            } else {
                $this->output("  [→] Admin user already exists", 'yellow');
            }

            return true;
        } catch (PDOException $e) {
            $this->output("[✗] Failed to insert data: " . $e->getMessage(), 'red');
            return false;
        }
    }

    /**
     * Run setup
     */
    public function run() {
        echo "\n";
        $this->output("╔════════════════════════════════════════════════════════╗", 'blue');
        $this->output("║   Inventory Management System - Setup                   ║", 'blue');
        $this->output("╚════════════════════════════════════════════════════════╝", 'blue');
        echo "\n";

        $steps = [
            'connectToDatabase' => 'Database Connection',
            'createDatabase' => 'Database Creation',
            'createTables' => 'Table Creation',
            'insertSampleData' => 'Sample Data'
        ];

        $totalSteps = count($steps);
        $currentStep = 0;

        foreach ($steps as $method => $description) {
            $currentStep++;
            $this->output("[$currentStep/$totalSteps] $description", 'blue');

            if (!$this->{$method}()) {
                echo "\n";
                $this->output("[✗] Setup failed!", 'red');
                return false;
            }
        }

        echo "\n";
        $this->output("╔════════════════════════════════════════════════════════╗", 'green');
        $this->output("║   ✓ Setup completed successfully!                      ║", 'green');
        $this->output("║   You can now access the application                   ║", 'green');
        $this->output("╚════════════════════════════════════════════════════════╝", 'green');
        echo "\n";

        return true;
    }
}

// Run setup
$setup = new CLISetup();
$success = $setup->run();

exit($success ? 0 : 1);
