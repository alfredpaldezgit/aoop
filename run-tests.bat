@echo off
REM ============================================
REM PHPUnit Test Runner - Windows
REM ============================================
REM This script helps run PHPUnit tests easily

setlocal enabledelayedexpansion

echo.
echo ============================================
echo   PHPUnit Test Runner
echo ============================================
echo.

REM Check if vendor/bin/phpunit exists
if not exist "vendor\bin\phpunit.bat" (
    echo [ERROR] PHPUnit not found!
    echo Please run: composer install --dev
    pause
    exit /b 1
)

REM Show options
echo Choose an option:
echo.
echo 1. Run all tests
echo 2. Run with coverage report
echo 3. Run specific test file
echo 4. Run with verbose output
echo 5. Exit
echo.

set /p choice="Enter your choice (1-5): "

if "%choice%"=="1" (
    echo.
    echo Running all tests...
    echo.
    vendor\bin\phpunit
    goto done
)

if "%choice%"=="2" (
    echo.
    echo Running tests with coverage report...
    echo.
    vendor\bin\phpunit --coverage-html coverage
    echo.
    echo Coverage report generated in: coverage/index.html
    goto done
)

if "%choice%"=="3" (
    echo.
    set /p testfile="Enter test file name (e.g., SecurityHelperTest.php): "
    vendor\bin\phpunit tests/Unit/!testfile!
    goto done
)

if "%choice%"=="4" (
    echo.
    echo Running tests with verbose output...
    echo.
    vendor\bin\phpunit --verbose
    goto done
)

if "%choice%"=="5" (
    echo Exiting...
    exit /b 0
)

echo Invalid choice!
pause
exit /b 1

:done
pause
exit /b 0
