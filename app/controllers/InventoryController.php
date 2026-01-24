<?php
// Defines the InventoryController, which handles user requests and orchestrates the application's response.

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';

class InventoryController {
    private $productModel;
    private $categoryModel;
    private const UPLOAD_DIR = __DIR__ . '/../../public/uploads/images/';

    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }

    public function index() {
        $products = $this->productModel->getAll();
        $categories = $this->categoryModel->getAll();
        require_once __DIR__ . '/../views/inventory.php';
    }

    public function get($id) {
        header('Content-Type: application/json');
        $product = $this->productModel->getById($id);
        if ($product) {
            echo json_encode($product);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Product not found.']);
        }
    }

    public function create() {
        header('Content-Type: application/json');
        $data = $_POST;

        if (empty($data['name']) || !isset($data['quantity']) || !isset($data['price'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid input. Please fill all fields.']);
            return;
        }

        $imageResult = $this->handleImageUpload();
        if ($imageResult['error']) {
            http_response_code(400);
            echo json_encode(['message' => $imageResult['error']]);
            return;
        }
        if ($imageResult['filename']) {
            $data['image'] = $imageResult['filename'];
        }

        $newId = $this->productModel->create($data);
        if ($newId) {
            $newProduct = $this->productModel->getById($newId);
            http_response_code(201);
            echo json_encode($newProduct);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to create product.']);
        }
    }

    public function update() {
        header('Content-Type: application/json');
        $data = $_POST;
        $id = $data['id'] ?? null;

        if (empty($id) || empty($data['name']) || !isset($data['quantity']) || !isset($data['price'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid input. Missing required fields.']);
            return;
        }

        $existingProduct = $this->productModel->getById($id);
        if (!$existingProduct) {
            http_response_code(404);
            echo json_encode(['message' => 'Product not found.']);
            return;
        }

        $imageResult = $this->handleImageUpload($existingProduct['image']);
        if ($imageResult['error']) {
            http_response_code(400);
            echo json_encode(['message' => $imageResult['error']]);
            return;
        }
        // Add image to data array only if it's being changed
        if ($imageResult['filename'] !== null) {
            $data['image'] = $imageResult['filename'];
        }

        if ($this->productModel->update($id, $data)) {
            $updatedProduct = $this->productModel->getById($id);
            echo json_encode($updatedProduct);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to update product.']);
        }
    }

    public function delete($id) {
        header('Content-Type: application/json');
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(['message' => 'Product ID is required.']);
            return;
        }
        
        $product = $this->productModel->getById($id);
        if ($product && !empty($product['image'])) {
            $this->deleteImageFile($product['image']);
        }

        if ($this->productModel->delete($id)) {
            echo json_encode(['message' => 'Product deleted successfully.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to delete product.']);
        }
    }

    private function handleImageUpload($oldImage = null) {
        $result = ['filename' => null, 'error' => null];

        // Check if a file was uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['image'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxSize = 5 * 1024 * 1024; // 5 MB

            if (!in_array($file['type'], $allowedTypes)) {
                $result['error'] = 'Invalid file type. Only JPG, PNG, and GIF are allowed.';
                return $result;
            }

            if ($file['size'] > $maxSize) {
                $result['error'] = 'File size exceeds the 5MB limit.';
                return $result;
            }

            if (!file_exists(self::UPLOAD_DIR)) {
                mkdir(self::UPLOAD_DIR, 0775, true);
            }

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFilename = uniqid('prod_', true) . '.' . $extension;
            $destination = self::UPLOAD_DIR . $newFilename;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                // If upload is successful and there was an old image, delete it
                if ($oldImage) {
                    $this->deleteImageFile($oldImage);
                }
                $result['filename'] = $newFilename;
            } else {
                $result['error'] = 'Failed to save the uploaded file.';
            }
        }
        // Handle image removal
        else if (isset($_POST['remove_image']) && $_POST['remove_image'] == '1' && $oldImage) {
            $this->deleteImageFile($oldImage);
            $result['filename'] = ''; // Set to empty string to save NULL in DB
        }

        return $result;
    }

    private function deleteImageFile($filename) {
        if ($filename && file_exists(self::UPLOAD_DIR . $filename)) {
            unlink(self::UPLOAD_DIR . $filename);
        }
    }
}
