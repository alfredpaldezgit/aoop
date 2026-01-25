<?php
// Defines the InventoryController, which handles user requests and orchestrates the application's response.

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Stats.php';
require_once __DIR__ . '/../helpers/SecurityHelper.php';
require_once __DIR__ . '/../helpers/ReportGenerator.php';

class InventoryController {
    private $productModel;
    private $categoryModel;
    private $statsModel;
    private $reportGenerator;
    private const UPLOAD_DIR = __DIR__ . '/../../public/uploads/images/';

    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
        $this->statsModel = new Stats();
        $this->reportGenerator = new ReportGenerator();
    }

    public function dashboard() {
        $stats = $this->statsModel->getDashboardStats();
        $categoryStats = $this->statsModel->getInventoryByCategory();
        $topProducts = $this->statsModel->getTopProductsByValue(5);
        require_once __DIR__ . '/../views/dashboard.php';
    }

    public function index() {
        $products = $this->productModel->getAll();
        $categories = $this->categoryModel->getAll();
        require_once __DIR__ . '/../views/inventory.php';
    }

    public function reports() {
        require_once __DIR__ . '/../views/reports.php';
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

        // Basic field validation
        if (empty($data['name']) || !isset($data['quantity']) || !isset($data['price']) || empty($data['category_id'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid input. Please fill all fields.']);
            SecurityHelper::logSecurityEvent('VALIDATION_FAILED', ['reason' => 'Missing required fields']);
            return;
        }

        // Enhanced validation
        $nameValidation = SecurityHelper::validateProductName($data['name']);
        if (!$nameValidation['valid']) {
            http_response_code(400);
            echo json_encode(['message' => $nameValidation['error']]);
            return;
        }

        $quantityValidation = SecurityHelper::validateQuantity($data['quantity']);
        if (!$quantityValidation['valid']) {
            http_response_code(400);
            echo json_encode(['message' => $quantityValidation['error']]);
            return;
        }

        $priceValidation = SecurityHelper::validatePrice($data['price']);
        if (!$priceValidation['valid']) {
            http_response_code(400);
            echo json_encode(['message' => $priceValidation['error']]);
            return;
        }

        $categoryValidation = SecurityHelper::validateCategoryId($data['category_id']);
        if (!$categoryValidation['valid']) {
            http_response_code(400);
            echo json_encode(['message' => $categoryValidation['error']]);
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

        if (empty($id) || empty($data['name']) || !isset($data['quantity']) || !isset($data['price']) || empty($data['category_id'])) {
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

    public function getLowStockAlerts() {
        header('Content-Type: application/json');
        $alerts = $this->statsModel->getDashboardStats();
        echo json_encode([
            'low_stock_count' => $alerts['low_stock_count'],
            'out_of_stock' => $alerts['out_of_stock'],
            'low_stock_items' => $alerts['low_stock_items']
        ]);
    }

    public function exportCsv() {
        $products = $this->productModel->getAll();
        $categories = $this->categoryModel->getAll();
        $categoryMap = array_column($categories, 'name', 'id');

        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="inventory_export_' . date('Y-m-d_H-i-s') . '.csv"');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Write BOM for Excel UTF-8 compatibility
        fwrite($output, "\xEF\xBB\xBF");

        // Write header row
        fputcsv($output, ['ID', 'Product Name', 'Category', 'Quantity', 'Price', 'Total Value']);

        // Write data rows
        foreach ($products as $product) {
            $totalValue = $product['quantity'] * $product['price'];
            $categoryName = $categoryMap[$product['category_id']] ?? 'N/A';
            fputcsv($output, [
                $product['id'],
                $product['name'],
                $categoryName,
                $product['quantity'],
                number_format($product['price'], 2),
                number_format($totalValue, 2)
            ]);
        }

        fclose($output);
        exit;
    }

    public function generateReport() {
        $type = $_GET['type'] ?? 'inventory';
        header('Content-Type: application/json');

        try {
            $report = [];
            switch ($type) {
                case 'low-stock':
                    $report = $this->reportGenerator->generateLowStockReport();
                    break;
                case 'category-value':
                    $report = $this->reportGenerator->generateCategoryValueReport();
                    break;
                case 'abc-analysis':
                    $report = $this->reportGenerator->generateABCAnalysis();
                    break;
                case 'reorder':
                    $report = $this->reportGenerator->generateReorderReport();
                    break;
                case 'inventory':
                default:
                    $report = $this->reportGenerator->generateInventoryReport();
            }
            echo json_encode($report);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to generate report: ' . $e->getMessage()]);
        }
    }

    public function exportReportPdf() {
        $type = $_GET['type'] ?? 'inventory';
        
        // Get report data
        $reportData = [];
        switch ($type) {
            case 'low-stock':
                $reportData = $this->reportGenerator->generateLowStockReport();
                break;
            case 'abc-analysis':
                $reportData = $this->reportGenerator->generateABCAnalysis();
                break;
            default:
                $reportData = $this->reportGenerator->generateInventoryReport();
        }

        // Set headers for download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="report_' . $type . '_' . date('Y-m-d') . '.pdf"');

        // Generate simple HTML table for PDF conversion
        // Note: For proper PDF generation, consider using a library like TCPDF or mPDF
        $html = $this->generateReportHtml($type, $reportData);
        
        // For now, output as downloadable HTML (can be printed to PDF)
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="report_' . $type . '_' . date('Y-m-d') . '.html"');
        echo $html;
        exit;
    }

    private function generateReportHtml($type, $data) {
        $html = "<!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Inventory Report - " . date('Y-m-d') . "</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
                th { background-color: #0d6efd; color: white; }
                tr:nth-child(even) { background-color: #f9f9f9; }
                h1 { color: #0d6efd; }
                .summary { margin: 20px 0; padding: 15px; background-color: #f0f0f0; border-radius: 5px; }
            </style>
        </head>
        <body>
            <h1>Inventory Report - " . ucfirst(str_replace('-', ' ', $type)) . "</h1>
            <p>Generated on: " . date('Y-m-d H:i:s') . "</p>";

        if ($type === 'inventory' && isset($data['stats'])) {
            $html .= "<div class='summary'>
                <h3>Summary</h3>
                <p>Total Products: " . $data['stats']['total_products'] . "</p>
                <p>Total Quantity: " . $data['stats']['total_quantity'] . "</p>
                <p>Total Value: $" . number_format($data['stats']['total_value'], 2) . "</p>
            </div>";
            
            $html .= "<h3>Products</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total Value</th>
                </tr>";
            foreach ($data['products'] as $product) {
                $html .= "<tr>
                    <td>" . $product['id'] . "</td>
                    <td>" . htmlspecialchars($product['name']) . "</td>
                    <td>" . htmlspecialchars($product['category'] ?? 'N/A') . "</td>
                    <td>" . $product['quantity'] . "</td>
                    <td>$" . number_format($product['price'], 2) . "</td>
                    <td>$" . number_format($product['total_value'], 2) . "</td>
                </tr>";
            }
            $html .= "</table>";
        } else {
            $html .= "<table>
                <tr>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total Value</th>
                </tr>";
            foreach ($data as $item) {
                if (is_array($item)) {
                    $html .= "<tr>
                        <td>" . htmlspecialchars($item['name'] ?? $item['id'] ?? 'N/A') . "</td>
                        <td>" . ($item['quantity'] ?? '-') . "</td>
                        <td>" . ($item['price'] ? '$' . number_format($item['price'], 2) : '-') . "</td>
                        <td>" . ($item['total_value'] ? '$' . number_format($item['total_value'], 2) : '-') . "</td>
                    </tr>";
                }
            }
            $html .= "</table>";
        }

        $html .= "</body></html>";
        return $html;
    }

    private function deleteImageFile($filename) {
        if ($filename && file_exists(self::UPLOAD_DIR . $filename)) {
            unlink(self::UPLOAD_DIR . $filename);
        }
    }
}
