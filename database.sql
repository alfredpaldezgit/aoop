-- ============================================
-- Inventory Management System Database Setup
-- ============================================
-- This SQL script creates the complete database structure
-- and initializes sample data for the Inventory Management System

-- Create Database
CREATE DATABASE IF NOT EXISTS `inventory_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE `inventory_db`;

-- ============================================
-- Create Tables
-- ============================================

-- Categories Table
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products Table
CREATE TABLE IF NOT EXISTS `products` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Users Table
CREATE TABLE IF NOT EXISTS `users` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Audit Logs Table
CREATE TABLE IF NOT EXISTS `audit_logs` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Insert Sample Data
-- ============================================

-- Insert Categories
INSERT INTO `categories` (name, description) VALUES
('Electronics', 'Electronic devices and gadgets'),
('Peripherals', 'Computer peripherals and accessories'),
('Furniture', 'Office furniture and equipment'),
('Software', 'Software licenses and digital products');

-- Insert Products
INSERT INTO `products` (name, description, quantity, price, category_id) VALUES
('Laptop Pro', 'High-performance laptop for professionals', 10, 1200.50, 1),
('Mouse', 'Wireless ergonomic mouse', 50, 25.00, 2),
('Mechanical Keyboard', 'RGB mechanical keyboard', 30, 75.99, 2),
('Monitor 27"', '4K UHD Monitor', 15, 399.99, 1),
('Office Chair', 'Ergonomic office chair with lumbar support', 8, 250.00, 3),
('Desk Lamp', 'LED desk lamp with USB charging', 25, 35.50, 3),
('Microsoft Office', 'Microsoft Office 365 Annual License', 100, 99.99, 4),
('Adobe Creative Suite', 'Adobe Creative Cloud Monthly Subscription', 50, 54.99, 4);

-- Insert Default Admin User
-- Username: admin
-- Password: admin123 (hashed with bcrypt)
INSERT INTO `users` (username, email, password, role, status) VALUES
('admin', 'admin@inventory.local', '$2y$10$qMN2wc3qTvXbQGPnGjpYeOoHzjCY3n4b6yDqZm5zVxQ1N8a8pVl0O', 'admin', 'active');

-- ============================================
-- End of Database Setup Script
-- ============================================
