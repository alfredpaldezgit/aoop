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
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fas fa-box-open me-2"></i><strong>InventorySys</strong></a>
        </div>
    </nav>

    <main class="container mt-5">
        <div class="card border-0 shadow-lg">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-list-ul me-2"></i>Product Inventory</h4>
                <button class="btn btn-light fw-bold" data-bs-toggle="modal" data-bs-target="#productModal" id="addProductBtn">
                    <i class="fas fa-plus-circle me-2"></i>Add New Product
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="product-list">
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No products found. Click 'Add New Product' to get started!</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $product): ?>
                                    <tr id="product-<?php echo $product['id']; ?>">
                                        <td><strong><?php echo $product['id']; ?></strong></td>
                                        <td class="name"><?php echo htmlspecialchars($product['name']); ?></td>
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
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="productQuantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="productQuantity" name="quantity" min="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="productPrice" class="form-label">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="productPrice" name="price" min="0" step="0.01" required>
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
      // Enable Bootstrap tooltips
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
      })
    </script>
</body>
</html>
