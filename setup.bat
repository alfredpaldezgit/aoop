@echo off
REM ============================================
REM Inventory Management System - Setup Script
REM ============================================
REM This script automates the setup of the project
REM Make sure MySQL is running in XAMPP before executing

setlocal enabledelayedexpansion

echo.
echo ============================================
echo   Inventory Management System - Setup
echo ============================================
echo.

REM Check if PHP is available
where php >nul 2>nul
if errorlevel 1 (
    echo [ERROR] PHP is not installed or not in PATH
    echo Please install PHP or add it to your PATH environment variable
    pause
    exit /b 1
)

echo [*] Checking for PHP... 
php --version
echo.

REM Check if MySQL is running
echo [*] Checking MySQL connection...
php -r "try { new PDO('mysql:host=localhost', 'root', ''); echo '[OK] MySQL is running'; } catch (Exception $e) { echo '[ERROR] MySQL is not running'; exit(1); }"

if errorlevel 1 (
    echo.
    echo [ERROR] MySQL server is not running!
    echo Please start MySQL from XAMPP Control Panel
    pause
    exit /b 1
)

echo.
echo [*] Starting setup process...
echo.

REM Run the CLI setup script
php setup-cli.php

if errorlevel 1 (
    echo.
    echo [ERROR] Setup failed!
    pause
    exit /b 1
)

echo.
echo [OK] Setup completed! Press any key to exit...
pause
exit /b 0
