$(document).ready(function() {

    const productModal = new bootstrap.Modal(document.getElementById('productModal'));
    const productForm = $('#productForm');

    // --- Reset and open modal for adding a product ---
    $('#addProductBtn').on('click', function() {
        productForm.trigger('reset');
        $('#productId').val('');
        $('#productModalLabel').text('Add New Product');
    });

    // --- Handle Edit Button Click (Event Delegation) ---
    $('#product-list').on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        
        // Fetch product data from the server using the API endpoint
        $.get(`index.php?action=get&id=${id}`, function(data) {
            // Populate the modal form with the fetched data
            $('#productModalLabel').text('Edit Product');
            $('#productId').val(data.id);
            $('#productName').val(data.name);
            $('#productQuantity').val(data.quantity);
            $('#productPrice').val(data.price);
            
            // Show the modal for editing
            productModal.show();
        }).fail(function() {
            alert('Error: Could not retrieve product data. Please try again.');
        });
    });

    // --- Handle Form Submission for both Create and Update ---
    productForm.on('submit', function(e) {
        e.preventDefault();
        
        const id = $('#productId').val();
        // If an ID exists, we're updating. Otherwise, we're creating.
        const url = id ? `index.php?action=update` : `index.php?action=create`;
        const method = 'POST'; // Use POST for both to simplify handling

        const formData = {
            id: id,
            name: $('#productName').val(),
            quantity: $('#productQuantity').val(),
            price: $('#productPrice').val()
        };

        // Send the data to the server via AJAX
        $.ajax({
            url: url,
            method: method,
            contentType: 'application/json',
            data: JSON.stringify(formData),
            success: function(response) {
                productModal.hide();
                // Simple reload to show the updated data. A more advanced
                // implementation would update the table row dynamically.
                location.reload(); 
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON ? xhr.responseJSON.message : 'An unknown error occurred.';
                alert(`Operation failed: ${errorMsg}`);
            }
        });
    });

    // --- Handle Delete Button Click (Event Delegation) ---
    $('#product-list').on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        const productName = $(this).closest('tr').find('.name').text();
        
        // Use a confirmation dialog to prevent accidental deletion
        if (confirm(`Are you sure you want to delete "${productName}"?`)) {
            $.ajax({
                url: `index.php?action=delete&id=${id}`,
                method: 'GET', // Or 'DELETE' if the router is configured for it
                success: function(response) {
                    // On successful deletion, remove the row from the table dynamically
                    $(`#product-${id}`).fadeOut(500, function() {
                        $(this).remove();
                        // If no products are left, show the "empty" message.
                        if ($('#product-list tr').length === 0) {
                            location.reload(); // Reload to show the empty message from the server
                        }
                    });
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Could not delete the product.';
                    alert(`Error: ${errorMsg}`);
                }
            });
        }
    });

});
