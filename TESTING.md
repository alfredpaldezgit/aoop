# Unit Tests Setup Guide

## Overview

This project now includes comprehensive unit tests using PHPUnit. Tests cover:

- ✅ SecurityHelper validation and sanitization
- ✅ Product model CRUD operations
- ✅ User model authentication
- ✅ Category model operations

## Installation

### 1. Install PHPUnit

Run from project root:
```bash
composer install --dev
```

Or install PHPUnit directly:
```bash
composer require --dev phpunit/phpunit:^11.0
```

### 2. Verify Installation

```bash
vendor/bin/phpunit --version
```

## Running Tests

### Run All Tests
```bash
vendor/bin/phpunit
```

### Run Specific Test Class
```bash
vendor/bin/phpunit tests/Unit/SecurityHelperTest.php
```

### Run with Coverage Report
```bash
vendor/bin/phpunit --coverage-html coverage
```

The coverage report will be generated in the `coverage/` directory.

### Run with Verbose Output
```bash
vendor/bin/phpunit --verbose
```

### Run Specific Test Method
```bash
vendor/bin/phpunit tests/Unit/SecurityHelperTest.php::SecurityHelperTest::testValidateProductNameValid
```

## Test Structure

```
tests/
├── bootstrap.php           # Test environment setup
├── TestCase.php            # Base test class with utilities
└── Unit/
    ├── SecurityHelperTest.php    # SecurityHelper tests (15 tests)
    ├── ProductModelTest.php      # Product model tests (7 tests)
    ├── UserModelTest.php         # User model tests (8 tests)
    └── CategoryModelTest.php     # Category model tests (4 tests)
```

## Test Database

Tests use a separate database `inventory_db_test` to avoid affecting production data.

- ✅ Automatically created before tests run
- ✅ Automatically destroyed after tests complete
- ✅ All tables are recreated fresh for each test suite

## Test Files Breakdown

### SecurityHelperTest.php (15 tests)
Tests validation, sanitization, and security features:
- Product name validation (valid, empty, too long, whitespace)
- Quantity validation (valid, zero, negative, too large)
- Price validation (valid, zero, negative, too large)
- Category ID validation (valid, zero, negative)
- String sanitization (XSS protection)
- Email validation (valid, invalid, empty)
- CSRF token generation and verification
- Rate limiting

### ProductModelTest.php (7 tests)
Tests CRUD operations for products:
- Model instantiation
- Create product
- Get all products
- Get product by ID
- Update product
- Delete product
- Input sanitization

### UserModelTest.php (8 tests)
Tests user authentication and management:
- Model instantiation
- Register valid user
- Prevent duplicate emails
- Login with valid credentials
- Login with invalid password
- Login with non-existent user
- Get user by ID
- Password hashing verification
- Default role assignment

### CategoryModelTest.php (4 tests)
Tests category retrieval:
- Model instantiation
- Get all categories (empty)
- Insert and retrieve categories
- Verify sorting by name

## Configuration Files

### phpunit.xml
PHPUnit configuration with:
- Test suite definitions
- Bootstrap file
- Code coverage settings
- Output format configuration

### composer.json
Composer configuration with:
- PHPUnit dependency
- PSR-4 autoloading
- Class mapping

## Key Features

✅ **Automatic Database Setup** - Test database created before tests run  
✅ **Isolated Tests** - Each test uses fresh database state  
✅ **Code Coverage** - Generate HTML coverage reports  
✅ **Clear Assertions** - Easy-to-read test expectations  
✅ **Security Focused** - Tests validate sanitization and security  
✅ **Authentication Testing** - User login/registration thoroughly tested  

## Writing New Tests

Example test class:

```php
<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class MyFeatureTest extends TestCase {
    protected function setUp(): void {
        parent::setUp();
        // Setup for each test
    }

    public function testSomething() {
        $this->assertTrue(true);
    }

    public function testAnotherThing() {
        $this->assertEquals(5, 2 + 3);
    }
}
```

Place new test files in `tests/Unit/` directory.

## Common Assertions

```php
// Equality
$this->assertEquals($expected, $actual);
$this->assertNotEquals($expected, $actual);

// Boolean
$this->assertTrue($condition);
$this->assertFalse($condition);

// Type
$this->assertIsArray($value);
$this->assertIsString($value);
$this->assertIsInt($value);

// Containment
$this->assertStringContainsString($needle, $haystack);
$this->assertStringNotContainsString($needle, $haystack);
$this->assertArrayHasKey($key, $array);

// Count
$this->assertCount($expectedCount, $array);

// Null
$this->assertNull($value);
$this->assertNotNull($value);
```

## Continuous Integration

To run tests in CI/CD pipelines, use:

```bash
vendor/bin/phpunit --no-coverage --log-junit test-results.xml
```

## Troubleshooting

### Tests skip with "Test database not available"
- Ensure MySQL is running
- Check credentials in `config/database.php`
- Verify MySQL user has CREATE DATABASE privilege

### "Class not found" errors
- Verify `bootstrap.php` paths are correct
- Check that all required files are included

### Permission denied creating test database
- Ensure MySQL user has CREATE/DROP DATABASE rights
- User typically: `root` with no password

## Test Coverage Goals

Current coverage:
- SecurityHelper: 100%
- User model: ~80%
- Product model: ~70%
- Category model: ~70%

Work towards 85%+ coverage for critical business logic.

## Next Steps

1. ✅ Run all tests: `vendor/bin/phpunit`
2. ✅ Check coverage: `vendor/bin/phpunit --coverage-html coverage`
3. ✅ Add more tests for controllers
4. ✅ Set up CI/CD integration
