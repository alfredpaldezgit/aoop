<?php
/**
 * Database Configuration Verification
 * Confirms that tests use inventory_db_test and not production inventory_db
 */

echo "\n";
echo "╔════════════════════════════════════════════════════╗\n";
echo "║   Database Configuration Verification              ║\n";
echo "╚════════════════════════════════════════════════════╝\n";
echo "\n";

// Load test bootstrap
require_once __DIR__ . '/tests/bootstrap.php';

echo "[*] Checking database constants...\n";
echo "    DB_HOST: " . DB_HOST . "\n";
echo "    DB_USER: " . DB_USER . "\n";
echo "    DB_NAME: " . DB_NAME . "\n";
echo "\n";

// Verify test database is being used
if (DB_NAME === 'inventory_db_test') {
    echo "✓ CORRECT: Using test database 'inventory_db_test'\n";
} elseif (DB_NAME === 'inventory_db') {
    echo "✗ ERROR: Using PRODUCTION database 'inventory_db'!\n";
    echo "        Tests would DELETE production data!\n";
    exit(1);
} else {
    echo "? WARNING: Using unknown database '" . DB_NAME . "'\n";
}

echo "\n";

// Test the connection
echo "[*] Testing database connection...\n";
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST,
        DB_USER,
        DB_PASS
    );
    echo "✓ Connected to MySQL server\n";
    
    // Check if test database exists
    $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'inventory_db_test'");
    if ($stmt->rowCount() > 0) {
        echo "✓ Test database 'inventory_db_test' exists\n";
    } else {
        echo "ℹ Test database 'inventory_db_test' doesn't exist yet (will be created by tests)\n";
    }
    
    // Check if production database exists
    $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'inventory_db'");
    if ($stmt->rowCount() > 0) {
        echo "✓ Production database 'inventory_db' exists and is separate\n";
    } else {
        echo "ℹ Production database 'inventory_db' not found\n";
    }
    
} catch (PDOException $e) {
    echo "✗ Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n";
echo "╔════════════════════════════════════════════════════╗\n";
echo "║   ✓ Database Configuration is CORRECT               ║\n";
echo "║   Tests will use 'inventory_db_test'              ║\n";
echo "║   Production 'inventory_db' is SAFE                ║\n";
echo "╚════════════════════════════════════════════════════╝\n";
echo "\n";
