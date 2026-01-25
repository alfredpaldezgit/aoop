#!/bin/bash
# ============================================
# Inventory Management System - Setup Script
# ============================================
# This script automates the setup of the project
# Make sure MySQL is running before executing

echo ""
echo "============================================"
echo "  Inventory Management System - Setup"
echo "============================================"
echo ""

# Check if PHP is available
if ! command -v php &> /dev/null; then
    echo "[ERROR] PHP is not installed or not in PATH"
    echo "Please install PHP first"
    exit 1
fi

echo "[*] Checking for PHP..."
php --version
echo ""

# Check if MySQL is running
echo "[*] Checking MySQL connection..."
php -r "try { new PDO('mysql:host=localhost', 'root', ''); echo '[OK] MySQL is running'; } catch (Exception \$e) { echo '[ERROR] MySQL is not running'; exit(1); }"

if [ $? -ne 0 ]; then
    echo ""
    echo "[ERROR] MySQL server is not running!"
    echo "Please start MySQL server before running this script"
    exit 1
fi

echo ""
echo "[*] Starting setup process..."
echo ""

# Run the CLI setup script
php setup-cli.php

if [ $? -ne 0 ]; then
    echo ""
    echo "[ERROR] Setup failed!"
    exit 1
fi

echo ""
echo "[OK] Setup completed successfully!"
exit 0
