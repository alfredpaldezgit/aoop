# PHP Inventory Management System

A simple, web-based inventory management system built with PHP, MySQL, and Bootstrap, following the Model-View-Controller (MVC) architectural pattern. This project provides basic CRUD (Create, Read, Update, Delete) functionality for managing a product inventory.

## Features

-   **View Products:** Displays a list of all products in the inventory with their name, quantity, and price.
-   **Add Products:** A modal form allows for the seamless addition of new products.
-   **Edit Products:** Update the information for existing products in a modal without a page refresh.
-   **Delete Products:** Remove products from the inventory with a confirmation step to prevent accidents.
-   **Responsive UI:** A clean and modern user interface built with Bootstrap 5 that works on all screen sizes.
-   **AJAX-Powered:** All CRUD operations are handled asynchronously using JavaScript (jQuery) for a smooth, single-page application feel.

## Technology Stack

-   **Backend:** PHP 8+
-   **Database:** MySQL
-   **Frontend:** HTML5, CSS3, JavaScript (with jQuery)
-   **Frameworks/Libraries:** Bootstrap 5
-   **Server:** Apache (via XAMPP)
-   **Architecture:** Model-View-Controller (MVC)

## Project Structure

The project follows a standard MVC pattern to ensure a clean separation of concerns:

```
/
├── app/
│   ├── controllers/  # Contains InventoryController.php, which handles user input and business logic.
│   ├── models/       # Contains Product.php, which manages all database interactions.
│   └── views/        # Contains inventory.php, the main presentation file (the UI).
├── config/
│   ├── database.php  # Stores the database connection credentials.
│   └── setup.php     # A one-time script to create the database and table.
├── public/
│   └── js/           # Contains app.js for all client-side AJAX and DOM manipulation.
├── index.php         # The single entry point (Front Controller) that routes all requests.
└── README.md         # This documentation file.
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

### Step 3: Run the Database Setup Script (Crucial Step)

-   This is a **one-time action** to automatically create the database and the `products` table.
-   Open your web browser (like Chrome or Firefox) and go to the following URL:
    ```
    http://localhost/aoop/config/setup.php
    ```
-   A success message should appear on the page, confirming that the database and table were created. If you see an error, ensure MySQL is running in XAMPP.

### Step 4: Launch the Application

-   Once the database setup is complete, you can now use the application.
-   In your web browser, navigate to the project's root URL:
    ```
    http://localhost/aoop/
    ```

The inventory system will now be fully operational.
