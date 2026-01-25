<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Inventory Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .report-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }
        .report-icon {
            font-size: 2rem;
            color: #0d6efd;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><i class="fas fa-chart-line me-2"></i>Reports</a>
            <div class="navbar-nav ms-auto">
                <a href="index.php?action=dashboard" class="nav-link btn btn-light btn-sm me-2">
                    <i class="fas fa-home me-1"></i>Dashboard
                </a>
                <a href="index.php?action=index" class="nav-link btn btn-light btn-sm">
                    <i class="fas fa-boxes me-1"></i>Inventory
                </a>
            </div>
        </div>
    </nav>

    <main class="container mt-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="mb-4"><i class="fas fa-chart-pie me-2"></i>Inventory Reports</h1>
            </div>
        </div>

        <div class="row g-4">
            <!-- Inventory Report -->
            <div class="col-md-6 col-lg-4">
                <div class="card report-card" onclick="generateReport('inventory')">
                    <div class="card-body text-center">
                        <div class="report-icon">
                            <i class="fas fa-list-alt"></i>
                        </div>
                        <h5 class="card-title">Full Inventory Report</h5>
                        <p class="card-text text-muted">Complete list of all products with details</p>
                        <button class="btn btn-primary btn-sm">View Report</button>
                    </div>
                </div>
            </div>

            <!-- Low Stock Report -->
            <div class="col-md-6 col-lg-4">
                <div class="card report-card" onclick="generateReport('low-stock')">
                    <div class="card-body text-center">
                        <div class="report-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h5 class="card-title">Low Stock Alert</h5>
                        <p class="card-text text-muted">Products below reorder threshold</p>
                        <button class="btn btn-warning btn-sm">View Report</button>
                    </div>
                </div>
            </div>

            <!-- Category Analysis -->
            <div class="col-md-6 col-lg-4">
                <div class="card report-card" onclick="generateReport('category-value')">
                    <div class="card-body text-center">
                        <div class="report-icon">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        <h5 class="card-title">Category Analysis</h5>
                        <p class="card-text text-muted">Inventory value by category</p>
                        <button class="btn btn-info btn-sm">View Report</button>
                    </div>
                </div>
            </div>

            <!-- ABC Analysis -->
            <div class="col-md-6 col-lg-4">
                <div class="card report-card" onclick="generateReport('abc-analysis')">
                    <div class="card-body text-center">
                        <div class="report-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h5 class="card-title">ABC Analysis</h5>
                        <p class="card-text text-muted">Pareto analysis of inventory items</p>
                        <button class="btn btn-success btn-sm">View Report</button>
                    </div>
                </div>
            </div>

            <!-- Reorder Report -->
            <div class="col-md-6 col-lg-4">
                <div class="card report-card" onclick="generateReport('reorder')">
                    <div class="card-body text-center">
                        <div class="report-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h5 class="card-title">Reorder Report</h5>
                        <p class="card-text text-muted">Items that need reordering</p>
                        <button class="btn btn-secondary btn-sm">View Report</button>
                    </div>
                </div>
            </div>

            <!-- Export CSV -->
            <div class="col-md-6 col-lg-4">
                <div class="card report-card" onclick="exportCsv()">
                    <div class="card-body text-center">
                        <div class="report-icon">
                            <i class="fas fa-download"></i>
                        </div>
                        <h5 class="card-title">Export to CSV</h5>
                        <p class="card-text text-muted">Download complete inventory as CSV</p>
                        <button class="btn btn-success btn-sm">Download</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="reportContent">
                    <p>Loading...</p>
                </div>
                <div class="modal-footer">
                    <a id="exportBtn" href="#" class="btn btn-primary" download>
                        <i class="fas fa-download me-1"></i>Export as HTML
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        const reportModal = new bootstrap.Modal(document.getElementById('reportModal'));

        function generateReport(type) {
            const reportContent = document.getElementById('reportContent');
            reportContent.innerHTML = '<p><i class="fas fa-spinner fa-spin"></i> Loading report...</p>';
            reportModal.show();

            $.ajax({
                url: 'index.php?action=report&type=' + type,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    displayReport(data, type);
                    document.getElementById('exportBtn').href = 'index.php?action=export-report&type=' + type;
                },
                error: function() {
                    reportContent.innerHTML = '<div class="alert alert-danger">Failed to load report</div>';
                }
            });
        }

        function displayReport(data, type) {
            const reportContent = document.getElementById('reportContent');
            let html = '';

            if (type === 'inventory') {
                html = generateInventoryHtml(data);
            } else if (type === 'low-stock') {
                html = generateTableHtml(data, ['name', 'quantity', 'price', 'total_value']);
            } else if (type === 'category-value') {
                html = generateTableHtml(data, ['name', 'product_count', 'total_quantity', 'total_value']);
            } else if (type === 'abc-analysis') {
                html = generateABCHtml(data);
            } else if (type === 'reorder') {
                html = generateTableHtml(data, ['name', 'quantity', 'price']);
            }

            reportContent.innerHTML = html;
        }

        function generateInventoryHtml(data) {
            const stats = data.stats || {};
            let html = '<div class="mb-3">';
            html += '<h6>Summary</h6>';
            html += '<p>Total Products: <strong>' + stats.total_products + '</strong></p>';
            html += '<p>Total Quantity: <strong>' + stats.total_quantity + '</strong></p>';
            html += '<p>Total Value: <strong>$' + (stats.total_value ? parseFloat(stats.total_value).toFixed(2) : '0.00') + '</strong></p>';
            html += '</div>';

            html += '<div class="table-responsive"><table class="table table-sm">';
            html += '<thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Qty</th><th>Price</th><th>Value</th></tr></thead><tbody>';
            if (data.products) {
                data.products.forEach(p => {
                    html += '<tr><td>' + p.id + '</td><td>' + p.name + '</td><td>' + (p.category || 'N/A') + '</td>';
                    html += '<td>' + p.quantity + '</td><td>$' + parseFloat(p.price).toFixed(2) + '</td>';
                    html += '<td>$' + parseFloat(p.total_value).toFixed(2) + '</td></tr>';
                });
            }
            html += '</tbody></table></div>';
            return html;
        }

        function generateTableHtml(data, columns) {
            let html = '<div class="table-responsive"><table class="table table-sm">';
            html += '<thead><tr>';
            columns.forEach(col => html += '<th>' + col.replace(/_/g, ' ').toUpperCase() + '</th>');
            html += '</tr></thead><tbody>';
            data.forEach(row => {
                html += '<tr>';
                columns.forEach(col => {
                    let value = row[col] || '-';
                    if (col.includes('price') || col.includes('value')) {
                        value = '$' + parseFloat(value).toFixed(2);
                    }
                    html += '<td>' + value + '</td>';
                });
                html += '</tr>';
            });
            html += '</tbody></table></div>';
            return html;
        }

        function generateABCHtml(data) {
            let html = '<h6>High Value Items (A)</h6>';
            html += '<p>Count: ' + (data.a_items ? data.a_items.length : 0) + '</p>';
            
            html += '<h6 class="mt-3">Medium Value Items (B)</h6>';
            html += '<p>Count: ' + (data.b_items ? data.b_items.length : 0) + '</p>';
            
            html += '<h6 class="mt-3">Low Value Items (C)</h6>';
            html += '<p>Count: ' + (data.c_items ? data.c_items.length : 0) + '</p>';
            
            html += '<p class="mt-3">Total Inventory Value: <strong>$' + parseFloat(data.total_value || 0).toFixed(2) + '</strong></p>';
            return html;
        }

        function exportCsv() {
            window.location.href = 'index.php?action=export-csv';
        }
    </script>
</body>
</html>
