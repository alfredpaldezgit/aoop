$(document).ready(function() {

    const productModal = new bootstrap.Modal(document.getElementById('productModal'));
    const productForm = $('#productForm');
    const productList = $('#product-list');
    const LOW_STOCK_THRESHOLD = 10;
    let searchTimeout;

    /**
     * Initialize dark mode toggle if exists
     */
    function initDarkMode() {
        const darkModeToggle = $('#darkModeToggle');
        if (darkModeToggle.length) {
            const isDarkMode = localStorage.getItem('darkMode') === 'true';
            if (isDarkMode) {
                enableDarkMode();
                darkModeToggle.checked = true;
            }
            darkModeToggle.on('change', function() {
                if (this.checked) {
                    enableDarkMode();
                } else {
                    disableDarkMode();
                }
            });
        }
    }

    function enableDarkMode() {
        $('body').addClass('dark-mode');
        localStorage.setItem('darkMode', 'true');
    }

    function disableDarkMode() {
        $('body').removeClass('dark-mode');
        localStorage.setItem('darkMode', 'false');
    }

    /**
     * Debounce function for search
     */
    function debounce(func, delay) {
        return function(...args) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    /**
     * Applies a warning style to a row if its quantity is below the threshold.
     * @param {jQuery} row - The jQuery object for the table row.
     */
    function checkLowStock(row) {
        const quantity = parseInt(row.find('.quantity').text());
        if (!isNaN(quantity) && quantity < LOW_STOCK_THRESHOLD) {
            row.addClass('table-warning');
        } else {
            row.removeClass('table-warning');
        }
    }

    /**
     * Creates the HTML for a table row from a product object.
     * @param {object} product - The product data.
     * @returns {string} The HTML string for the new table row.
     */
    function createProductRow(product) {
        const price = parseFloat(product.price).toFixed(2);
        const categoryName = product.category_name ? escapeHtml(product.category_name) : 'N/A';
        return `
            <tr id="product-${product.id}" data-category-id="${product.category_id}" style="display:none;">
                <td><strong>${product.id}</strong></td>
                <td class="name">${escapeHtml(product.name)}</td>
                <td class="category"><span class="badge bg-secondary">${categoryName}</span></td>
                <td class="quantity">${escapeHtml(product.quantity)}</td>
                <td class="price">$${price}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-info text-white edit-btn" data-id="${product.id}" data-bs-toggle="tooltip" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="${product.id}" data-bs-toggle="tooltip" title="Delete">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        `;
    }
    
    function escapeHtml(text) {
        if (text === null || text === undefined) return "";
        return text.toString().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    function showEmptyMessageIfNeeded() {
        $('#no-results-row').remove();
        if (productList.find('tr:visible').length === 0) {
            productList.html('<tr><td colspan="6" class="text-center text-muted">No products found. Click \'Add New Product\' to get started!</td></tr>');
        }
    }

    function validateForm() {
        let isValid = true;
        $('.form-control, .form-select').removeClass('is-invalid');
        if ($('#productName').val().trim() === '') {
            $('#productName').addClass('is-invalid');
            isValid = false;
        }
        if ($('#productCategory').val() === '') {
            $('#productCategory').addClass('is-invalid');
            isValid = false;
        }
        const quantity = $('#productQuantity').val();
        if (quantity === '' || !/^\d+$/.test(quantity) || parseInt(quantity) < 0) {
            $('#productQuantity').addClass('is-invalid');
            isValid = false;
        }
        const price = $('#productPrice').val();
        if (price === '' || isNaN(parseFloat(price)) || parseFloat(price) < 0) {
            $('#productPrice').addClass('is-invalid');
            isValid = false;
        }
        return isValid;
    }

    $('#addProductBtn').on('click', function() {
        productForm.trigger('reset');
        $('#productId').val('');
        $('#productModalLabel').text('Add New Product');
        $('#productCategory').val('');
        $('.form-control, .form-select').removeClass('is-invalid');
    });

    productList.on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.get(`index.php?action=get&id=${id}`, function(data) {
            productForm.trigger('reset');
            $('.form-control, .form-select').removeClass('is-invalid');
            $('#productModalLabel').text('Edit Product');
            $('#productId').val(data.id);
            $('#productName').val(data.name);
            $('#productCategory').val(data.category_id);
            $('#productQuantity').val(data.quantity);
            $('#productPrice').val(data.price);
            productModal.show();
        }).fail(() => alert('Error: Could not retrieve product data.'));
    });

    productForm.on('submit', function(e) {
        e.preventDefault();
        if (!validateForm()) return;

        const id = $('#productId').val();
        const url = id ? `index.php?action=update` : `index.php?action=create`;
        const formData = {
            id: id,
            name: $('#productName').val(),
            quantity: $('#productQuantity').val(),
            price: $('#productPrice').val(),
            category_id: $('#productCategory').val()
        };

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            success: function(product) {
                productModal.hide();
                const price = parseFloat(product.price).toFixed(2);
                const categoryName = product.category_name ? escapeHtml(product.category_name) : 'N/A';
                let row;

                if (id) { // --- Handle UPDATE ---
                    row = $(`#product-${product.id}`);
                    row.data('category-id', product.category_id);
                    row.find('.name').text(escapeHtml(product.name));
                    row.find('.category').html(`<span class="badge bg-secondary">${categoryName}</span>`);
                    row.find('.quantity').text(escapeHtml(product.quantity));
                    row.find('.price').text(`$${price}`);
                    row.css('background-color', '#dff0d8').animate({backgroundColor: 'transparent'}, 1500);
                } else { // --- Handle CREATE ---
                    if (productList.find('td[colspan]').length > 0) {
                        productList.empty();
                    }
                    const newRow = createProductRow(product);
                    productList.prepend(newRow);
                    row = $(`#product-${product.id}`);
                    row.fadeIn(500);
                }
                
                checkLowStock(row);
                applyFilters();
            },
            error: (xhr) => alert(`Operation failed: ${xhr.responseJSON?.message || 'An unknown error occurred.'}`)
        });
    });

    productList.on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');
        if (confirm(`Are you sure you want to delete "${row.find('.name').text()}"?`)) {
            $.get(`index.php?action=delete&id=${id}`, () => {
                row.fadeOut(500, function() {
                    $(this).remove();
                    showEmptyMessageIfNeeded();
                });
            }).fail((xhr) => alert(`Error: ${xhr.responseJSON?.message || 'Could not delete product.'}`));
        }
    });

    function getCellValue(row, columnClass) {
        const cell = $(row).find(`td.${columnClass}`);
        let value = cell.text().trim();
        if (columnClass === 'price') value = value.replace('$', '');
        const numValue = parseFloat(value);
        return !isNaN(numValue) ? numValue : value;
    }

    $('thead').on('click', '.sortable', function() {
        const column = $(this).data('sort');
        const order = $(this).data('order');
        const rows = productList.find('tr').get();
        if (rows.length <= 1 && $(rows).find('td[colspan]').length > 0) return;

        rows.sort((a, b) => {
            let valA = getCellValue(a, column);
            let valB = getCellValue(b, column);
            if (order === 'desc') [valA, valB] = [valB, valA];
            if (typeof valA === 'number' && typeof valB === 'number') return valA - valB;
            return valA.toString().localeCompare(valB.toString());
        });

        productList.append(rows);
        $('.sortable i').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');
        $(this).data('order', order === 'desc' ? 'asc' : 'desc');
        $(this).find('i').removeClass('fa-sort').addClass(order === 'desc' ? 'fa-sort-down' : 'fa-sort-up');
    });

    function applyFilters() {
        const searchTerm = $('#searchInput').val().toLowerCase();
        const categoryId = $('#categoryFilter').val();
        let visibleRows = 0;

        $('#no-results-row').remove();

        productList.find('tr').each(function() {
            const row = $(this);
            if (row.find('td[colspan]').length > 0) return;

            const nameMatch = row.find('.name').text().toLowerCase().includes(searchTerm);
            const categoryMatch = (categoryId === "" || row.data('category-id') == categoryId);

            if (nameMatch && categoryMatch) {
                row.show();
                visibleRows++;
            } else {
                row.hide();
            }
        });

        if (visibleRows === 0 && productList.find('td[colspan]').length === 0) {
            productList.append('<tr id="no-results-row"><td colspan="6" class="text-center text-muted">No products match your filters.</td></tr>');
        }
    }

    // Apply debounced search with 300ms delay
    const debouncedSearch = debounce(applyFilters, 300);
    $('#searchInput').on('keyup', debouncedSearch);
    $('#categoryFilter').on('change', applyFilters);

    // Initial check for low stock on page load
    productList.find('tr').each(function() {
        if ($(this).find('td[colspan]').length === 0) {
            checkLowStock($(this));
        }
    });

    // Initialize dark mode
    initDarkMode();
});
