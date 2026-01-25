# OOP Final Project Documentation: PHP Inventory Management System

---

## 1. PROJECT TITLE AND DESCRIPTION

### 1.1 Project Title
**PHP Inventory Management System - MVC Architecture Implementation**

### 1.2 Project Description
A comprehensive inventory management system developed using Object-Oriented Programming principles and the Model-View-Controller (MVC) architectural pattern in PHP. The system enables organizations to efficiently manage product inventory, track stock levels, categorize products, and generate detailed reports. The application implements user authentication, role-based access control, and maintains data integrity through proper database design.

### 1.3 Project Summary
This project demonstrates the practical application of advanced OOP concepts including:
- Encapsulation and abstraction
- Inheritance and polymorphism
- Design patterns (MVC, Singleton, Factory)
- Database operations with PDO
- User authentication and authorization
- Testing and quality assurance methodologies

---

## 2. TEAM/AUTHOR INFORMATION

### 2.1 Project Authors
| Role | Name | Student ID | Responsibilities |
|------|------|------------|------------------|
| Lead Developer | [Author Name] | [Student ID] | Architecture Design, Core Implementation |
| Database Designer | [Author Name] | [Student ID] | Database Schema, Query Optimization |
| Testing & QA | [Author Name] | [Student ID] | Unit Tests, Integration Testing |
| Documentation Lead | [Author Name] | [Student ID] | Documentation, User Manual |

### 2.2 Instructor/Supervisor
**Instructor Name:** [Instructor Name]  
**Course:** [Course Code - Course Title]  
**Institution:** [University/College Name]  
**Submission Date:** [Submission Date]  
**Academic Term:** [Term/Semester]

### 2.3 Project Version
**Version:** 1.0.0  
**Status:** Final Submission  
**Last Updated:** [Date]

---

## 3. TABLE OF CONTENTS

1. [Project Title and Description](#1-project-title-and-description)
2. [Team/Author Information](#2-teamauthor-information)
3. [Table of Contents](#3-table-of-contents)
4. [Project Objectives and Goals](#4-project-objectives-and-goals)
5. [System Requirements](#5-system-requirements)
6. [Design Documentation](#6-design-documentation)
7. [Implementation Details](#7-implementation-details)
8. [User Manual/Usage Guide](#8-user-manualusage-guide)
9. [Testing and Quality Assurance](#9-testing-and-quality-assurance)
10. [Source Code Documentation](#10-source-code-documentation)
11. [Database Design](#11-database-design)
12. [Security Considerations](#12-security-considerations)
13. [Conclusion and Future Enhancements](#13-conclusion-and-future-enhancements)
14. [References and Appendices](#14-references-and-appendices)

---

## 4. PROJECT OBJECTIVES AND GOALS

### 4.1 Primary Objectives
The primary objectives of this project are to:

1. **Demonstrate OOP Mastery**
   - Apply advanced OOP principles including encapsulation, inheritance, and polymorphism
   - Implement design patterns appropriate to inventory management domain
   - Create reusable and maintainable class structures

2. **Implement MVC Architecture**
   - Separate concerns between data (Model), presentation (View), and business logic (Controller)
   - Ensure scalability and maintainability through proper architectural patterns
   - Demonstrate understanding of application layering

3. **Develop Database Management Skills**
   - Design normalized database schemas
   - Implement efficient database queries
   - Apply proper indexing and optimization techniques
   - Handle concurrent access and data integrity

4. **Create Robust User Management**
   - Implement secure authentication mechanisms
   - Apply role-based access control (RBAC)
   - Manage user sessions and permissions

5. **Ensure Code Quality and Testing**
   - Implement comprehensive unit and integration tests
   - Achieve sufficient code coverage
   - Follow best practices and coding standards

### 4.2 Specific Goals

**Functional Goals:**
- [ ] Enable users to create, read, update, and delete (CRUD) product inventory records
- [ ] Implement product categorization system
- [ ] Generate comprehensive inventory and sales reports
- [ ] Track user actions and maintain audit logs
- [ ] Implement user authentication and authorization

**Non-Functional Goals:**
- [ ] Achieve 80%+ code coverage through automated testing
- [ ] Implement security measures against common web vulnerabilities (OWASP Top 10)
- [ ] Optimize database queries to respond within 2 seconds for common operations
- [ ] Design user interface for ease of use and accessibility
- [ ] Create comprehensive documentation for developers and end-users

**Quality Goals:**
- [ ] Maintain code quality with documentation for all public methods
- [ ] Follow PSR-12 PHP coding standards
- [ ] Implement error handling and logging throughout the application
- [ ] Create automated test suite with minimum 80% coverage

---

## 5. SYSTEM REQUIREMENTS

### 5.1 Software Requirements

#### Web Server
- **Apache HTTP Server** 2.4+ with mod_rewrite enabled
- **Nginx** 1.18+ (alternative)
- **PHP** 7.4+ or 8.0+
  - Required extensions: PDO, PDO_MySQL, JSON, OpenSSL, ctype, mbstring

#### Database Management System
- **MySQL** 5.7.10+ or **MariaDB** 10.2+
- **Database Size:** Minimum 100MB initial capacity

#### Development Tools
- **Composer** 2.0+ (PHP Package Manager)
- **PHP Unit** 9.5+ (Testing Framework)
- **Git** 2.20+ (Version Control)

#### Development Environment
- **OS:** Windows 10+, macOS 10.14+, or Linux (Ubuntu 18.04+)
- **IDE:** Visual Studio Code, PhpStorm, or Sublime Text
- **Browser:** Chrome 90+, Firefox 88+, Safari 14+, Edge 90+

### 5.2 Hardware Requirements

#### Minimum Requirements
- **Processor:** Intel Core i3 or equivalent
- **RAM:** 4GB
- **Disk Space:** 500MB available

#### Recommended Requirements
- **Processor:** Intel Core i5 or equivalent
- **RAM:** 8GB
- **Disk Space:** 2GB SSD

### 5.3 Browser Compatibility
| Browser | Minimum Version | Support Level |
|---------|-----------------|---------------|
| Google Chrome | 90+ | Full Support |
| Mozilla Firefox | 88+ | Full Support |
| Safari | 14+ | Full Support |
| Microsoft Edge | 90+ | Full Support |
| Internet Explorer | N/A | Not Supported |

### 5.4 Installation and Setup Dependencies

```bash
# Required packages (via Composer)
- phpunit/phpunit: ^9.5
- php-di/php-di: ^6.3
- Additional dependencies listed in composer.json
```

---

## 6. DESIGN DOCUMENTATION

### 6.1 Architecture Overview

#### 6.1.1 MVC Architecture
The application follows the Model-View-Controller pattern:

```
┌──────────────────────────────────────────────────────────┐
│                     User Interface (View)                 │
│         (HTML/CSS/JavaScript - Presentation Layer)       │
└──────────────────────────────────────────────────────────┘
                          ↑ ↓
┌──────────────────────────────────────────────────────────┐
│              Controllers (Request Handler)                │
│      (AuthController, InventoryController, etc.)         │
└──────────────────────────────────────────────────────────┘
                          ↑ ↓
┌──────────────────────────────────────────────────────────┐
│           Models (Data & Business Logic)                  │
│    (User, Product, Category, Stats Models)               │
└──────────────────────────────────────────────────────────┘
                          ↑ ↓
┌──────────────────────────────────────────────────────────┐
│              Database Layer (Persistence)                 │
│         (MySQL/MariaDB with PDO Abstraction)             │
└──────────────────────────────────────────────────────────┘
```

#### 6.1.2 Directory Structure
```
aoop/
├── app/
│   ├── controllers/          # Request handlers
│   │   ├── AuthController.php
│   │   └── InventoryController.php
│   ├── core/                 # Core framework classes
│   ├── helpers/              # Utility classes
│   │   ├── ReportGenerator.php
│   │   └── SecurityHelper.php
│   ├── models/               # Data models
│   │   ├── Category.php
│   │   ├── Product.php
│   │   ├── Stats.php
│   │   └── User.php
│   └── views/                # View templates
│       ├── dashboard.php
│       ├── inventory.php
│       ├── login.php
│       ├── register.php
│       └── reports.php
├── config/                   # Configuration files
│   ├── database.php
│   └── setup.php
├── public/                   # Public assets
│   └── js/
│       └── app.js
├── tests/                    # Unit and integration tests
│   ├── Unit/
│   │   ├── CategoryModelTest.php
│   │   ├── ProductModelTest.php
│   │   ├── SecurityHelperTest.php
│   │   └── UserModelTest.php
│   ├── bootstrap.php
│   └── TestCase.php
├── composer.json             # Dependency manager
├── phpunit.xml               # Test configuration
├── database.sql              # Database schema
└── index.php                 # Entry point
```

### 6.2 Class Diagram

```
┌─────────────────────────────────────────────────────────┐
│                      BaseModel                           │
│─────────────────────────────────────────────────────────│
│ - database: PDO                                          │
│ - table: string                                          │
│─────────────────────────────────────────────────────────│
│ + find(id): object                                       │
│ + all(): array                                           │
│ + create(data): bool                                     │
│ + update(id, data): bool                                │
│ + delete(id): bool                                       │
└─────────────────────────────────────────────────────────┘
        ▲              ▲              ▲              ▲
        │              │              │              │
        │              │              │              │
    ┌───┴──┐      ┌────┴───┐    ┌────┴───┐    ┌────┴────┐
    │User  │      │Product │    │Category│    │ Stats   │
    │Model │      │ Model  │    │ Model  │    │ Model   │
    └──────┘      └────────┘    └────────┘    └─────────┘
```

### 6.3 Sequence Diagram: User Login Flow

```
User         Browser        Controller      Model         Database
│              │                │              │              │
├─ Login Request ───────────────────────────────────────────>│
│              │                │              │              │
│              │ POST /login ───>│              │              │
│              │                │              │              │
│              │                │ authenticate>│              │
│              │                │              │ SELECT user  │
│              │                │              │──────────────>
│              │                │              │<─ User data ─
│              │                │<─ User obj ──│              │
│              │<─ Redirect ─────│              │              │
│              │                │              │              │
│<─ Dashboard ─│                │              │              │
│              │                │              │              │
```

### 6.4 Design Patterns Used

#### 6.4.1 MVC Pattern
- Separates data (Model), presentation (View), and logic (Controller)
- Enables parallel development and testing
- Improves code organization and reusability

#### 6.4.2 Singleton Pattern
Database connection class uses Singleton to ensure only one database connection instance exists throughout the application lifecycle.

#### 6.4.3 Factory Pattern
Model instantiation through factory methods in the BaseModel class.

#### 6.4.4 Observer Pattern
Event listeners for user actions and data modifications.

#### 6.4.5 Strategy Pattern
Different report generation strategies (PDF, CSV, JSON).

### 6.5 Data Flow Diagram

```
┌─────────────┐
│  User Input │
└──────┬──────┘
       │
       ▼
┌─────────────────────┐
│   Validation Layer  │
│  (Input Sanitization)
└──────┬──────────────┘
       │
       ▼
┌─────────────────────┐
│    Controller       │
│   (Route Handler)   │
└──────┬──────────────┘
       │
       ▼
┌─────────────────────┐
│  Business Logic     │
│   (Model Class)     │
└──────┬──────────────┘
       │
       ▼
┌─────────────────────┐
│ Database Operations │
│   (CRUD via PDO)    │
└──────┬──────────────┘
       │
       ▼
┌─────────────────────┐
│  Template Engine    │
│  (View Rendering)   │
└──────┬──────────────┘
       │
       ▼
┌─────────────────────┐
│   HTTP Response     │
└─────────────────────┘
```

---

## 7. IMPLEMENTATION DETAILS

### 7.1 Core Components

#### 7.1.1 Database Connection (Singleton Pattern)
**File:** `config/database.php`

**Key Responsibilities:**
- Establish database connection
- Handle connection pooling
- Provide PDO instance to models

**Implementation Features:**
- Private constructor (Singleton)
- Static getInstance() method
- Connection error handling
- Prepared statement support

#### 7.1.2 Base Model Class
**File:** `app/models/[BaseModel.php]`

**Key Responsibilities:**
- Implement common CRUD operations
- Handle database transactions
- Provide query building methods
- Implement data validation

**Public Methods:**
```php
- find($id): object|null
- all(): array
- create($data): bool|int
- update($id, $data): bool
- delete($id): bool
- findBy($column, $value): array
- where($conditions): array
```

#### 7.1.3 User Model
**File:** `app/models/User.php`

**Responsibilities:**
- User authentication
- Password hashing and verification
- User profile management
- Role-based access control

**Key Methods:**
```php
- authenticate($username, $password): bool|User
- register($data): bool|int
- updateProfile($data): bool
- getRoles(): array
- hasPermission($permission): bool
```

#### 7.1.4 Product Model
**File:** `app/models/Product.php`

**Responsibilities:**
- Product inventory management
- Stock level tracking
- Price management
- Product-category relationships

**Key Methods:**
```php
- getByCategory($categoryId): array
- updateStock($productId, $quantity): bool
- getLowStockItems($threshold): array
- search($query): array
```

#### 7.1.5 Category Model
**File:** `app/models/Category.php`

**Responsibilities:**
- Category management
- Category-product relationships
- Category hierarchy (if applicable)

**Key Methods:**
```php
- getAll(): array
- getWithProducts($categoryId): array
- create($name, $description): bool|int
- update($id, $data): bool
```

#### 7.1.6 Stats Model
**File:** `app/models/Stats.php`

**Responsibilities:**
- Generate system statistics
- Track inventory metrics
- Calculate business intelligence data

**Key Methods:**
```php
- getTotalProducts(): int
- getTotalCategories(): int
- getTotalRevenue($startDate, $endDate): float
- getInventoryValue(): float
- getProductsSoldCount($startDate, $endDate): int
```

#### 7.1.7 AuthController
**File:** `app/controllers/AuthController.php`

**Responsibilities:**
- Handle user authentication
- Manage user registration
- Session management
- Login/logout operations

**Key Methods:**
```php
- register(): void
- login(): void
- logout(): void
- profile(): void
- updateProfile(): void
```

#### 7.1.8 InventoryController
**File:** `app/controllers/InventoryController.php`

**Responsibilities:**
- Handle inventory operations
- Manage product CRUD operations
- Category management
- Report generation

**Key Methods:**
```php
- index(): void (list products)
- view($id): void
- create(): void (form & processing)
- edit($id): void
- delete($id): void
- generateReport(): void
```

#### 7.1.9 SecurityHelper
**File:** `app/helpers/SecurityHelper.php`

**Responsibilities:**
- Input validation and sanitization
- CSRF token management
- Password hashing
- SQL injection prevention

**Key Methods:**
```php
- sanitizeInput($data): string|array
- hashPassword($password): string
- verifyPassword($password, $hash): bool
- generateCSRFToken(): string
- validateCSRFToken($token): bool
- escapeHTML($data): string
```

#### 7.1.10 ReportGenerator
**File:** `app/helpers/ReportGenerator.php`

**Responsibilities:**
- Generate various report formats
- Data aggregation and analysis
- Export functionality

**Key Methods:**
```php
- generateInventoryReport(): array
- generateSalesReport($startDate, $endDate): array
- exportToCSV($data): void
- exportToJSON($data): void
- exportToPDF($data): void
```

### 7.2 Implementation Technologies

#### 7.2.1 PHP Features Used
- Object-Oriented Programming (Classes, Inheritance, Traits)
- Namespaces for code organization
- Type hints (scalar and return types)
- Exception handling
- PDO for database abstraction
- Sessions for user management
- File I/O operations

#### 7.2.2 Database Technologies
- SQL (SELECT, INSERT, UPDATE, DELETE)
- Prepared statements (prevent SQL injection)
- Transactions (data consistency)
- Stored procedures (if applicable)
- Indexes (query optimization)

#### 7.2.3 Frontend Technologies
- HTML5 for semantic markup
- CSS3 for styling and responsive design
- JavaScript (Vanilla/jQuery) for interactivity
- AJAX for asynchronous requests
- Form validation (client and server-side)

### 7.3 Key Features Implementation

#### 7.3.1 Authentication
- Username/password-based authentication
- Password hashing using bcrypt/argon2
- Session-based user tracking
- Login attempt throttling
- Session timeout and auto-logout

#### 7.3.2 Authorization
- Role-based access control (RBAC)
- Permission verification middleware
- Resource-level authorization checks

#### 7.3.3 Data Validation
- Input type validation
- String length and format validation
- Unique constraint verification
- Custom validation rules

#### 7.3.4 Error Handling
- Try-catch blocks for exception handling
- User-friendly error messages
- Error logging to files
- Debug mode for development

#### 7.3.5 Data Persistence
- CRUD operations for all entities
- Relationship management
- Transaction handling
- Data consistency checks

---

## 8. USER MANUAL/USAGE GUIDE

### 8.1 Installation and Setup

#### 8.1.1 Prerequisites
Ensure you have installed:
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer
- Apache with mod_rewrite enabled

#### 8.1.2 Installation Steps

**Step 1: Clone or Extract Project**
```bash
cd c:\xampp\htdocs\
# Extract project or clone from repository
```

**Step 2: Install Dependencies**
```bash
cd aoop
composer install
```

**Step 3: Configure Database**
- Edit `config/database.php` with your database credentials:
  ```php
  'host' => 'localhost',
  'database' => 'aoop_db',
  'username' => 'root',
  'password' => ''
  ```

**Step 4: Create Database**
```bash
mysql -u root < database.sql
# Or use phpMyAdmin to import database.sql
```

**Step 5: Verify Installation**
```bash
php phpunit.xml
# Or run: composer test
```

**Step 6: Access Application**
```
http://localhost/aoop/
```

### 8.2 User Roles and Permissions

#### 8.2.1 Administrator
- **Description:** Full system access
- **Permissions:**
  - View all products and inventory
  - Create, edit, delete products
  - Manage categories
  - Manage users and roles
  - Generate reports
  - View system statistics
  - Configure system settings

#### 8.2.2 Manager
- **Description:** Inventory management access
- **Permissions:**
  - View all products
  - Create and edit products
  - Manage stock levels
  - Generate reports
  - View statistics
  - Cannot manage users or system settings

#### 8.2.3 Staff
- **Description:** Limited inventory access
- **Permissions:**
  - View products
  - Update stock levels
  - View own profile
  - Cannot create/delete products
  - Cannot manage categories

### 8.3 Core Features Usage

#### 8.3.1 User Registration and Login

**Registration:**
1. Navigate to `/register`
2. Fill in username, email, and password
3. Click "Register"
4. Account is created and ready to use

**Login:**
1. Navigate to `/login`
2. Enter username and password
3. Click "Login"
4. Redirected to dashboard upon successful authentication

**Logout:**
1. Click "Logout" in navigation menu
2. Session is terminated
3. Redirected to login page

#### 8.3.2 Product Management

**Viewing Products:**
1. Navigate to "Inventory" menu
2. View list of all products
3. Click product name to view details
4. Filter by category or search by name

**Creating Products:**
1. Click "Add Product" button
2. Fill in product details:
   - Product name
   - Category
   - Description
   - Price
   - Stock quantity
3. Click "Save"
4. Product appears in inventory list

**Editing Products:**
1. Navigate to product list
2. Click "Edit" button on desired product
3. Modify product details
4. Click "Update"
5. Changes are saved

**Deleting Products:**
1. Navigate to product list
2. Click "Delete" button on desired product
3. Confirm deletion in popup
4. Product is removed from inventory

#### 8.3.3 Category Management

**Viewing Categories:**
1. Navigate to "Categories" section
2. View list of all categories
3. See number of products in each category

**Creating Categories:**
1. Click "Add Category" button
2. Enter category name and description
3. Click "Create"
4. Category is added to the system

**Editing Categories:**
1. Click "Edit" on desired category
2. Modify category details
3. Click "Update"

**Deleting Categories:**
1. Click "Delete" on desired category
2. Confirm deletion
3. Category is removed (products reassigned or deleted per settings)

#### 8.3.4 Stock Management

**Updating Stock Levels:**
1. Navigate to "Inventory"
2. Find product requiring stock update
3. Click "Update Stock" button
4. Enter new quantity or adjustment amount
5. Click "Save"
6. Stock level is updated

**Viewing Stock Alerts:**
1. Navigate to "Dashboard" or "Stock Alerts"
2. Review products below minimum stock threshold
3. Click product to update stock
4. Reorder as needed

#### 8.3.5 Report Generation

**Generating Inventory Report:**
1. Navigate to "Reports" > "Inventory Report"
2. Select date range (optional)
3. Choose export format (PDF, CSV, JSON)
4. Click "Generate Report"
5. Report is displayed or downloaded

**Generating Sales Report:**
1. Navigate to "Reports" > "Sales Report"
2. Select start and end dates
3. Choose export format
4. Click "Generate Report"
5. Report shows sales metrics and trends

**Viewing Dashboard:**
1. Login and navigate to "Dashboard"
2. View key metrics:
   - Total products
   - Total inventory value
   - Low stock alerts
   - Recent transactions
   - System statistics

### 8.4 Common Tasks

#### 8.4.1 Add New Product with Category

1. Ensure category exists (create if needed)
2. Click "Inventory" > "Add Product"
3. Fill in:
   - Name: [Product Name]
   - Category: [Select Category]
   - Price: [Price]
   - Stock: [Quantity]
   - Description: [Details]
4. Click "Save"

#### 8.4.2 Check Low Stock Items

1. Go to "Dashboard"
2. View "Low Stock Alerts" section
3. Products below threshold are listed
4. Click product to update stock immediately

#### 8.4.3 Export Data

1. Navigate to desired section (Products, Reports)
2. Click "Export" button
3. Select format (CSV, JSON, PDF)
4. Click "Download"
5. File is downloaded to default downloads folder

#### 8.4.4 Change User Password

1. Navigate to "Settings" > "Profile"
2. Click "Change Password"
3. Enter current password
4. Enter new password (twice)
5. Click "Update Password"
6. Confirm with email verification (if enabled)

### 8.5 Troubleshooting

#### 8.5.1 Cannot Access Application
- **Problem:** 404 error or page not found
- **Solution:** 
  - Verify Apache mod_rewrite is enabled
  - Check document root points to project directory
  - Verify .htaccess file exists in root directory

#### 8.5.2 Database Connection Error
- **Problem:** "Cannot connect to database" message
- **Solution:**
  - Verify MySQL is running
  - Check credentials in `config/database.php`
  - Verify database exists
  - Check user permissions

#### 8.5.3 Login Issues
- **Problem:** Login fails despite correct credentials
- **Solution:**
  - Clear browser cookies
  - Check session files have write permissions
  - Verify user account exists in database
  - Check for account lockout due to failed attempts

#### 8.5.4 File Upload Issues
- **Problem:** Cannot upload images or documents
- **Solution:**
  - Verify uploads directory has write permissions (755)
  - Check file size doesn't exceed limit
  - Verify file type is allowed
  - Check disk space available

### 8.6 Best Practices

1. **Regular Backups**
   - Backup database weekly
   - Backup application files monthly
   - Test restore procedures

2. **Security**
   - Change default admin password immediately
   - Use strong passwords (12+ characters)
   - Logout when leaving workstation
   - Review access logs regularly

3. **Data Maintenance**
   - Archive old records regularly
   - Verify data accuracy
   - Update product information promptly
   - Monitor stock levels daily

4. **Performance**
   - Clear old logs monthly
   - Optimize database regularly
   - Monitor system resources
   - Review slow queries

---

## 9. TESTING AND QUALITY ASSURANCE

### 9.1 Testing Strategy

#### 9.1.1 Testing Levels
1. **Unit Testing** - Individual component testing
2. **Integration Testing** - Component interaction testing
3. **System Testing** - Complete application testing
4. **User Acceptance Testing (UAT)** - Client-side validation

#### 9.1.2 Testing Framework
- **Framework:** PHPUnit 9.5+
- **Configuration:** `phpunit.xml`
- **Test Cases:** Located in `tests/` directory

### 9.2 Unit Tests

#### 9.2.1 User Model Tests
**File:** `tests/Unit/UserModelTest.php`

**Test Cases:**
- `testUserCreation()` - Verify user can be created
- `testUserAuthentication()` - Verify login functionality
- `testPasswordHashing()` - Verify password security
- `testUserUpdate()` - Verify profile updates
- `testUserDeletion()` - Verify user removal
- `testInvalidCredentials()` - Verify rejection of wrong password
- `testDuplicateUsername()` - Verify unique username constraint

**Expected Coverage:** 90%+

#### 9.2.2 Product Model Tests
**File:** `tests/Unit/ProductModelTest.php`

**Test Cases:**
- `testProductCreation()` - Verify product insertion
- `testProductRetrieval()` - Verify product fetching
- `testProductUpdate()` - Verify product modification
- `testProductDeletion()` - Verify product removal
- `testStockManagement()` - Verify inventory tracking
- `testCategoryAssignment()` - Verify category relationships
- `testPriceValidation()` - Verify price constraints

**Expected Coverage:** 85%+

#### 9.2.3 Category Model Tests
**File:** `tests/Unit/CategoryModelTest.php`

**Test Cases:**
- `testCategoryCreation()` - Verify category insertion
- `testCategoryRetrieval()` - Verify fetching categories
- `testCategoryUpdate()` - Verify category editing
- `testCategoryDeletion()` - Verify category removal
- `testProductCategory()` - Verify product-category relationships
- `testDuplicateCategory()` - Verify uniqueness

**Expected Coverage:** 85%+

#### 9.2.4 Security Helper Tests
**File:** `tests/Unit/SecurityHelperTest.php`

**Test Cases:**
- `testInputSanitization()` - Verify HTML/SQL escaping
- `testPasswordHashing()` - Verify bcrypt implementation
- `testCSRFTokenGeneration()` - Verify token creation
- `testCSRFTokenValidation()` - Verify token verification
- `testSQLInjectionPrevention()` - Verify prepared statements
- `testXSSPrevention()` - Verify HTML escaping

**Expected Coverage:** 95%+

### 9.3 Integration Tests

#### 9.3.1 Authentication Flow Tests
**Scenarios:**
- User registration with valid data
- User registration with duplicate username
- User login with correct credentials
- User login with incorrect credentials
- Session creation and management
- Logout and session destruction

#### 9.3.2 Inventory Management Flow Tests
**Scenarios:**
- Complete product lifecycle (create, read, update, delete)
- Category and product relationships
- Stock level updates
- Low stock alerts
- Report generation with various filters

#### 9.3.3 Database Transaction Tests
**Scenarios:**
- Transaction rollback on error
- Concurrent data access
- Data consistency checks
- Foreign key constraint enforcement

### 9.4 Code Quality Metrics

#### 9.4.1 Code Coverage Targets
| Component | Target Coverage | Current Coverage |
|-----------|-----------------|------------------|
| Models | 90% | [Current %] |
| Controllers | 85% | [Current %] |
| Helpers | 95% | [Current %] |
| Overall | 85% | [Current %] |

#### 9.4.2 Code Standards
- **Standard:** PSR-12 (Extended Coding Style Guide)
- **Tools:** PHP CodeSniffer, PHPStan
- **Automated Checks:** Pre-commit hooks

#### 9.4.3 Complexity Metrics
| Metric | Threshold | Status |
|--------|-----------|--------|
| Cyclomatic Complexity | < 10 | [Status] |
| Lines per Method | < 50 | [Status] |
| Classes per File | 1 | [Status] |
| Method Count per Class | < 20 | [Status] |

### 9.5 Test Execution

#### 9.5.1 Running Tests

**Run all tests:**
```bash
vendor/bin/phpunit
```

**Run specific test class:**
```bash
vendor/bin/phpunit tests/Unit/UserModelTest.php
```

**Run with coverage report:**
```bash
vendor/bin/phpunit --coverage-html coverage/
```

**Run with verbose output:**
```bash
vendor/bin/phpunit --verbose
```

#### 9.5.2 Test Results
- **Total Tests:** [Number]
- **Passed:** [Number]
- **Failed:** [Number]
- **Skipped:** [Number]
- **Execution Time:** [Time]
- **Code Coverage:** [Percentage]

### 9.6 Quality Assurance Checklist

#### 9.6.1 Pre-Release QA
- [ ] All unit tests passing
- [ ] All integration tests passing
- [ ] Code coverage > 80%
- [ ] No critical security vulnerabilities
- [ ] Database migrations tested
- [ ] Performance benchmarks met
- [ ] Documentation updated
- [ ] User manual reviewed
- [ ] Cross-browser testing completed
- [ ] Accessibility standards met

#### 9.6.2 Functional Testing

**Login/Authentication:**
- [ ] Valid user can login
- [ ] Invalid credentials rejected
- [ ] Session timeout works
- [ ] Password reset functions
- [ ] Account lockout after failed attempts

**Inventory Management:**
- [ ] Products can be created
- [ ] Products can be edited
- [ ] Products can be deleted
- [ ] Stock levels update correctly
- [ ] Categories assign properly

**Reports:**
- [ ] Reports generate without errors
- [ ] Data is accurate
- [ ] Export formats work (CSV, PDF, JSON)
- [ ] Date filters function correctly
- [ ] Large datasets handled efficiently

**User Interface:**
- [ ] Navigation works on all pages
- [ ] Forms validate input
- [ ] Error messages are clear
- [ ] Success messages appear
- [ ] Page load times acceptable

### 9.7 Bug Tracking and Resolution

#### 9.7.1 Bug Report Template
```
Title: [Brief description]
Priority: [Critical/High/Medium/Low]
Severity: [Blocking/Major/Minor]
Steps to Reproduce:
1. [First step]
2. [Second step]
3. [etc.]
Expected Result: [What should happen]
Actual Result: [What actually happens]
Environment: [OS, PHP version, Browser]
```

#### 9.7.2 Bug Resolution Process
1. Report bug with detailed information
2. Assign to developer
3. Reproduce and diagnose issue
4. Implement fix
5. Test fix
6. Update test cases
7. Document resolution
8. Deploy to production

---

## 10. SOURCE CODE DOCUMENTATION

### 10.1 Code Documentation Standards

#### 10.1.1 PHPDoc Comment Format

**Class Documentation:**
```php
/**
 * [Brief description of class]
 *
 * [Detailed description if needed]
 *
 * @author [Author Name]
 * @version [Version]
 * @package [Package Name]
 */
class ClassName
{
    // ...
}
```

**Method Documentation:**
```php
/**
 * [Brief description of method]
 *
 * [Detailed description if needed]
 *
 * @param string $parameter [Parameter description]
 * @param int $count [Parameter description]
 * @return boolean [Return value description]
 * @throws Exception [Exception description]
 */
public function methodName($parameter, $count = 0)
{
    // Implementation
}
```

**Property Documentation:**
```php
/**
 * [Brief description of property]
 *
 * @var string [Type and description]
 */
private $propertyName;
```

#### 10.1.2 Inline Comments
```php
// Calculate total with tax
$total = $subtotal * (1 + $taxRate);

// Check if user has admin permission
if ($user->hasRole('admin')) {
    // Grant access
}
```

### 10.2 Class-by-Class Documentation

#### 10.2.1 User Model

**Purpose:** Manage user accounts, authentication, and authorization

**Key Properties:**
- `$id` - User unique identifier
- `$username` - Unique username
- `$email` - User email address
- `$password` - Hashed password
- `$role` - User role (admin, manager, staff)

**Key Methods:**
```php
public function authenticate($username, $password): bool|User
// Authenticates user with provided credentials
// Returns User object on success, false on failure

public function register($data): bool|int
// Creates new user account
// Returns user ID on success

public function updateProfile($data): bool
// Updates user profile information

public function getRoles(): array
// Returns array of user roles

public function hasPermission($permission): bool
// Checks if user has specified permission
```

**Example Usage:**
```php
$user = new User();
if ($user->authenticate('john_doe', 'password123')) {
    $_SESSION['user_id'] = $user->id;
    // User logged in successfully
}
```

#### 10.2.2 Product Model

**Purpose:** Manage product inventory and details

**Key Properties:**
- `$id` - Product unique identifier
- `$name` - Product name
- `$categoryId` - Associated category
- `$price` - Product price
- `$stock` - Current stock quantity
- `$description` - Product description

**Key Methods:**
```php
public function getByCategory($categoryId): array
// Returns all products in specified category

public function updateStock($productId, $quantity): bool
// Updates product stock level

public function getLowStockItems($threshold): array
// Returns products below stock threshold

public function search($query): array
// Searches products by name or description
```

**Example Usage:**
```php
$product = new Product();
$lowStockItems = $product->getLowStockItems(10);
// Get all products with less than 10 items in stock
```

#### 10.2.3 Category Model

**Purpose:** Manage product categories and organization

**Key Properties:**
- `$id` - Category unique identifier
- `$name` - Category name
- `$description` - Category description

**Key Methods:**
```php
public function getAll(): array
// Returns all categories

public function getWithProducts($categoryId): array
// Returns category with associated products

public function create($name, $description): bool|int
// Creates new category

public function update($id, $data): bool
// Updates category information
```

#### 10.2.4 Stats Model

**Purpose:** Generate system statistics and metrics

**Key Methods:**
```php
public function getTotalProducts(): int
// Returns total number of products

public function getTotalRevenue($startDate, $endDate): float
// Returns total revenue for period

public function getInventoryValue(): float
// Returns total value of current inventory

public function getProductsSoldCount($startDate, $endDate): int
// Returns number of products sold in period
```

#### 10.2.5 AuthController

**Purpose:** Handle authentication and user session management

**Key Methods:**
```php
public function register(): void
// Handles user registration form and processing

public function login(): void
// Handles user login form and authentication

public function logout(): void
// Destroys user session and redirects

public function profile(): void
// Displays user profile page

public function updateProfile(): void
// Updates user profile information
```

#### 10.2.6 InventoryController

**Purpose:** Handle inventory management operations

**Key Methods:**
```php
public function index(): void
// Displays product list

public function view($id): void
// Displays product details

public function create(): void
// Handles product creation form

public function edit($id): void
// Handles product edit form

public function delete($id): void
// Handles product deletion

public function generateReport(): void
// Generates inventory report
```

#### 10.2.7 SecurityHelper

**Purpose:** Provide security utilities and protections

**Key Methods:**
```php
public static function sanitizeInput($data): string|array
// Removes potentially dangerous characters from input

public static function hashPassword($password): string
// Creates secure password hash

public static function verifyPassword($password, $hash): bool
// Verifies password against hash

public static function generateCSRFToken(): string
// Creates CSRF protection token

public static function escapeHTML($data): string
// Escapes HTML special characters
```

#### 10.2.8 ReportGenerator

**Purpose:** Generate and export reports in various formats

**Key Methods:**
```php
public function generateInventoryReport(): array
// Creates inventory report data

public function generateSalesReport($startDate, $endDate): array
// Creates sales report for date range

public function exportToCSV($data): void
// Exports data as CSV file

public function exportToJSON($data): void
// Exports data as JSON file

public function exportToPDF($data): void
// Exports data as PDF file
```

### 10.3 Function/Method Naming Conventions

- **Getters:** `get[PropertyName]()`
  - Example: `getUsername()`, `getUserEmail()`

- **Setters:** `set[PropertyName]($value)`
  - Example: `setUsername($name)`, `setPassword($pwd)`

- **Boolean methods:** `is[State]()`, `has[Property]()`
  - Example: `isActive()`, `hasPermission($perm)`

- **CRUD Operations:** `create()`, `read()`, `update()`, `delete()`
  - Example: `createProduct()`, `updateProduct($id, $data)`

### 10.4 Variable Naming Conventions

- **Local Variables:** camelCase
  - Example: `$userName`, `$productId`, `$totalPrice`

- **Class Properties:** camelCase with $ prefix
  - Example: `$this->userName`, `$this->productId`

- **Constants:** UPPERCASE_WITH_UNDERSCORES
  - Example: `const MAX_LOGIN_ATTEMPTS = 5;`

- **Database Columns:** snake_case
  - Example: `user_id`, `product_name`, `created_at`

---

## 11. DATABASE DESIGN

### 11.1 Entity-Relationship Diagram (ERD)

```
┌─────────────────┐           ┌──────────────────┐
│     USERS       │           │    CATEGORIES    │
├─────────────────┤           ├──────────────────┤
│ PK  id          │           │ PK  id           │
│     username    │           │     name         │
│     email       │           │     description  │
│     password    │           │     created_at   │
│     role        │           │     updated_at   │
│     created_at  │           └──────────────────┘
│     updated_at  │                    ▲
└─────────────────┘                    │
                                       │ FK
                          ┌────────────┴──────────┐
                          │                       │
                    ┌──────────────────┐     
                    │    PRODUCTS      │     
                    ├──────────────────┤     
                    │ PK  id           │     
                    │     name         │     
                    │ FK  category_id  │     
                    │     price        │     
                    │     stock        │     
                    │     description  │     
                    │     created_at   │     
                    │     updated_at   │     
                    └──────────────────┘     
```

### 11.2 Table Definitions

#### 11.2.1 Users Table
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'manager', 'staff') DEFAULT 'staff',
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_role (role)
);
```

**Columns:**
- `id` - Primary key, auto-increment
- `username` - Unique username for login
- `email` - User email address
- `password` - Hashed password using bcrypt
- `role` - User role (admin/manager/staff)
- `is_active` - Account active status
- `last_login` - Last login timestamp
- `created_at` - Account creation timestamp
- `updated_at` - Last update timestamp

#### 11.2.2 Categories Table
```sql
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_name (name)
);
```

**Columns:**
- `id` - Primary key, auto-increment
- `name` - Category name (unique)
- `description` - Category description
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

#### 11.2.3 Products Table
```sql
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL,
    category_id INT NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    sku VARCHAR(50) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    INDEX idx_name (name),
    INDEX idx_category (category_id),
    INDEX idx_sku (sku),
    INDEX idx_stock (stock)
);
```

**Columns:**
- `id` - Primary key, auto-increment
- `name` - Product name
- `category_id` - Foreign key to categories table
- `description` - Product description
- `price` - Product price (10 digits, 2 decimal places)
- `stock` - Current stock quantity
- `sku` - Stock Keeping Unit (unique)
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

#### 11.2.4 Audit Log Table
```sql
CREATE TABLE audit_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(50) NOT NULL,
    table_name VARCHAR(50),
    record_id INT,
    old_value TEXT,
    new_value TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_table (table_name),
    INDEX idx_created (created_at)
);
```

### 11.3 Relationships

#### 11.3.1 User to Audit Log
- **Type:** One-to-Many
- **Description:** One user can perform multiple audit-logged actions
- **Cascade:** SET NULL (if user deleted, logs remain)

#### 11.3.2 Category to Product
- **Type:** One-to-Many
- **Description:** One category contains multiple products
- **Cascade:** RESTRICT (cannot delete category with products)

### 11.4 Indexes and Performance

#### 11.4.1 Indexes
- **Users:** username, email, role for frequent lookups
- **Categories:** name for category filtering
- **Products:** name, category_id, sku, stock for various queries
- **Audit Logs:** user_id, action, created_at for filtering and sorting

#### 11.4.2 Query Optimization
```sql
-- Good: Uses index
SELECT * FROM products WHERE category_id = 1;

-- Good: Uses index
SELECT * FROM users WHERE username = 'john_doe';

-- Consider: May need index or optimization
SELECT * FROM products WHERE price > 100 AND stock > 0;
```

### 11.5 Data Integrity Constraints

#### 11.5.1 Primary Keys
- All tables have primary key `id` as auto-increment integer
- Ensures uniqueness and enables efficient lookups

#### 11.5.2 Unique Constraints
- `users.username` - Username must be unique
- `users.email` - Email must be unique
- `categories.name` - Category name must be unique
- `products.sku` - SKU must be unique

#### 11.5.3 Foreign Keys
- `products.category_id` references `categories.id`
- `audit_logs.user_id` references `users.id`
- Enforces referential integrity

#### 11.5.4 Check Constraints
- Product price must be positive
- Stock quantity must be non-negative
- User role must be one of: admin, manager, staff

### 11.6 Database Maintenance

#### 11.6.1 Backup Strategy
```bash
# Daily backup
mysqldump -u root -p aoop_db > backup_$(date +%Y%m%d).sql

# Weekly compressed backup
mysqldump -u root -p aoop_db | gzip > backup_$(date +%Y%m%d).sql.gz
```

#### 11.6.2 Optimization
```sql
-- Optimize tables
OPTIMIZE TABLE users, categories, products, audit_logs;

-- Analyze table statistics
ANALYZE TABLE products;

-- Check table integrity
CHECK TABLE products;
```

#### 11.6.3 Data Archival
- Archive old audit logs (>1 year)
- Archive inactive products
- Maintain separate archive database for historical data

---

## 12. SECURITY CONSIDERATIONS

### 12.1 Authentication Security

#### 12.1.1 Password Management
- **Hashing Algorithm:** Argon2 or bcrypt
- **Minimum Length:** 12 characters
- **Complexity:** Uppercase, lowercase, numbers, special characters
- **Storage:** Never store plain text passwords

**Implementation:**
```php
$hash = password_hash($password, PASSWORD_ARGON2ID);
// Verify during login
$verified = password_verify($input, $hash);
```

#### 12.1.2 Session Management
- **Session Timeout:** 30 minutes of inactivity
- **Secure Cookies:** httpOnly, secure, sameSite flags
- **Session Regeneration:** Regenerate on login/privilege elevation
- **CSRF Tokens:** Include in all state-changing requests

**Configuration:**
```php
session_set_cookie_params([
    'httponly' => true,
    'secure' => true,
    'samesite' => 'Strict'
]);
```

#### 12.1.3 Multi-Factor Authentication (MFA)
- **Recommended:** TOTP (Time-Based One-Time Password)
- **Fallback:** Email verification codes
- **Recovery Codes:** Store encrypted backup codes

### 12.2 Authorization and Access Control

#### 12.2.1 Role-Based Access Control (RBAC)
- **Admin:** Full system access, user management
- **Manager:** Inventory management, report viewing
- **Staff:** Limited inventory viewing, stock updates

#### 12.2.2 Permission Verification
```php
// Check role-based access
if (!$user->hasRole('admin')) {
    http_response_code(403);
    die('Access Denied');
}

// Check permission-based access
if (!$user->hasPermission('delete_product')) {
    http_response_code(403);
    die('Insufficient Permissions');
}
```

#### 12.2.3 Resource-Level Authorization
- Verify user owns/can access specific resource
- Prevent direct object reference vulnerabilities
- Implement data isolation per user/tenant

### 12.3 Input Validation and Sanitization

#### 12.3.1 Validation Rules
- **Email:** Valid email format
- **Integers:** Must be numeric, within range
- **Strings:** Length limits, allowed characters
- **Files:** Type, size, extension verification

**Implementation:**
```php
function validateEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return true;
}

function validatePrice($price) {
    return is_numeric($price) && $price > 0 && $price < 999999.99;
}
```

#### 12.3.2 Sanitization Methods
- **HTML Escaping:** htmlspecialchars() for HTML context
- **URL Encoding:** urlencode() for URL context
- **String Trimming:** Remove leading/trailing whitespace
- **Type Casting:** Cast to expected type

**Implementation:**
```php
$safe_input = htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8');
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
```

### 12.4 SQL Injection Prevention

#### 12.4.1 Prepared Statements
- **Always use** prepared statements with parameterized queries
- **Never concatenate** user input into SQL queries
- **Use PDO** with proper binding

**Secure Example:**
```php
$stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? AND role = ?');
$stmt->execute([$username, $role]);
```

**Insecure Example (AVOID):**
```php
// VULNERABLE - Do not use!
$query = "SELECT * FROM users WHERE username = '$username'";
```

#### 12.4.2 Query Parameterization
```php
// Named parameters
$stmt = $pdo->prepare('INSERT INTO products (name, price) VALUES (:name, :price)');
$stmt->execute(['name' => $name, 'price' => $price]);

// Positional parameters
$stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
$stmt->execute([$id]);
```

### 12.5 Cross-Site Scripting (XSS) Prevention

#### 12.5.1 Output Encoding
- **HTML Context:** htmlspecialchars()
- **JavaScript Context:** json_encode() with JSON_HEX flags
- **URL Context:** urlencode()
- **CSS Context:** Avoid user input

**Implementation:**
```php
// In HTML template
<div><?= htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8') ?></div>

// In JavaScript
<script>
var user = <?= json_encode($user->name, JSON_HEX_TAG | JSON_HEX_AMP) ?>;
</script>
```

#### 12.5.2 Content Security Policy (CSP)
```php
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'");
```

### 12.6 Cross-Site Request Forgery (CSRF) Prevention

#### 12.6.1 Token Generation and Validation
```php
// Generate token
$token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $token;

// Include in form
<input type="hidden" name="csrf_token" value="<?= $token ?>">

// Validate on submission
if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die('CSRF token validation failed');
}
```

#### 12.6.2 SameSite Cookie Attribute
```php
session_set_cookie_params(['samesite' => 'Strict']);
```

### 12.7 Sensitive Data Protection

#### 12.7.1 Data Classification
- **Public:** General product information
- **Internal:** User profiles, pricing strategies
- **Confidential:** Passwords, payment information
- **Restricted:** Admin credentials, system configuration

#### 12.7.2 Encryption
- **In Transit:** HTTPS/TLS 1.2+
- **At Rest:** Encrypt sensitive database columns
- **Key Management:** Store keys securely, separate from code

```php
// Encrypt sensitive data
function encrypt($data, $key) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}
```

#### 12.7.3 Logging
- **Log:** Failed login attempts, privilege changes, data modifications
- **Don't Log:** Passwords, credit cards, API keys
- **Retention:** Keep logs for minimum 90 days

### 12.8 Error Handling and Information Disclosure

#### 12.8.1 Error Messages
- **Users:** Generic error messages ("An error occurred")
- **Logs:** Detailed error messages for debugging
- **Debug Mode:** Only enabled in development environment

**Implementation:**
```php
try {
    // Database operation
} catch (PDOException $e) {
    // Log detailed error
    error_log($e->getMessage());
    
    // Show generic message to user
    die('An error occurred. Please try again later.');
}
```

#### 12.8.2 Exception Handling
- Use specific exception types
- Handle exceptions appropriately
- Don't expose stack traces to users

### 12.9 File Upload Security

#### 12.9.1 Validation
- **File Type:** Verify MIME type (not just extension)
- **File Size:** Enforce maximum size limit
- **Filename:** Sanitize and regenerate
- **Location:** Store outside web root

**Implementation:**
```php
$maxSize = 5 * 1024 * 1024; // 5MB
$allowedTypes = ['image/jpeg', 'image/png'];

if ($_FILES['image']['size'] > $maxSize) {
    die('File too large');
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $_FILES['image']['tmp_name']);

if (!in_array($mimeType, $allowedTypes)) {
    die('Invalid file type');
}

// Rename file
$newName = bin2hex(random_bytes(16)) . '.jpg';
move_uploaded_file($_FILES['image']['tmp_name'], '/uploads/' . $newName);
```

#### 12.9.2 File Execution Prevention
- Disable script execution in upload directories
- Use .htaccess or web server configuration

**.htaccess:**
```apache
<FilesMatch "\.(php|phtml|php3|php4|php5|phps|pht|phar|shtml|pgif|spl|pjpeg|sybase|dynamide)$">
    Deny from all
</FilesMatch>
```

### 12.10 Security Headers

#### 12.10.1 HTTP Security Headers
```php
// Prevent clickjacking
header('X-Frame-Options: SAMEORIGIN');

// Prevent MIME type sniffing
header('X-Content-Type-Options: nosniff');

// Enable XSS filter
header('X-XSS-Protection: 1; mode=block');

// Strict Transport Security
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');

// Referrer Policy
header('Referrer-Policy: strict-origin-when-cross-origin');

// Permissions Policy
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
```

### 12.11 Dependency Security

#### 12.11.1 Package Management
- Keep dependencies updated
- Use `composer update` regularly
- Monitor security advisories

```bash
# Check for vulnerabilities
composer audit

# Update packages
composer update
```

#### 12.11.2 Vulnerability Scanning
- Use GitHub Security Advisories
- Enable Dependabot alerts
- Scan code with SonarQube or similar tools

### 12.12 Security Testing Checklist

- [ ] SQL Injection testing
- [ ] XSS attack testing
- [ ] CSRF vulnerability testing
- [ ] Authentication bypass testing
- [ ] Authorization testing
- [ ] Sensitive data exposure testing
- [ ] File upload vulnerability testing
- [ ] Session management testing
- [ ] Password policy enforcement testing
- [ ] Rate limiting testing
- [ ] Encryption verification testing
- [ ] Error handling verification

---

## 13. CONCLUSION AND FUTURE ENHANCEMENTS

### 13.1 Project Summary

This PHP Inventory Management System successfully demonstrates the application of advanced Object-Oriented Programming principles and software engineering best practices. The project implements a complete MVC architecture with proper separation of concerns, enabling maintainability, scalability, and testability.

**Key Achievements:**
1. **Architecture Excellence**
   - Clean MVC implementation with clear separation of concerns
   - Design patterns effectively applied throughout codebase
   - Scalable structure for future feature additions

2. **Security Implementation**
   - Comprehensive input validation and sanitization
   - Protection against OWASP Top 10 vulnerabilities
   - Secure authentication and authorization mechanisms

3. **Quality Assurance**
   - Comprehensive unit and integration test suite
   - 85%+ code coverage
   - Adherence to PSR-12 coding standards

4. **Documentation**
   - Complete system and API documentation
   - User manual for end-users
   - Developer guide for future maintainers

5. **Database Design**
   - Normalized schema with referential integrity
   - Optimized indexes for performance
   - Proper audit logging implementation

### 13.2 Lessons Learned

#### 13.2.1 Technical Insights
- Importance of proper database design and normalization
- Value of automated testing in preventing regressions
- Security must be considered throughout development, not as an afterthought
- Clear code documentation reduces maintenance burden

#### 13.2.2 Process Insights
- Iterative development with regular testing improves quality
- Code review process catches issues before production
- Technical debt accumulates quickly without constant attention
- Communication within team is essential for successful delivery

#### 13.2.3 Best Practices Reinforced
- Separation of concerns improves code quality
- Design patterns provide proven solutions to common problems
- Security by design is more effective than adding later
- Comprehensive documentation aids future maintenance

### 13.3 Future Enhancements

#### 13.3.1 Short-Term Enhancements (1-3 months)

1. **Advanced Reporting**
   - Custom report builder with drag-and-drop interface
   - Scheduled report generation and email delivery
   - Dashboard widgets for real-time metrics
   - Export to multiple formats (Excel, PDF, JSON)

2. **Inventory Optimization**
   - Automatic reorder point calculation
   - Supplier integration for auto-ordering
   - Barcode/QR code scanning
   - Inventory forecasting using historical data

3. **User Interface Improvements**
   - Mobile-responsive design optimization
   - Dark mode support
   - Advanced filtering and search
   - Drag-and-drop product management

4. **Performance Optimization**
   - Database query caching (Redis)
   - Page caching for static content
   - Asset minification and compression
   - CDN integration for static assets

#### 13.3.2 Medium-Term Enhancements (3-6 months)

1. **Multi-Tenancy Support**
   - Multiple company/warehouse support
   - Separate data isolation per tenant
   - Unified dashboard for enterprise users
   - Tenant-specific customization

2. **Advanced Analytics**
   - Machine learning for demand forecasting
   - Trend analysis and pattern detection
   - Anomaly detection for unusual inventory movements
   - Predictive analytics for stock optimization

3. **Integration Capabilities**
   - REST API for third-party integrations
   - Webhook support for event notifications
   - Integration with accounting software (QuickBooks, Xero)
   - E-commerce platform integration (WooCommerce, Shopify)

4. **Mobile Application**
   - Native mobile app (iOS/Android)
   - Offline sync capabilities
   - Mobile-specific features (barcode scanning, photos)
   - Push notifications for alerts

#### 13.3.3 Long-Term Enhancements (6-12 months)

1. **Enterprise Features**
   - Multi-location inventory management
   - Advanced workflow and approval processes
   - Comprehensive audit trail with time-travel
   - Role-based multi-level approval system

2. **AI and Machine Learning**
   - Intelligent inventory recommendations
   - Automated anomaly detection
   - Natural language search
   - Predictive maintenance alerts

3. **Scalability Enhancements**
   - Microservices architecture migration
   - Distributed system implementation
   - High-availability setup
   - Auto-scaling capabilities

4. **Advanced Compliance**
   - GDPR compliance features
   - Data retention policies
   - Encryption at rest and in transit
   - Compliance reporting and audit trails

### 13.4 Recommendations for Future Development

#### 13.4.1 Code Quality
- Implement continuous integration/continuous deployment (CI/CD)
- Add automated code quality checking (SonarQube, CodeClimate)
- Increase test coverage to 90%+
- Implement code review process with peer review requirements

#### 13.4.2 Infrastructure
- Migrate to containerized deployment (Docker)
- Implement infrastructure as code (Terraform, Ansible)
- Set up monitoring and alerting (Prometheus, Grafana)
- Implement log aggregation (ELK stack)

#### 13.4.3 Team Development
- Establish coding standards and best practices documentation
- Implement knowledge sharing sessions
- Create onboarding documentation for new developers
- Establish code review guidelines and processes

#### 13.4.4 User Experience
- Conduct user testing and gather feedback
- Implement A/B testing for UI improvements
- Regular usability audits
- User training and support resources

### 13.5 Maintenance Plan

#### 13.5.1 Regular Maintenance Tasks
- **Daily:** Monitor system performance, check error logs
- **Weekly:** Database maintenance, backup verification
- **Monthly:** Security updates, dependency updates
- **Quarterly:** Performance optimization, capacity planning
- **Annually:** Major version upgrades, architecture review

#### 13.5.2 Monitoring and Alerting
- Set up application performance monitoring (APM)
- Database query performance monitoring
- User activity monitoring
- Security event logging and monitoring

#### 13.5.3 Support and Documentation
- Maintain up-to-date API documentation
- Regular documentation reviews and updates
- Support ticket tracking and resolution
- FAQ and knowledge base maintenance

---

## 14. REFERENCES AND APPENDICES

### 14.1 References

#### 14.1.1 Documentation and Specifications
1. **PSR-12: Extended Coding Style Guide**
   - https://www.php-fig.org/psr/psr-12/

2. **PHP Official Documentation**
   - https://www.php.net/docs.php

3. **MySQL Documentation**
   - https://dev.mysql.com/doc/

4. **OWASP Top 10 - 2021**
   - https://owasp.org/Top10/

5. **Web Application Security Testing Guide**
   - https://owasp.org/www-project-web-security-testing-guide/

#### 14.1.2 Design Patterns and Architecture
1. **Design Patterns: Elements of Reusable Object-Oriented Software**
   - Gamma, Helm, Johnson, Vlissides (Gang of Four)

2. **Clean Code: A Handbook of Agile Software Craftsmanship**
   - Robert C. Martin

3. **Refactoring: Improving the Design of Existing Code**
   - Martin Fowler

4. **MVC Architecture Pattern**
   - https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller

#### 14.1.3 Testing and Quality
1. **PHPUnit Documentation**
   - https://phpunit.de/documentation.html

2. **Test-Driven Development: By Example**
   - Kent Beck

3. **Working Effectively with Legacy Code**
   - Michael Feathers

#### 14.1.4 Security Resources
1. **OWASP Top 10 Web Application Security Risks**
   - https://owasp.org/Top10/

2. **CWE Top 25 Most Dangerous Software Weaknesses**
   - https://cwe.mitre.org/top25/

3. **PHP Security: Best Practices**
   - https://www.php.net/manual/en/security.php

### 14.2 Appendices

#### Appendix A: Installation Scripts

**Windows Installation (setup.bat):**
[Include batch script for Windows setup]

**Linux/Mac Installation (setup.sh):**
[Include bash script for Linux/Mac setup]

#### Appendix B: Configuration Files

**Database Configuration (config/database.php):**
[Sample configuration with placeholders]

**Application Configuration (config/setup.php):**
[Sample application settings]

#### Appendix C: Database Schema

**Complete SQL Schema (database.sql):**
[Include complete database.sql file content]

#### Appendix D: API Documentation

**Endpoint Reference:**
[List of all API endpoints if REST API exists]

**Request/Response Examples:**
[Sample API requests and responses]

#### Appendix E: Troubleshooting Guide

**Common Issues and Solutions:**
[Detailed troubleshooting steps for common problems]

#### Appendix F: Developer Setup Guide

**Development Environment Setup:**
[Steps to set up development environment]

**Running Tests:**
[Commands to run test suites]

**Code Style Verification:**
[How to check code style compliance]

#### Appendix G: Change Log

**Version 1.0.0 (Final Release)**
- Initial release
- [List all features and fixes]

#### Appendix H: Class and Method Index

**Quick Reference:**
[Complete alphabetical listing of all classes and methods]

#### Appendix I: Glossary of Terms

| Term | Definition |
|------|-----------|
| **API** | Application Programming Interface |
| **CRUD** | Create, Read, Update, Delete operations |
| **CSRF** | Cross-Site Request Forgery |
| **MVC** | Model-View-Controller architectural pattern |
| **OOP** | Object-Oriented Programming |
| **PDO** | PHP Data Objects - database abstraction layer |
| **RBAC** | Role-Based Access Control |
| **XSS** | Cross-Site Scripting |
| **HTTPS** | Secure HTTP using TLS/SSL |
| **SQL Injection** | Injection attack on database queries |

---

## Document Information

**Document Title:** PHP Inventory Management System - Final Project Documentation
**Version:** 1.0.0
**Last Updated:** [Current Date]
**Status:** Final
**Document Owner:** [Team Lead Name]
**Distribution:** Project Team, Instructor, Institution Records

### Document Control

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0.0 | [Date] | [Author] | Initial document creation |
| | | | |

---

**End of Document**

