<?php
// --- Database Setup Script ---
// This script connects to the MySQL server, creates the database, and the necessary table.

// --- IMPORTANT ---
// 1. Make sure your MySQL server is running (e.g., via XAMPP).
// 2. Access this script from your browser to run it (e.g., http://localhost/aoop/config/setup.php).
// 3. Update the credentials below if they differ from your XAMPP defaults.

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory_db"; // The database to be created

try {
    // 1. Connect to MySQL Server (without specifying a database)
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to MySQL server successfully.<br>";

    // 2. Create the database if it doesn't exist
    $conn->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "Database '<strong>$dbname</strong>' created or already exists.<br>";

    // 3. Select the new database for subsequent operations
    $conn->exec("USE `$dbname`;");
    echo "Switched to database '<strong>$dbname</strong>'.<br>";

    // 4. Create the 'categories' table
    $conn->exec("CREATE TABLE IF NOT EXISTS `categories` (
        `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL UNIQUE
    ) ENGINE=InnoDB;");
    echo "Table '<strong>categories</strong>' created or already exists.<br>";

    // 5. Create the 'products' table
    $conn->exec("CREATE TABLE IF NOT EXISTS `products` (
        `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `quantity` INT(11) NOT NULL,
        `price` DECIMAL(10, 2) NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;");
    echo "Table '<strong>products</strong>' created or already exists.<br>";

    // 6. Add 'category_id' to products table if it doesn't exist
    $stmt = $conn->query("SHOW COLUMNS FROM `products` LIKE 'category_id'");
    if ($stmt->rowCount() == 0) {
        $conn->exec("ALTER TABLE `products` 
                     ADD COLUMN `category_id` INT(11) UNSIGNED NULL AFTER `price`,
                     ADD FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL;");
        echo "Column '<strong>category_id</strong>' and foreign key added to 'products' table.<br>";
    }

    // 7. Add 'image' column to products table if it doesn't exist
    $stmt = $conn->query("SHOW COLUMNS FROM `products` LIKE 'image'");
    if ($stmt->rowCount() == 0) {
        $conn->exec("ALTER TABLE `products` 
                     ADD COLUMN `image` VARCHAR(255) NULL DEFAULT NULL AFTER `category_id`;");
        echo "Column '<strong>image</strong>' added to 'products' table.<br>";
    }

    // 8. Insert sample categories if the table is empty
    $stmt = $conn->query("SELECT COUNT(*) FROM `categories`");
    if ($stmt->fetchColumn() == 0) {
        $conn->exec("
            INSERT INTO `categories` (name) VALUES
            ('Electronics'),
            ('Peripherals');
        ");
        echo "Inserted sample data into '<strong>categories</strong>' table.<br>";
    }

    // 9. Insert sample products if the table is empty
    $stmt = $conn->query("SELECT COUNT(*) FROM `products`");
    if ($stmt->fetchColumn() == 0) {
        // Clear existing data to prevent conflicts
        $conn->exec("TRUNCATE TABLE `products`"); 
        $conn->exec("
            INSERT INTO `products` (name, quantity, price, category_id, image) VALUES
            ('Laptop', 10, 1200.50, 1, NULL),
            ('Mouse', 50, 25.00, 2, NULL),
            ('Keyboard', 30, 75.99, 2, NULL);
        ");
        echo "Inserted sample data into '<strong>products</strong>' table.<br>";
    }

    echo "<hr><strong style='color:green;'>Database setup was successful! You can now browse to the main application.</strong>";

} catch(PDOException $e) {
    // Display error message if something goes wrong
    echo "<strong style='color:red;'>Error: " . $e->getMessage() . "</strong><br>";
    echo "Please check your MySQL credentials in `config/setup.php` and ensure your MySQL server is running.";
}

// Close the connection
$conn = null;
?>
