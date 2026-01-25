<?php
/**
 * Security Helper Tests
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

// Load SecurityHelper
require_once dirname(__DIR__, 2) . '/app/helpers/SecurityHelper.php';

class SecurityHelperTest extends TestCase {
    
    protected function setUp(): void {
        parent::setUp();
        // Start session for CSRF tests
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Test product name validation - valid name
     */
    public function testValidateProductNameValid() {
        $result = \SecurityHelper::validateProductName('Valid Product Name');
        $this->assertTrue($result['valid']);
        $this->assertArrayNotHasKey('error', $result);
    }

    /**
     * Test product name validation - empty name
     */
    public function testValidateProductNameEmpty() {
        $result = \SecurityHelper::validateProductName('');
        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('error', $result);
    }

    /**
     * Test product name validation - too long
     */
    public function testValidateProductNameTooLong() {
        $longName = str_repeat('a', 256);
        $result = \SecurityHelper::validateProductName($longName);
        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('error', $result);
    }

    /**
     * Test product name validation - with whitespace
     */
    public function testValidateProductNameWithWhitespace() {
        $result = \SecurityHelper::validateProductName('  Product Name  ');
        $this->assertTrue($result['valid']);
    }

    /**
     * Test quantity validation - valid quantity
     */
    public function testValidateQuantityValid() {
        $result = \SecurityHelper::validateQuantity(100);
        $this->assertTrue($result['valid']);
    }

    /**
     * Test quantity validation - zero quantity
     */
    public function testValidateQuantityZero() {
        $result = \SecurityHelper::validateQuantity(0);
        $this->assertTrue($result['valid']);
    }

    /**
     * Test quantity validation - negative quantity
     */
    public function testValidateQuantityNegative() {
        $result = \SecurityHelper::validateQuantity(-1);
        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('error', $result);
    }

    /**
     * Test quantity validation - too large
     */
    public function testValidateQuantityTooLarge() {
        $result = \SecurityHelper::validateQuantity(1000000);
        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('error', $result);
    }

    /**
     * Test price validation - valid price
     */
    public function testValidatePriceValid() {
        $result = \SecurityHelper::validatePrice(99.99);
        $this->assertTrue($result['valid']);
    }

    /**
     * Test price validation - zero price
     */
    public function testValidatePriceZero() {
        $result = \SecurityHelper::validatePrice(0);
        $this->assertTrue($result['valid']);
    }

    /**
     * Test price validation - negative price
     */
    public function testValidatePriceNegative() {
        $result = \SecurityHelper::validatePrice(-10.50);
        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('error', $result);
    }

    /**
     * Test price validation - too large
     */
    public function testValidatePriceTooLarge() {
        $result = \SecurityHelper::validatePrice(1000000.00);
        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('error', $result);
    }

    /**
     * Test category ID validation - valid ID
     */
    public function testValidateCategoryIdValid() {
        $result = \SecurityHelper::validateCategoryId(1);
        $this->assertTrue($result['valid']);
    }

    /**
     * Test category ID validation - zero ID
     */
    public function testValidateCategoryIdZero() {
        $result = \SecurityHelper::validateCategoryId(0);
        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('error', $result);
    }

    /**
     * Test category ID validation - negative ID
     */
    public function testValidateCategoryIdNegative() {
        $result = \SecurityHelper::validateCategoryId(-1);
        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('error', $result);
    }

    /**
     * Test string sanitization
     */
    public function testSanitizeString() {
        $input = '<script>alert("XSS")</script>Test';
        $result = \SecurityHelper::sanitizeString($input);
        $this->assertStringNotContainsString('<script>', $result);
        $this->assertStringNotContainsString('</script>', $result);
    }

    /**
     * Test email validation - valid email
     */
    public function testValidateEmailValid() {
        $result = \SecurityHelper::validateEmail('test@example.com');
        $this->assertTrue($result['valid']);
    }

    /**
     * Test email validation - invalid email
     */
    public function testValidateEmailInvalid() {
        $result = \SecurityHelper::validateEmail('not-an-email');
        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('error', $result);
    }

    /**
     * Test email validation - empty email
     */
    public function testValidateEmailEmpty() {
        $result = \SecurityHelper::validateEmail('');
        $this->assertFalse($result['valid']);
        $this->assertArrayHasKey('error', $result);
    }

    /**
     * Test CSRF token generation
     */
    public function testGenerateCsrfToken() {
        $_SESSION = [];
        $token1 = \SecurityHelper::generateCsrfToken();
        $this->assertNotEmpty($token1);
        $this->assertTrue(strlen($token1) > 0);
    }

    /**
     * Test CSRF token verification - valid token
     */
    public function testVerifyCsrfTokenValid() {
        $_SESSION = [];
        $token = \SecurityHelper::generateCsrfToken();
        $result = \SecurityHelper::verifyCsrfToken($token);
        $this->assertTrue($result);
    }

    /**
     * Test CSRF token verification - invalid token
     */
    public function testVerifyCsrfTokenInvalid() {
        $_SESSION = [];
        \SecurityHelper::generateCsrfToken();
        $result = \SecurityHelper::verifyCsrfToken('invalid-token');
        $this->assertFalse($result);
    }

    /**
     * Test rate limiting - within limit
     */
    public function testRateLimitWithinLimit() {
        $_SESSION = [];
        for ($i = 0; $i < 5; $i++) {
            $result = \SecurityHelper::checkRateLimit('test-key', 10, 60);
            $this->assertTrue($result);
        }
    }

    /**
     * Test rate limiting - exceeded limit
     */
    public function testRateLimitExceeded() {
        $_SESSION = [];
        for ($i = 0; $i < 10; $i++) {
            \SecurityHelper::checkRateLimit('test-key', 10, 60);
        }
        $result = \SecurityHelper::checkRateLimit('test-key', 10, 60);
        $this->assertFalse($result);
    }
}
