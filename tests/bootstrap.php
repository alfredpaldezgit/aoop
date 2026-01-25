<?php
/**
 * PHPUnit Bootstrap File
 * Initializes the test environment and sets up necessary configurations
 * 
 * IMPORTANT: This file MUST define test database constants FIRST
 * before any models are loaded. This ensures tests use inventory_db_test
 * and never touch the production inventory_db database.
 */

// Define project root
define('PROJECT_ROOT', dirname(__DIR__));

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Set timezone
date_default_timezone_set('UTC');

// ============================================================
// CRITICAL: Define TEST database constants FIRST
// This MUST happen before any code that uses these constants
// ============================================================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'inventory_db_test'); // ALWAYS USE TEST DATABASE

// Include models and helpers (do NOT include production database.php)
require_once PROJECT_ROOT . '/app/models/Product.php';
require_once PROJECT_ROOT . '/app/models/User.php';
require_once PROJECT_ROOT . '/app/models/Category.php';
require_once PROJECT_ROOT . '/app/helpers/SecurityHelper.php';

// Start session for tests BEFORE any output
if (session_status() === PHP_SESSION_NONE) {
    @session_start(); // Suppress warning if headers already sent
}
