<?php
// Defines the InventoryController, which handles user requests and orchestrates the application's response.

require_once __DIR__ . '/../models/Product.php';

class InventoryController {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    /**
     * Displays the main inventory page.
     * It fetches all products from the model and loads the main view.
     */
    public function index() {
        $products = $this->productModel->getAll();
        // The view is loaded, and the `$products` variable becomes available within it.
        require_once __DIR__ . '/../views/inventory.php';
    }

    /**
     * Fetches a single product's data for editing and returns it as JSON.
     * @param int $id The ID of the product to fetch.
     */
    public function get($id) {
        header('Content-Type: application/json');
        $product = $this->productModel->getById($id);
        if ($product) {
            echo json_encode($product);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(['message' => 'Product not found.']);
        }
    }

    /**
     * Handles the creation of a new product via an AJAX request.
     */
    public function create() {
        header('Content-Type: application/json');
        // `php://input` reads raw POST data, ideal for JSON from AJAX.
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['name']) || !isset($data['quantity']) || !isset($data['price'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => 'Invalid input. Please fill all fields.']);
            return;
        }

        $newId = $this->productModel->create($data);
        if ($newId) {
            $newProduct = $this->productModel->getById($newId);
            http_response_code(201); // Created
            echo json_encode($newProduct);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['message' => 'Failed to create product.']);
        }
    }

    /**
     * Handles updating an existing product via an AJAX request.
     */
    public function update() {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id']) || empty($data['name']) || !isset($data['quantity']) || !isset($data['price'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => 'Invalid input. Missing required fields.']);
            return;
        }

        if ($this->productModel->update($data['id'], $data)) {
            $updatedProduct = $this->productModel->getById($data['id']);
            echo json_encode($updatedProduct);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['message' => 'Failed to update product.']);
        }
    }

    /**
     * Handles deleting a product via an AJAX request.
     * @param int $id The ID of the product to delete.
     */
    public function delete($id) {
        header('Content-Type: application/json');
        if (empty($id)) {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => 'Product ID is required.']);
            return;
        }

        if ($this->productModel->delete($id)) {
            echo json_encode(['message' => 'Product deleted successfully.']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['message' => 'Failed to delete product.']);
        }
    }
}
