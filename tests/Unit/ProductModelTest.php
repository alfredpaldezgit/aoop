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

    public static function setUpBeforeClass(): void {
        BaseTestCase::createTestDatabase();
        self::$testSetup = true;
    }

    public static function tearDownAfterClass(): void {
        BaseTestCase::dropTestDatabase();
    }

    /**
     * Override DB constants for testing
     */
    protected function setUp(): void {
        parent::setUp();
        if (!defined('DB_HOST')) {
            define('DB_HOST', 'localhost');
        }
        if (!defined('DB_USER')) {
            define('DB_USER', 'root');
        }
        if (!defined('DB_PASS')) {
            define('DB_PASS', '');
        }
        if (!defined('DB_NAME')) {
            define('DB_NAME', 'inventory_db_test');
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
        
        // Insert test category first
        $testData = [
            'name' => 'Test Product',
            'quantity' => 50,
            'price' => 99.99,
            'category_id' => 0,
            'image' => null
        ];

        $result = $product->create($testData);
        $this->assertNotFalse($result);
        $this->assertIsInt($result);
        $this->assertGreaterThan(0, $result);
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
        
        // Create a product first
        $testData = [
            'name' => 'Test Product for Get',
            'quantity' => 25,
            'price' => 49.99,
            'category_id' => 0,
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
        
        // Create a product
        $testData = [
            'name' => 'Product to Update',
            'quantity' => 100,
            'price' => 199.99,
            'category_id' => 0,
            'image' => null
        ];
        
        $productId = $product->create($testData);
        
        // Update it
        $updateData = [
            'name' => 'Updated Product',
            'quantity' => 50,
            'price' => 149.99,
            'category_id' => 0
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
        
        // Create a product
        $testData = [
            'name' => 'Product to Delete',
            'quantity' => 10,
            'price' => 9.99,
            'category_id' => 0,
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
        
        $testData = [
            'name' => '<script>alert("XSS")</script>Safe Product',
            'quantity' => 20,
            'price' => 29.99,
            'category_id' => 0,
            'image' => '<img src=x onerror=alert("XSS")>'
        ];
        
        $productId = $product->create($testData);
        $retrieved = $product->getById($productId);
        
        // Verify sanitization
        $this->assertStringNotContainsString('<script>', $retrieved['name']);
        $this->assertStringNotContainsString('onerror', $retrieved['image']);
    }
}
