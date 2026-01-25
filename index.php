<?php
/**
 * Front Controller
 *
 * This file is the single entry point for the application. It handles all
 * incoming requests and routes them to the appropriate controller method.
 */

// Set the default timezone
date_default_timezone_set('UTC');

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoload the controllers
require_once __DIR__ . '/app/controllers/InventoryController.php';
require_once __DIR__ . '/app/controllers/AuthController.php';

// --- Authentication Routes ---
$authController = new AuthController();
$action = $_GET['action'] ?? 'index';

// Check if user is trying to access protected routes
$protectedRoutes = ['index', 'create', 'update', 'delete', 'get', 'dashboard', 'alerts', 'export-csv'];
if (in_array($action, $protectedRoutes) && !$authController->isLoggedIn()) {
    header('Location: index.php?action=login-page');
    exit;
}

// Route auth actions
if (in_array($action, ['login', 'register', 'logout', 'login-page', 'register-page'])) {
    switch ($action) {
        case 'login':
            $authController->login();
            exit;
        case 'register':
            $authController->register();
            exit;
        case 'logout':
            $authController->logout();
            exit;
        case 'login-page':
            $authController->loginPage();
            exit;
        case 'register-page':
            $authController->registerPage();
            exit;
    }
}

// For authenticated routes, instantiate inventory controller
$controller = new InventoryController();

// --- Basic Routing ---
// Determines the action from the URL, defaulting to 'index' (the main list page).
$id = $_GET['id'] ?? null;

// Basic security: Ensure the ID is a valid integer if provided.
if ($id !== null && !filter_var($id, FILTER_VALIDATE_INT)) {
    http_response_code(400); // Bad Request
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Invalid ID specified.']);
    exit;
}

// Route the request to the correct controller method
switch ($action) {
    case 'dashboard':
        $controller->dashboard();
        break;

    case 'reports':
        $controller->reports();
        break;

    case 'get':
        if (!$id) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Product ID is required.']);
        } else {
            $controller->get($id);
        }
        break;

    case 'create':
        // The create method handles reading the POST data.
        $controller->create();
        break;

    case 'update':
        // The update method handles reading the PUT/POST data.
        $controller->update();
        break;

    case 'delete':
        if (!$id) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Product ID is required.']);
        } else {
            $controller->delete($id);
        }
        break;

    case 'alerts':
        $controller->getLowStockAlerts();
        break;

    case 'export-csv':
        $controller->exportCsv();
        break;

    case 'report':
        $controller->generateReport();
        break;

    case 'export-report':
        $controller->exportReportPdf();
        break;

    case 'index':
    default:
        // If no action is specified, show the main inventory page.
        $controller->index();
        break;
}
