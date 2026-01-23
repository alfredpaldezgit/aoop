<?php
/**
 * Front Controller
 *
 * This file is the single entry point for the application. It handles all
 * incoming requests and routes them to the appropriate controller method.
 */

// Set the default timezone
date_default_timezone_set('UTC');

// Autoload the controller
require_once __DIR__ . '/app/controllers/InventoryController.php';

// Instantiate the controller
$controller = new InventoryController();

// --- Basic Routing ---
// Determines the action from the URL, defaulting to 'index' (the main list page).
$action = $_GET['action'] ?? 'index';
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

    case 'index':
    default:
        // If no action is specified, show the main inventory page.
        $controller->index();
        break;
}
