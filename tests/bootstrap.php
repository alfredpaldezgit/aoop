<?php
/**
 * PHPUnit Bootstrap File
 * Initializes the test environment and sets up necessary configurations
 */

// Define project root
define('PROJECT_ROOT', dirname(__DIR__));

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Set timezone
date_default_timezone_set('UTC');

// Include configuration
require_once PROJECT_ROOT . '/config/database.php';

// Include models and helpers
require_once PROJECT_ROOT . '/app/models/Product.php';
require_once PROJECT_ROOT . '/app/models/User.php';
require_once PROJECT_ROOT . '/app/models/Category.php';
require_once PROJECT_ROOT . '/app/helpers/SecurityHelper.php';

// Start session for tests
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
