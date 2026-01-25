#!/bin/bash
# ============================================
# PHPUnit Test Runner - Linux/Mac
# ============================================
# This script helps run PHPUnit tests easily

echo ""
echo "============================================"
echo "  PHPUnit Test Runner"
echo "============================================"
echo ""

# Check if vendor/bin/phpunit exists
if [ ! -f "vendor/bin/phpunit" ]; then
    echo "[ERROR] PHPUnit not found!"
    echo "Please run: composer install --dev"
    exit 1
fi

# Show options
echo "Choose an option:"
echo ""
echo "1. Run all tests"
echo "2. Run with coverage report"
echo "3. Run specific test file"
echo "4. Run with verbose output"
echo "5. Exit"
echo ""

read -p "Enter your choice (1-5): " choice

case $choice in
    1)
        echo ""
        echo "Running all tests..."
        echo ""
        vendor/bin/phpunit
        ;;
    2)
        echo ""
        echo "Running tests with coverage report..."
        echo ""
        vendor/bin/phpunit --coverage-html coverage
        echo ""
        echo "Coverage report generated in: coverage/index.html"
        ;;
    3)
        echo ""
        read -p "Enter test file name (e.g., SecurityHelperTest.php): " testfile
        vendor/bin/phpunit "tests/Unit/$testfile"
        ;;
    4)
        echo ""
        echo "Running tests with verbose output..."
        echo ""
        vendor/bin/phpunit --verbose
        ;;
    5)
        echo "Exiting..."
        exit 0
        ;;
    *)
        echo "Invalid choice!"
        exit 1
        ;;
esac

exit 0
