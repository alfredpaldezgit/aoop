<?php
/**
 * User Model Tests
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\TestCase as BaseTestCase;

// Load User model
require_once dirname(__DIR__, 2) . '/app/models/User.php';
require_once dirname(__DIR__) . '/TestCase.php';

class UserModelTest extends TestCase {
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

        // Clear tables before each test
        $this->pdo->exec("TRUNCATE TABLE users");
    }

    protected function tearDown(): void {
        parent::tearDown();
        // Cleanup after each test
        if ($this->pdo) {
            $this->pdo->exec("TRUNCATE TABLE users");
        }
    }

    /**
     * Test User model instantiation
     */
    public function testUserModelInstantiation() {
        $this->assertTrue(class_exists('User'));
    }

    /**
     * Test user registration - valid data
     */
    public function testRegisterUserValid() {
        $user = new \User();
        
        $userData = [
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'SecurePass123!',
            'role' => 'staff'
        ];
        
        $result = $user->register($userData);
        $this->assertTrue($result['success']);
        $this->assertStringContainsString('successfully', strtolower($result['message']));
    }

    /**
     * Test user registration - duplicate email
     */
    public function testRegisterUserDuplicateEmail() {
        $user = new \User();
        
        $userData = [
            'username' => 'testuser2',
            'email' => 'duplicate@example.com',
            'password' => 'SecurePass123!',
            'role' => 'staff'
        ];
        
        // Register first user
        $user->register($userData);
        
        // Try to register with same email
        $userData['username'] = 'testuser3';
        $result = $user->register($userData);
        
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('already registered', strtolower($result['message']));
    }

    /**
     * Test user login - valid credentials
     */
    public function testLoginValidCredentials() {
        $user = new \User();
        
        // Register a user
        $userData = [
            'username' => 'loginuser',
            'email' => 'logintest@example.com',
            'password' => 'TestPass123!',
            'role' => 'staff'
        ];
        
        $user->register($userData);
        
        // Login
        $result = $user->login('logintest@example.com', 'TestPass123!');
        $this->assertIsArray($result);
        $this->assertEquals('loginuser', $result['username']);
        $this->assertEquals('logintest@example.com', $result['email']);
    }

    /**
     * Test user login - invalid password
     */
    public function testLoginInvalidPassword() {
        $user = new \User();
        
        // Register a user
        $userData = [
            'username' => 'passworduser',
            'email' => 'password@example.com',
            'password' => 'CorrectPass123!',
            'role' => 'staff'
        ];
        
        $user->register($userData);
        
        // Try to login with wrong password
        $result = $user->login('password@example.com', 'WrongPass123!');
        $this->assertFalse($result);
    }

    /**
     * Test user login - non-existent user
     */
    public function testLoginNonExistentUser() {
        $user = new \User();
        $result = $user->login('nonexistent@example.com', 'SomePassword123!');
        $this->assertFalse($result);
    }

    /**
     * Test getting user by ID
     */
    public function testGetUserById() {
        $user = new \User();
        
        // Register a user
        $userData = [
            'username' => 'getbyiduser',
            'email' => 'getbyid@example.com',
            'password' => 'TestPass123!',
            'role' => 'admin'
        ];
        
        $user->register($userData);
        
        // Get user details
        $result = $user->login('getbyid@example.com', 'TestPass123!');
        $retrieved = $user->getById($result['id']);
        
        $this->assertIsArray($retrieved);
        $this->assertEquals('getbyiduser', $retrieved['username']);
        $this->assertEquals('getbyid@example.com', $retrieved['email']);
        $this->assertEquals('admin', $retrieved['role']);
    }

    /**
     * Test password hashing
     */
    public function testPasswordHashing() {
        $user = new \User();
        
        $userData = [
            'username' => 'hashuser',
            'email' => 'hash@example.com',
            'password' => 'MySecurePass123!',
            'role' => 'staff'
        ];
        
        $user->register($userData);
        
        // Login should work (password verified correctly)
        $result = $user->login('hash@example.com', 'MySecurePass123!');
        $this->assertIsArray($result);
    }

    /**
     * Test default role assignment
     */
    public function testDefaultRoleAssignment() {
        $user = new \User();
        
        $userData = [
            'username' => 'defaultroleuser',
            'email' => 'defaultrole@example.com',
            'password' => 'TestPass123!'
            // No role specified
        ];
        
        $user->register($userData);
        
        $result = $user->login('defaultrole@example.com', 'TestPass123!');
        $this->assertEquals('staff', $result['role']);
    }
}
