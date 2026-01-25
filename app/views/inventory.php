<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .card-header { background-color: #0d6efd; color: white; }
        .table-hover tbody tr:hover { background-color: #e9ecef; }
        .align-middle { vertical-align: middle; }
        .sortable { cursor: pointer; user-select: none; }
        .sortable .fa-sort, .sortable .fa-sort-up, .sortable .fa-sort-down { margin-left: 5px; color: #ced4da; }
        .sortable:hover { background-color: #495057; }
        .badge { font-size: 0.9em; }
        
        /* Dark Mode Styles */
        body.dark-mode {
            background-color: #1a1a1a;
            color: #e0e0e0;
        }
        body.dark-mode .card {
            background-color: #2d2d2d;
            border-color: #444;
            color: #e0e0e0;
        }
        body.dark-mode .table {
            color: #e0e0e0;
            border-color: #444;
        }
        body.dark-mode .table-hover tbody tr:hover {
            background-color: #3a3a3a;
        }
        body.dark-mode .table-dark {
            background-color: #1e1e1e;
        }
        body.dark-mode .form-control,
        body.dark-mode .form-select {
            background-color: #3a3a3a;
            color: #e0e0e0;
            border-color: #555;
        }
        body.dark-mode .form-control:focus,
        body.dark-mode .form-select:focus {
            background-color: #3a3a3a;
            color: #e0e0e0;
            border-color: #0d6efd;
        }
        body.dark-mode .navbar-light {
            background-color: #2d2d2d !important;
            border-bottom: 1px solid #444;
        }
        body.dark-mode .container-fluid {
            background-color: #2d2d2d;
        }
        body.dark-mode .modal-content {
            background-color: #2d2d2d;
            color: #e0e0e0;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><i class="fas fa-box-open me-2"></i><strong>InventorySys</strong></a>
            <div class="navbar-nav ms-auto">
                <label class="nav-link form-check form-switch me-2">
                    <input class="form-check-input" type="checkbox" id="darkModeToggle" style="cursor: pointer; transform: scale(1.5);">
                    <i class="fas fa-moon ms-2"></i>
                </label>
                <a href="index.php?action=reports" class="nav-link btn btn-light btn-sm me-2">
                    <i class="fas fa-file-alt me-1"></i>Reports
                </a>
                <a href="index.php?action=dashboard" class="nav-link btn btn-light btn-sm me-2">
                    <i class="fas fa-chart-pie me-1"></i>Dashboard
                </a>
            </div>
        </div>
    </nav>

    <main class="container mt-5">
        <div class="card border-0 shadow-lg">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-list-ul me-2"></i>Product Inventory</h4>
                <div>
                    <a href="index.php?action=export-csv" class="btn btn-success fw-bold me-2">
                        <i class="fas fa-download me-2"></i>Export CSV
                    </a>
                    <button class="btn btn-light fw-bold" data-bs-toggle="modal" data-bs-target="#productModal" id="addProductBtn">
                        <i class="fas fa-plus-circle me-2"></i>Add New Product
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3 gx-2">
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search by Product Name...">
                    </div>
                    <div class="col-md-3">
                        <select id="categoryFilter" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th class="sortable" data-sort="name" data-order="desc">Product Name <i class="fas fa-sort"></i></th>
                                <th class="sortable" data-sort="category" data-order="desc">Category <i class="fas fa-sort"></i></th>
                                <th class="sortable" data-sort="quantity" data-order="desc">Quantity <i class="fas fa-sort"></i></th>
                                <th class="sortable" data-sort="price" data-order="desc">Price <i class="fas fa-sort"></i></th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="product-list">
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No products found. Click 'Add New Product' to get started!</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $product): ?>
                                    <tr id="product-<?php echo $product['id']; ?>" data-category-id="<?php echo $product['category_id']; ?>">
                                        <td><strong><?php echo $product['id']; ?></strong></td>
                                        <td class="name"><?php echo htmlspecialchars($product['name']); ?></td>
                                        <td class="category">
                                            <span class="badge bg-secondary"><?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?></span>
                                        </td>
                                        <td class="quantity"><?php echo htmlspecialchars($product['quantity']); ?></td>
                                        <td class="price">$<?php echo number_format($product['price'], 2); ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info text-white edit-btn" data-id="<?php echo $product['id']; ?>" data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-btn" data-id="<?php echo $product['id']; ?>" data-bs-toggle="tooltip" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Add/Edit Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="productForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="productId" name="id">
                        <div class="mb-3">
                            <label for="productName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="name" required>
                            <div class="invalid-feedback">Product name cannot be empty.</div>
                        </div>
                        <div class="mb-3">
                            <label for="productCategory" class="form-label">Category</label>
                            <select class="form-select" id="productCategory" name="category_id">
                                <option value="">Select a category</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="productQuantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="productQuantity" name="quantity" min="0" required>
                                <div class="invalid-feedback">Quantity must be a whole number (0 or greater).</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="productPrice" class="form-label">Price</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="productPrice" name="price" min="0" step="0.01" required>
                                    <div class="invalid-feedback">Price must be a valid number (0.00 or greater).</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    
    <!-- Custom JS for AJAX operations will be loaded here -->
    <script src="public/js/app.js"></script>

    <script>
      // Dark Mode Toggle
      document.addEventListener('DOMContentLoaded', function() {
          const darkModeToggle = document.getElementById('darkModeToggle');
          const isDarkMode = localStorage.getItem('darkMode') === 'enabled';
          
          if (isDarkMode) {
              document.body.classList.add('dark-mode');
              darkModeToggle.checked = true;
          }

          darkModeToggle.addEventListener('change', function() {
              if (this.checked) {
                  document.body.classList.add('dark-mode');
                  localStorage.setItem('darkMode', 'enabled');
              } else {
                  document.body.classList.remove('dark-mode');
                  localStorage.setItem('darkMode', 'disabled');
              }
          });
      });

      // Enable Bootstrap tooltips
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
      })
    </script>
</body>
</html>
