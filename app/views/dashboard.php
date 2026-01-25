<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Inventory Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .stat-card {
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        .navbar { background: rgba(255,255,255,0.95) !important; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .navbar-brand { color: #667eea !important; font-weight: 700; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .dashboard-title { color: white; font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .table-responsive { border-radius: 10px; overflow: hidden; }
        .badge-warning { background-color: #ff9800 !important; }
        .badge-danger { background-color: #f44336 !important; }
        .badge-success { background-color: #4caf50 !important; }
    </style>
</head>
<body>

    <nav class="navbar navbar-light bg-light sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><i class="fas fa-chart-line me-2"></i>Dashboard</a>
            <div>
                <a href="index.php?action=reports" class="btn btn-sm btn-outline-info me-2">
                    <i class="fas fa-file-alt me-1"></i>Reports
                </a>
                <a href="index.php?action=index" class="btn btn-sm btn-outline-primary me-2">
                    <i class="fas fa-boxes me-1"></i>Inventory
                </a>
                <a href="index.php?action=dashboard" class="btn btn-sm btn-primary">
                    <i class="fas fa-home me-1"></i>Home
                </a>
            </div>
        </div>
    </nav>

    <main class="container-fluid py-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="dashboard-title mb-4"><i class="fas fa-chart-pie me-2"></i>Inventory Dashboard</h1>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4 g-3">
            <div class="col-md-4 col-lg-2">
                <div class="card stat-card bg-primary text-white">
                    <div class="card-body text-center">
                        <div class="stat-icon"><i class="fas fa-box"></i></div>
                        <h6 class="card-title mt-2">Total Products</h6>
                        <h3 class="fw-bold"><?php echo $stats['total_products']; ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-2">
                <div class="card stat-card bg-success text-white">
                    <div class="card-body text-center">
                        <div class="stat-icon"><i class="fas fa-coins"></i></div>
                        <h6 class="card-title mt-2">Total Value</h6>
                        <h3 class="fw-bold">$<?php echo number_format($stats['total_value'], 2); ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-2">
                <div class="card stat-card bg-warning text-white">
                    <div class="card-body text-center">
                        <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                        <h6 class="card-title mt-2">Low Stock</h6>
                        <h3 class="fw-bold"><?php echo $stats['low_stock_count']; ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-2">
                <div class="card stat-card bg-danger text-white">
                    <div class="card-body text-center">
                        <div class="stat-icon"><i class="fas fa-ban"></i></div>
                        <h6 class="card-title mt-2">Out of Stock</h6>
                        <h3 class="fw-bold"><?php echo $stats['out_of_stock']; ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-2">
                <div class="card stat-card bg-info text-white">
                    <div class="card-body text-center">
                        <div class="stat-icon"><i class="fas fa-layer-group"></i></div>
                        <h6 class="card-title mt-2">Total Quantity</h6>
                        <h3 class="fw-bold"><?php echo $stats['total_quantity']; ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-2">
                <div class="card stat-card bg-secondary text-white">
                    <div class="card-body text-center">
                        <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                        <h6 class="card-title mt-2">Avg Price</h6>
                        <h3 class="fw-bold">$<?php echo number_format($stats['avg_price'], 2); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Low Stock Items -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0"><i class="fas fa-exclamation me-2"></i>⚠️ Low Stock Alert</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($stats['low_stock_items'])): ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($stats['low_stock_items'] as $item): ?>
                                            <tr class="<?php echo $item['quantity'] == 0 ? 'table-danger' : 'table-warning'; ?>">
                                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                                <td>
                                                    <span class="badge <?php echo $item['quantity'] == 0 ? 'bg-danger' : 'bg-warning text-dark'; ?>">
                                                        <?php echo $item['quantity']; ?>
                                                    </span>
                                                </td>
                                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle me-2"></i>All products have sufficient stock!
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Top Products by Value -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-star me-2"></i>Top Products by Value</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Total Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($topProducts as $product): 
                                        $totalValue = $product['quantity'] * $product['price'];
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                                            <td><span class="badge bg-secondary"><?php echo $product['quantity']; ?></span></td>
                                            <td class="fw-bold">$<?php echo number_format($totalValue, 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Breakdown -->
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-sitemap me-2"></i>Inventory by Category</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Category</th>
                                        <th>Products</th>
                                        <th>Total Quantity</th>
                                        <th>Category Value</th>
                                        <th>% of Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $totalValue = $stats['total_value'];
                                    foreach ($categoryStats as $cat): 
                                        $percentage = $totalValue > 0 ? ($cat['category_value'] / $totalValue * 100) : 0;
                                    ?>
                                        <tr>
                                            <td><strong><?php echo htmlspecialchars($cat['name'] ?? 'Uncategorized'); ?></strong></td>
                                            <td><span class="badge bg-secondary"><?php echo $cat['product_count']; ?></span></td>
                                            <td><?php echo $cat['total_quantity'] ?? 0; ?></td>
                                            <td class="fw-bold">$<?php echo number_format($cat['category_value'] ?? 0, 2); ?></td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar" style="width: <?php echo $percentage; ?>%">
                                                        <?php echo number_format($percentage, 1); ?>%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
