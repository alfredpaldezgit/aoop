# PHP Inventory Management System

A comprehensive, web-based inventory management system built with PHP, MySQL, and Bootstrap, following the Model-View-Controller (MVC) architectural pattern. This project provides complete CRUD (Create, Read, Update, Delete) functionality, user authentication, advanced reporting, and analytics capabilities.

## 🎯 Features

### Core Features
-   **View Products:** Displays a list of all products with name, quantity, price, and category.
-   **Add Products:** Modal form for seamless product addition with validation.
-   **Edit Products:** Update product information without page refresh.
-   **Delete Products:** Remove products with confirmation protection.
-   **Search & Filter:** Real-time search by product name and category filtering.
-   **Responsive UI:** Clean, modern Bootstrap 5 interface for all devices.
-   **AJAX-Powered:** Asynchronous operations for smooth user experience.

### Advanced Features
-   **🔐 User Authentication:** Registration, login/logout with password hashing (BCrypt)
-   **📊 Dashboard:** Real-time statistics, low-stock alerts, top products, category breakdown
-   **📈 Reports:** Inventory, Low-Stock, Category Analysis, ABC Analysis, Reorder Reports
-   **💾 CSV Export:** Download inventory data in CSV format
-   **🌙 Dark Mode:** Theme toggle with persistent storage
-   **🔒 Security:** Input validation, SQL injection prevention, XSS protection, rate limiting
-   **📋 Analytics:** ABC analysis, category value breakdown, top products by value
-   **⚠️ Stock Alerts:** Low inventory warnings and out-of-stock tracking

## Technology Stack

-   **Backend:** PHP 8+
-   **Database:** MySQL
-   **Frontend:** HTML5, CSS3, JavaScript (jQuery)
-   **Frameworks:** Bootstrap 5, Font Awesome Icons
-   **Server:** Apache (via XAMPP)
-   **Architecture:** Model-View-Controller (MVC)
-   **Security:** BCrypt password hashing, Prepared Statements, Input Sanitization

## Project Structure

```
/
├── app/
│   ├── controllers/
│   │   ├── InventoryController.php    # Main inventory operations
│   │   └── AuthController.php          # Authentication logic
│   ├── models/
│   │   ├── Product.php                 # Product database operations
│   │   ├── Category.php                # Category management
│   │   ├── User.php                    # User authentication model
│   │   └── Stats.php                   # Dashboard statistics
│   ├── helpers/
│   │   ├── SecurityHelper.php          # Validation & security utilities
│   │   └── ReportGenerator.php         # Report generation engine
│   └── views/
│       ├── inventory.php               # Main inventory page
│       ├── dashboard.php               # Dashboard with statistics
│       ├── login.php                   # Login page
│       ├── register.php                # Registration page
│       └── reports.php                 # Reports interface
├── config/
│   ├── database.php                    # Database credentials
│   └── setup.php                       # Database initialization
├── public/
│   ├── js/
│   │   └── app.js                      # AJAX & DOM manipulation
│   └── uploads/
│       └── images/                     # Product images
├── index.php                           # Front Controller & Router
└── README.md                           # Documentation
```

---

## Setup and Installation Guide

Follow these steps carefully to get the application running on your local machine.

### Prerequisites

-   You must have a local web server environment installed. **[XAMPP](https://www.apachefriends.org/index.html)** is recommended as it includes Apache, PHP, and MySQL.

### Step 1: Place Project Files

-   Make sure this entire project folder (named `aoop`) is located inside your XAMPP `htdocs` directory.
-   The correct path should be: `c:\xampp\htdocs\aoop\`

### Step 2: Start Your Local Server

-   Open the XAMPP Control Panel on your computer.
-   Start the **Apache** and **MySQL** services by clicking their "Start" buttons. They should both turn green.

---

## 🚀 Setup and Installation Guide

Follow these steps to get the application running on your local machine.

### Prerequisites

-   **[XAMPP](https://www.apachefriends.org/index.html)** (Apache, PHP 8+, MySQL)
-   Web browser (Chrome, Firefox, Edge, etc.)
-   Basic command line knowledge (optional)

### Step 1: Place Project Files

-   Ensure the project folder (`aoop`) is in: `c:\xampp\htdocs\aoop\`

### Step 2: Start Your Local Server

-   Open XAMPP Control Panel
-   Click "Start" for **Apache** and **MySQL** (both should turn green)

### Step 3: Initialize the Database

-   Open your browser and navigate to:
    ```
    http://localhost/aoop/config/setup.php
    ```
-   This creates the database and all required tables (one-time action)
-   Confirm you see a success message

### Step 4: Create Your Account

-   Go to: `http://localhost/aoop/index.php?action=register-page`
-   Register with a username, email, and password
-   After registration, login with your credentials

### Step 5: Start Using the Application

-   Navigate to: `http://localhost/aoop/`
-   You'll be redirected to login if not authenticated
-   After login, you'll have full access to all features

---

## 📱 Usage Guide

### Main Pages

| Page | URL | Description |
|------|-----|-------------|
| **Inventory** | `http://localhost/aoop/` | View, add, edit, delete products |
| **Dashboard** | `?action=dashboard` | Statistics, alerts, top products |
| **Reports** | `?action=reports` | Generate various inventory reports |
| **Login** | `?action=login-page` | User authentication |
| **Register** | `?action=register-page` | Create new account |
| **Logout** | `?action=logout` | Sign out from account |

### Key Features

**Dashboard**
- View total products, inventory value, and low-stock items
- Monitor out-of-stock count and average prices
- Check top products by value and category breakdown

**Inventory Management**
- Search products by name (real-time filtering)
- Filter by category
- Add new products with image upload
- Edit existing products
- Delete products with confirmation
- Dark mode toggle for comfortable viewing

**Reports**
- **Full Inventory Report**: Complete product listing
- **Low Stock Alert**: Products below threshold
- **Category Analysis**: Value breakdown by category
- **ABC Analysis**: Pareto analysis for inventory optimization
- **Reorder Report**: Items needing replenishment
- Export data to CSV or HTML

**Security**
- User authentication with secure password hashing
- Role-based access control (Admin, Staff, Viewer)
- Input validation and sanitization
- SQL injection prevention
- XSS protection

---

## 🔐 User Roles

- **Admin**: Full access to all features
- **Staff**: Can manage inventory and view reports
- **Viewer**: Read-only access to inventory and dashboard

---

## 📊 Database Schema

### Users Table
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff', 'viewer') DEFAULT 'staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Products Table
```sql
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    quantity INT DEFAULT 0,
    price DECIMAL(10, 2) NOT NULL,
    category_id INT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);
```

### Categories Table
```sql
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 🎨 Customization

### Change Low Stock Threshold
Edit `app/views/inventory.php` and `public/js/app.js`:
```javascript
const LOW_STOCK_THRESHOLD = 10; // Change this value
```

### Customize Colors
Edit CSS in view files:
```css
.card-header { background-color: #0d6efd; } /* Change to your color */
```

### Add New Product Fields
1. Add column to database: `ALTER TABLE products ADD COLUMN new_field VARCHAR(255);`
2. Update Product model
3. Update forms and views

---

## 🐛 Troubleshooting

**Issue: "Database Connection Failed"**
- Ensure MySQL is running in XAMPP
- Check database credentials in `config/database.php`
- Run setup.php again

**Issue: "Undefined constant PDO::ATTR_ERRMODE"**
- Ensure PHP 8.0+ is installed
- Check XAMPP PHP version

**Issue: "Login always fails"**
- Check browser cookies/storage
- Clear browser cache
- Try a new user account

**Issue: "Images not uploading"**
- Create `public/uploads/images/` folder
- Ensure folder permissions are writable (755)

---

## 📝 API Endpoints

| Endpoint | Method | Purpose |
|----------|--------|---------|
| `?action=create` | POST | Create new product |
| `?action=get&id=X` | GET | Get product details |
| `?action=update` | POST | Update product |
| `?action=delete&id=X` | GET | Delete product |
| `?action=alerts` | GET | Get stock alerts |
| `?action=export-csv` | GET | Export CSV |
| `?action=report` | GET | Generate report |
| `?action=login` | POST | User login |
| `?action=register` | POST | User registration |

---

## 🚀 Performance Optimization

- All database queries use prepared statements
- AJAX reduces page reloads
- Lazy loading for product images
- CSS and JS minification recommended for production
- Database indexes on frequently queried columns

---

## 📄 License

This project is open source and available under the MIT License.

---

## 👤 Author

Built with PHP, MySQL, and Bootstrap 5

---

## ✨ Latest Updates

### Version 2.0 - Advanced Features Release
- ✅ User Authentication System (Login/Register with BCrypt)
- ✅ Comprehensive Dashboard with Real-time Statistics
- ✅ Multi-type Report Generator (5 different reports)
- ✅ CSV Export Functionality
- ✅ Dark Mode Theme Toggle
- ✅ Enhanced Security (Input Validation, SQL Injection Prevention)
- ✅ Low Stock Alerts & Notifications
- ✅ ABC Analysis for Inventory Optimization
- ✅ Category-based Analytics

---

**Thank you for using the Inventory Management System!**

