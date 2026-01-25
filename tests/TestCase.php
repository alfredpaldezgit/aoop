<?php
/**
 * Base Test Case Class
 * Provides common setup and teardown for all test classes
 */

namespace Tests;

use PDO;
use PDOException;

class TestCase {
    protected $pdo;
    protected $testDbName = 'inventory_db_test';

    /**
     * Set up test database connection
     */
    protected function setUp(): void {
        // Create connection to test database
        try {
            $this->pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . $this->testDbName,
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            $this->markTestSkipped("Test database not available: " . $e->getMessage());
        }
    }

    /**
     * Create test database
     */
    public static function createTestDatabase() {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST,
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            // Drop test database if exists
            $pdo->exec("DROP DATABASE IF EXISTS `inventory_db_test`");

            // Create test database
            $pdo->exec("CREATE DATABASE `inventory_db_test` 
                       CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            // Switch to test database
            $pdo->exec("USE `inventory_db_test`");

            // Create categories table
            $pdo->exec("CREATE TABLE `categories` (
                `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(255) NOT NULL UNIQUE,
                `description` TEXT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            // Create products table
            $pdo->exec("CREATE TABLE `products` (
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
                    REFERENCES `categories`(`id`) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            // Create users table
            $pdo->exec("CREATE TABLE `users` (
                `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `username` VARCHAR(100) NOT NULL UNIQUE,
                `email` VARCHAR(255) NOT NULL UNIQUE,
                `password` VARCHAR(255) NOT NULL,
                `role` ENUM('admin', 'staff', 'viewer') DEFAULT 'staff',
                `status` ENUM('active', 'inactive') DEFAULT 'active',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            return true;
        } catch (PDOException $e) {
            echo "Error creating test database: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Drop test database
     */
    public static function dropTestDatabase() {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST,
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            $pdo->exec("DROP DATABASE IF EXISTS `inventory_db_test`");
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Clear all tables
     */
    protected function clearTables() {
        try {
            $this->pdo->exec("TRUNCATE TABLE products");
            $this->pdo->exec("TRUNCATE TABLE categories");
            $this->pdo->exec("TRUNCATE TABLE users");
        } catch (PDOException $e) {
            // Ignore errors
        }
    }
}
