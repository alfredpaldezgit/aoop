# Inventory Management System - Setup Guide

## Overview

This project includes multiple setup automation methods to get your inventory management system up and running quickly.

## Setup Methods

### Method 1: Web Browser Setup (Easiest) ✓

1. Make sure MySQL is running in XAMPP
2. Open your browser and navigate to: `http://localhost/aoop/setup.php`
3. Click "Start Setup" button
4. The wizard will create database, tables, and insert sample data
5. After successful setup, click "Go to Application" to access the system

**Credentials after setup:**
- Username: `admin`
- Password: `admin123`

### Method 2: Command Line Setup (Windows)

1. Open Command Prompt or PowerShell
2. Navigate to the project directory:
   ```
   cd C:\xampp\htdocs\aoop
   ```
3. Run the setup batch file:
   ```
   setup.bat
   ```
4. Wait for the setup to complete

### Method 3: Command Line Setup (Linux/Mac)

1. Open Terminal
2. Navigate to the project directory:
   ```
   cd /path/to/aoop
   ```
3. Make the script executable:
   ```
   chmod +x setup.sh
   ```
4. Run the setup script:
   ```
   ./setup.sh
   ```

### Method 4: Direct PHP CLI

Run directly from command line:
```bash
php setup-cli.php
```

## What Gets Set Up

### Database Creation
- Database name: `inventory_db`
- Character set: `utf8mb4`
- Collation: `utf8mb4_unicode_ci`

### Tables Created

1. **categories** - Product categories
   - id, name, description, created_at

2. **products** - Inventory products
   - id, name, description, quantity, price
   - category_id (foreign key), image, timestamps

3. **users** - System users
   - id, username, email, password (hashed)
   - role (admin, staff, viewer), status, timestamps

4. **audit_logs** - Audit trail
   - id, user_id, action, table_name, record_id
   - old_values, new_values, ip_address, timestamps

### Sample Data Inserted

**Categories:**
- Electronics
- Peripherals
- Furniture
- Software

**Products:** 8 sample products with varying quantities and prices

**Admin User:**
- Username: `admin`
- Password: `admin123` (hashed with BCrypt)

## Requirements

- PHP 7.4+ (PHP 8+ recommended)
- MySQL 5.7+
- XAMPP or similar local development environment
- Modern web browser (Chrome, Firefox, Safari, Edge)

## Important Notes

⚠️ **SECURITY WARNINGS:**

1. **Change Default Password**: Immediately change the default admin password after setup in production environments
2. **Database Backups**: Always maintain backups before running setup in production
3. **Credentials**: Never commit database credentials to version control
4. **Single Execution**: Run setup only once in production. Subsequent runs will skip existing data

## Troubleshooting

### "Failed to connect to MySQL"
- Ensure MySQL is running in XAMPP
- Check that credentials in `config/database.php` are correct
- Default: user=`root`, password=`` (empty)

### "Port 3306 already in use"
- Another MySQL instance may be running
- Change XAMPP MySQL port or stop conflicting service

### "Table already exists"
- Setup skips existing tables automatically
- To reset, delete the database manually and re-run setup

### "Permission denied" (Linux/Mac)
- Make script executable: `chmod +x setup.sh`
- Or run with PHP directly: `php setup-cli.php`

## Database Configuration

Edit `config/database.php` to change connection details:

```php
define('DB_HOST', 'localhost');   // MySQL host
define('DB_USER', 'root');        // MySQL username
define('DB_PASS', '');            // MySQL password
define('DB_NAME', 'inventory_db'); // Database name
```

## Post-Setup Steps

1. ✓ Access the application: `http://localhost/aoop/`
2. ✓ Login with admin credentials
3. ✓ Change admin password in user settings
4. ✓ Create additional user accounts as needed
5. ✓ Configure application settings

## File Descriptions

- **setup.php** - Web-based setup wizard with GUI
- **setup-cli.php** - Command-line setup script
- **setup.bat** - Windows batch script for easy execution
- **setup.sh** - Linux/Mac shell script for easy execution
- **config/setup.php** - Legacy setup script (original)

## Next Steps

After setup, you can:
- Add products through the dashboard
- Create user accounts
- Configure categories
- Generate reports
- Set up low-stock alerts

For more information, see the main README.md file.
