$(document).ready(function() {

    const productModal = new bootstrap.Modal(document.getElementById('productModal'));
    const productForm = $('#productForm');
    const productList = $('#product-list');

    /**
     * Creates the HTML for a table row from a product object.
     * @param {object} product - The product data.
     * @returns {string} The HTML string for the new table row.
     */
    function createProductRow(product) {
        const price = parseFloat(product.price).toFixed(2);
        return `
            <tr id="product-${product.id}" style="display:none;">
                <td><strong>${product.id}</strong></td>
                <td class="name">${escapeHtml(product.name)}</td>
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
    
    /**
     * Escapes HTML special characters to prevent XSS attacks.
     * @param {string} text - The string to escape.
     * @returns {string} The escaped string.
     */
    function escapeHtml(text) {
        if (text === null || text === undefined) return "";
        return text.toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    /**
     * Checks if the product list is empty and shows a message if it is.
     */
    function showEmptyMessageIfNeeded() {
        if (productList.find('tr').length === 0) {
            productList.html('<tr><td colspan="5" class="text-center text-muted">No products found. Click \'Add New Product\' to get started!</td></tr>');
        }
    }

    // --- Event Handler to open modal for adding a product ---
    $('#addProductBtn').on('click', function() {
        productForm.trigger('reset');
        $('#productId').val('');
        $('#productModalLabel').text('Add New Product');
    });

    // --- Event Handler for clicking the 'Edit' button ---
    productList.on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.get(`index.php?action=get&id=${id}`, function(data) {
            $('#productModalLabel').text('Edit Product');
            $('#productId').val(data.id);
            $('#productName').val(data.name);
            $('#productQuantity').val(data.quantity);
            $('#productPrice').val(data.price);
            productModal.show();
        }).fail(() => alert('Error: Could not retrieve product data.'));
    });

    // --- Event Handler for form submission (Create & Update) ---
    productForm.on('submit', function(e) {
        e.preventDefault();
        
        const id = $('#productId').val();
        const url = id ? `index.php?action=update` : `index.php?action=create`;
        const formData = {
            id: id,
            name: $('#productName').val(),
            quantity: $('#productQuantity').val(),
            price: $('#productPrice').val()
        };

        $.ajax({
            url: url,
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            success: function(product) {
                productModal.hide();

                if (id) { // --- Handle UPDATE ---
                    const row = $(`#product-${product.id}`);
                    const price = parseFloat(product.price).toFixed(2);
                    row.find('.name').text(escapeHtml(product.name));
                    row.find('.quantity').text(escapeHtml(product.quantity));
                    row.find('.price').text(`$${price}`);
                    row.css('background-color', '#dff0d8').animate({backgroundColor: 'transparent'}, 1500);
                } else { // --- Handle CREATE ---
                    if (productList.find('td[colspan="5"]').length > 0) {
                        productList.empty();
                    }
                    const newRow = createProductRow(product);
                    productList.prepend(newRow);
                    $(`#product-${product.id}`).fadeIn(500);
                }
            },
            error: (xhr) => alert(`Operation failed: ${xhr.responseJSON?.message || 'An unknown error occurred.'}`)
        });
    });

    // --- Event Handler for clicking the 'Delete' button ---
    productList.on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');
        const productName = row.find('.name').text();
        
        if (confirm(`Are you sure you want to delete "${productName}"?`)) {
            $.ajax({
                url: `index.php?action=delete&id=${id}`,
                method: 'GET',
                success: function() {
                    row.fadeOut(500, function() {
                        $(this).remove();
                        showEmptyMessageIfNeeded();
                    });
                },
                error: (xhr) => alert(`Error: ${xhr.responseJSON?.message || 'Could not delete product.'}`)
            });
        }
    });

});