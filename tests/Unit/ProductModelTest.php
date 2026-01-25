<?php
/**
 * Product Model Tests
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\TestCase as BaseTestCase;

// Load Product model
require_once dirname(__DIR__, 2) . '/app/models/Product.php';
require_once dirname(__DIR__) . '/TestCase.php';

class ProductModelTest extends TestCase {
    private static $testSetup = false;
    private $pdo;

    public static function setUpBeforeClass(): void {
        BaseTestCase::createTestDatabase();
        self::$testSetup = true;
    }

    public static function tearDownAfterClass(): void {
        BaseTestCase::dropTestDatabase();
    }

    /**
     * Set up test environment before each test
     */
    protected function setUp(): void {
        parent::setUp();

        // Create PDO connection for cleanup
        $this->pdo = new \PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS
        );

        // Clear tables before each test (disable foreign key checks)
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS=0");
        $this->pdo->exec("TRUNCATE TABLE products");
        $this->pdo->exec("TRUNCATE TABLE categories");
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS=1");

        // Insert a default test category
        $this->pdo->exec("INSERT INTO categories (name, description) VALUES ('Test Category', 'Default test category')");
    }

    protected function tearDown(): void {
        parent::tearDown();
        // Cleanup after each test
        if ($this->pdo) {
            $this->pdo->exec("SET FOREIGN_KEY_CHECKS=0");
            $this->pdo->exec("TRUNCATE TABLE products");
            $this->pdo->exec("TRUNCATE TABLE categories");
            $this->pdo->exec("SET FOREIGN_KEY_CHECKS=1");
        }
    }

    /**
     * Test Product model instantiation
     */
    public function testProductModelInstantiation() {
        $this->assertTrue(class_exists('Product'));
    }

    /**
     * Test creating a product
     */
    public function testCreateProduct() {
        $product = new \Product();
        
        // Get the test category ID
        $stmt = $this->pdo->query("SELECT id FROM categories LIMIT 1");
        $category = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        $testData = [
            'name' => 'Test Product',
            'quantity' => 50,
            'price' => 99.99,
            'category_id' => $category['id'],
            'image' => null
        ];

        $result = $product->create($testData);
        $this->assertNotFalse($result);
        $this->assertGreaterThan(0, intval($result));
    }

    /**
     * Test getting all products
     */
    public function testGetAllProducts() {
        $product = new \Product();
        $products = $product->getAll();
        
        $this->assertIsArray($products);
    }

    /**
     * Test getting product by ID
     */
    public function testGetProductById() {
        $product = new \Product();
        
        // Get the test category ID
        $stmt = $this->pdo->query("SELECT id FROM categories LIMIT 1");
        $category = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        $testData = [
            'name' => 'Test Product for Get',
            'quantity' => 25,
            'price' => 49.99,
            'category_id' => $category['id'],
            'image' => null
        ];
        
        $productId = $product->create($testData);
        
        // Now get it
        $retrieved = $product->getById($productId);
        $this->assertIsArray($retrieved);
        $this->assertEquals('Test Product for Get', $retrieved['name']);
        $this->assertEquals(25, $retrieved['quantity']);
        $this->assertEquals(49.99, $retrieved['price']);
    }

    /**
     * Test updating a product
     */
    public function testUpdateProduct() {
        $product = new \Product();
        
        // Get the test category ID
        $stmt = $this->pdo->query("SELECT id FROM categories LIMIT 1");
        $category = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        // Create a product
        $testData = [
            'name' => 'Product to Update',
            'quantity' => 100,
            'price' => 199.99,
            'category_id' => $category['id'],
            'image' => null
        ];
        
        $productId = $product->create($testData);
        
        // Update it
        $updateData = [
            'name' => 'Updated Product',
            'quantity' => 50,
            'price' => 149.99,
            'category_id' => $category['id']
        ];
        
        $result = $product->update($productId, $updateData);
        $this->assertTrue($result);
        
        // Verify update
        $updated = $product->getById($productId);
        $this->assertEquals('Updated Product', $updated['name']);
        $this->assertEquals(50, $updated['quantity']);
        $this->assertEquals(149.99, $updated['price']);
    }

    /**
     * Test deleting a product
     */
    public function testDeleteProduct() {
        $product = new \Product();
        
        // Get the test category ID
        $stmt = $this->pdo->query("SELECT id FROM categories LIMIT 1");
        $category = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        // Create a product
        $testData = [
            'name' => 'Product to Delete',
            'quantity' => 10,
            'price' => 9.99,
            'category_id' => $category['id'],
            'image' => null
        ];
        
        $productId = $product->create($testData);
        
        // Delete it
        $result = $product->delete($productId);
        $this->assertTrue($result);
        
        // Verify deletion
        $deleted = $product->getById($productId);
        $this->assertFalse($deleted);
    }

    /**
     * Test product with sanitization
     */
    public function testCreateProductWithSanitization() {
        $product = new \Product();
        
        // Get the test category ID
        $stmt = $this->pdo->query("SELECT id FROM categories LIMIT 1");
        $category = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        $testData = [
            'name' => '<script>alert("XSS")</script>Safe Product',
            'quantity' => 20,
            'price' => 29.99,
            'category_id' => $category['id'],
            'image' => '<img src=x onerror=alert("XSS")>'
        ];
        
        $productId = $product->create($testData);
        $retrieved = $product->getById($productId);
        
        // Verify sanitization
        $this->assertStringNotContainsString('<script>', $retrieved['name']);
        $this->assertStringNotContainsString('onerror', $retrieved['image']);
    }
}
