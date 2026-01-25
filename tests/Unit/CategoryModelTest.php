<?php
/**
 * Category Model Tests
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\TestCase as BaseTestCase;

// Load Category model
require_once dirname(__DIR__, 2) . '/app/models/Category.php';
require_once dirname(__DIR__) . '/TestCase.php';

class CategoryModelTest extends TestCase {
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
     * Test Category model instantiation
     */
    public function testCategoryModelInstantiation() {
        $this->assertTrue(class_exists('Category'));
    }

    /**
     * Test getting all categories - empty initially
     */
    public function testGetAllCategoriesEmpty() {
        $category = new \Category();
        $categories = $category->getAll();
        
        $this->assertIsArray($categories);
    }

    /**
     * Test inserting and retrieving categories
     */
    public function testInsertAndRetrieveCategories() {
        $category = new \Category();
        
        // Insert test categories using direct query
        $pdo = new \PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS
        );
        
        $pdo->exec("INSERT INTO categories (name, description) VALUES 
                   ('Electronics', 'Electronic devices'),
                   ('Software', 'Software products')");
        
        // Get categories
        $categories = $category->getAll();
        
        $this->assertCount(2, $categories);
        $this->assertEquals('Electronics', $categories[0]['name']);
        $this->assertEquals('Software', $categories[1]['name']);
    }

    /**
     * Test categories are sorted by name
     */
    public function testCategoriesSortedByName() {
        $pdo = new \PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS
        );
        
        // Clear existing categories
        $pdo->exec("TRUNCATE TABLE categories");
        
        // Insert categories in random order
        $pdo->exec("INSERT INTO categories (name) VALUES 
                   ('Zebra Products'),
                   ('Apple Products'),
                   ('Middle Products')");
        
        $category = new \Category();
        $categories = $category->getAll();
        
        // Check if sorted by name
        $this->assertEquals('Apple Products', $categories[0]['name']);
        $this->assertEquals('Middle Products', $categories[1]['name']);
        $this->assertEquals('Zebra Products', $categories[2]['name']);
    }
}
