<div id="EditProduct" class="content">
        <form name="Product_queries" id="Product_queries" method="post" action="index.php">
            <div class="top-controls">
                <button id="addProPopup_button" class="addProPopup_button_style" type="button" onclick="addProPopup()">
                    <i class="fas fa-plus"></i> Add Product
                </button>
                
                <div class="search_div">
                    <input class="search-field_style" name="product_search_field" id="search_field" type="text"
                        placeholder="Search... attribute:key ex(name:mustafa)" />
                    <button class="search-button_style" name="search_button" id="search_button" type="submit">
                        Search
                    </button>
                    <span class="rows_label">Rows:</span>
                    <select name="rows_per_page" id="rows_per_page" class="filter_value_style" onchange="changeRowsPerPage()">
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100" selected>100</option>
                        <option value="250">250</option>
                    </select>
                </div>
            </div>
            
            <div class="table-container">
                <table id="products-table">
                    <thead>
                        <tr>
                            <th data-sort="id">ID <span class="sort-icon">↕</span></th>
                            <th data-sort="name">Name <span class="sort-icon">↕</span></th>
                            <th data-sort="price">Price <span class="sort-icon">↕</span></th>
                            <th data-sort="stock">Stock <span class="sort-icon">↕</span></th>
                            <th data-sort="category">Category <span class="sort-icon">↕</span></th>
                            <th data-sort="updated_date">Updated Date <span class="sort-icon">↕</span></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="products-table-body">
                        <!-- Table rows will be added dynamically via JavaScript -->
                    </tbody>
                </table>
            </div>
            
            <div class="pagination">
                <div class="pagination-info">
                    Showing <span id="showing-start">1</span> to <span id="showing-end">10</span> of <span id="total-items">100</span> items
                </div>
                <div class="pagination-controls">
                    <button type="button" id="prev-page" class="pagination-button" disabled>&laquo; Previous</button>
                    <button type="button" id="next-page" class="pagination-button">Next &raquo;</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmation-modal" class="modal">
        <div class="modal-content">
            <div class="modal-title">Confirm Deletion</div>
            <p>Are you sure you want to delete this product? This action cannot be undone.</p>
            <div class="modal-actions">
                <button type="button" class="modal-button modal-button-cancel" onclick="closeConfirmationModal()">Cancel</button>
                <button type="button" class="modal-button modal-button-confirm" onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>

    <script>
        // Sample data for demonstration
        const sampleProducts = [
            { id: 1, name: "Laptop Pro", price: 1299.99, stock: 25, category: "Electronics", updated_date: "2025-02-28" },
            { id: 2, name: "Wireless Mouse", price: 29.99, stock: 150, category: "Accessories", updated_date: "2025-03-01" },
            { id: 3, name: "HD Monitor", price: 249.99, stock: 42, category: "Electronics", updated_date: "2025-02-25" },
            { id: 4, name: "Bluetooth Speaker", price: 79.99, stock: 78, category: "Audio", updated_date: "2025-03-02" },
            { id: 5, name: "Mechanical Keyboard", price: 129.99, stock: 55, category: "Accessories", updated_date: "2025-02-20" },
            { id: 6, name: "USB-C Hub", price: 49.99, stock: 120, category: "Accessories", updated_date: "2025-02-15" },
            { id: 7, name: "Smartphone X", price: 899.99, stock: 30, category: "Mobile", updated_date: "2025-03-01" },
            { id: 8, name: "Wireless Headphones", price: 199.99, stock: 65, category: "Audio", updated_date: "2025-02-28" },
            { id: 9, name: "External SSD", price: 159.99, stock: 85, category: "Storage", updated_date: "2025-02-27" },
            { id: 10, name: "Gaming Mouse", price: 89.99, stock: 70, category: "Gaming", updated_date: "2025-03-01" },
            { id: 1, name: "Laptop Pro", price: 1299.99, stock: 25, category: "Electronics", updated_date: "2025-02-28" },
            { id: 2, name: "Wireless Mouse", price: 29.99, stock: 150, category: "Accessories", updated_date: "2025-03-01" },
            { id: 31, name: "HD Monitor", price: 249.99, stock: 42, category: "Electronics", updated_date: "2025-02-25" },
            { id: 4, name: "Bluetooth Speaker", price: 79.99, stock: 78, category: "Audio", updated_date: "2025-03-02" },
            { id: 5, name: "Mechanical Keyboard", price: 129.99, stock: 55, category: "Accessories", updated_date: "2025-02-20" },
            { id: 61, name: "USB-C Hub", price: 49.99, stock: 120, category: "Accessories", updated_date: "2025-02-15" },
            { id: 71, name: "Smartphone X", price: 899.99, stock: 30, category: "Mobile", updated_date: "2025-03-01" },
            { id: 81, name: "Wireless Headphones", price: 199.99, stock: 65, category: "Audio", updated_date: "2025-02-28" },
            { id: 91, name: "External SSD", price: 159.99, stock: 85, category: "Storage", updated_date: "2025-02-27" },
            { id: 101, name: "Gaming Mouse", price: 89.99, stock: 70, category: "Gaming", updated_date: "2025-03-01" },
            { id: 1, name: "Laptop Pro", price: 1299.99, stock: 25, category: "Electronics", updated_date: "2025-02-28" },
            { id: 21, name: "Wireless Mouse", price: 29.99, stock: 150, category: "Accessories", updated_date: "2025-03-01" },
            { id: 31, name: "HD Monitor", price: 249.99, stock: 42, category: "Electronics", updated_date: "2025-02-25" },
            { id: 42, name: "Bluetooth Speaker", price: 79.99, stock: 78, category: "Audio", updated_date: "2025-03-02" },
            { id: 51, name: "Mechanical Keyboard", price: 129.99, stock: 55, category: "Accessories", updated_date: "2025-02-20" },
            { id: 6, name: "USB-C Hub", price: 49.99, stock: 120, category: "Accessories", updated_date: "2025-02-15" },
            { id: 72, name: "Smartphone X", price: 899.99, stock: 30, category: "Mobile", updated_date: "2025-03-01" },
            { id: 8, name: "Wireless Headphones", price: 199.99, stock: 65, category: "Audio", updated_date: "2025-02-28" },
            { id: 9, name: "External SSD", price: 159.99, stock: 85, category: "Storage", updated_date: "2025-02-27" },
            { id: 10, name: "Gaming Mouse", price: 89.99, stock: 70, category: "Gaming", updated_date: "2025-03-01" }
        ];

        // State variables
        let products = [...sampleProducts];
        let currentPage = 1;
        let rowsPerPage = 100;
        let sortColumn = 'id';
        let sortDirection = 'asc';
        let editingRow = null;
        let productToDelete = null;

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadProducts();
            setupSortingListeners();
            setupPaginationListeners();
            
            // Set default rows per page
            document.getElementById('rows_per_page').value = rowsPerPage;
        });

        // Function to fetch products (simulated)
        async function fetchProducts() {
            // In a real implementation, you would fetch from an API
            // For now, we'll return the sample data
            
            // Simulate API delay
            await new Promise(resolve => setTimeout(resolve, 300));
            
            return [...sampleProducts];
        }

        // Load products to table
        async function loadProducts() {
            // In a real implementation, you would fetch data here
            // products = await fetchProducts();
            
            renderTable();
            updatePaginationInfo();
        }

        // Render table with current data and settings
        function renderTable() {
            const tableBody = document.getElementById('products-table-body');
            tableBody.innerHTML = '';
            
            // Sort products
            const sortedProducts = [...products].sort((a, b) => {
                let valA = a[sortColumn];
                let valB = b[sortColumn];
                
                // Handle numeric sorts
                if (sortColumn === 'price' || sortColumn === 'stock' || sortColumn === 'id') {
                    valA = parseFloat(valA);
                    valB = parseFloat(valB);
                }
                
                if (sortDirection === 'asc') {
                    return valA > valB ? 1 : -1;
                } else {
                    return valA < valB ? 1 : -1;
                }
            });
            
            // Calculate pagination
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = Math.min(startIndex + rowsPerPage, sortedProducts.length);
            const paginatedProducts = sortedProducts.slice(startIndex, endIndex);
            
            // Create table rows
            paginatedProducts.forEach(product => {
                const row = document.createElement('tr');
                row.setAttribute('data-id', product.id);
                
                // Create cell for each product property
                row.innerHTML = `
                    <td>${product.id}</td>
                    <td class="editable-cell" data-field="name">${product.name}</td>
                    <td class="editable-cell" data-field="price">${product.price}</td>
                    <td class="editable-cell" data-field="stock">${product.stock}</td>
                    <td>${product.category}</td>
                    <td>${product.updated_date}</td>
                    <td>
                        <div class="buttons_table" id="buttons_table_${product.id}">
                            <button type="button" class="edit_product_button_style" onclick="editProduct(${product.id})">Edit</button>
                            <button type="button" class="remove_product_button_style" onclick="confirmationPopup(${product.id})">Remove</button>
                        </div>
                        <div class="buttons_table" id="edit_buttons_${product.id}" style="display:none;">
                            <button type="button" class="product_cancel_edit_style" onclick="cancelEdit(${product.id})">Cancel</button>
                            <button type="button" class="product_confirm_edit_style" onclick="saveEdit(${product.id})">Save</button>
                        </div>
                    </td>
                `;
                
                tableBody.appendChild(row);
            });

            // Update sorting indicators
            updateSortIndicators();
        }

        // Set up column sorting
        function setupSortingListeners() {
            const headers = document.querySelectorAll('th[data-sort]');
            headers.forEach(header => {
                header.addEventListener('click', () => {
                    const column = header.getAttribute('data-sort');
                    
                    // If clicking the same column, toggle direction
                    if (sortColumn === column) {
                        sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        sortColumn = column;
                        sortDirection = 'asc';
                    }
                    
                    renderTable();
                });
            });
        }

        // Update sort indicators
        function updateSortIndicators() {
            const headers = document.querySelectorAll('th[data-sort]');
            headers.forEach(header => {
                const column = header.getAttribute('data-sort');
                const sortIcon = header.querySelector('.sort-icon');
                
                if (column === sortColumn) {
                    sortIcon.textContent = sortDirection === 'asc' ? '↑' : '↓';
                } else {
                    sortIcon.textContent = '↕';
                }
            });
        }

        // Set up pagination controls
        function setupPaginationListeners() {
            document.getElementById('prev-page').addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderTable();
                    updatePaginationInfo();
                }
            });
            
            document.getElementById('next-page').addEventListener('click', () => {
                const maxPage = Math.ceil(products.length / rowsPerPage);
                if (currentPage < maxPage) {
                    currentPage++;
                    renderTable();
                    updatePaginationInfo();
                }
            });
        }

        // Update pagination information
        function updatePaginationInfo() {
            const totalItems = products.length;
            const startItem = Math.min((currentPage - 1) * rowsPerPage + 1, totalItems);
            const endItem = Math.min(startItem + rowsPerPage - 1, totalItems);
            
            document.getElementById('showing-start').textContent = startItem;
            document.getElementById('showing-end').textContent = endItem;
            document.getElementById('total-items').textContent = totalItems;
            
            // Enable/disable pagination buttons
            document.getElementById('prev-page').disabled = (currentPage === 1);
            document.getElementById('next-page').disabled = (endItem === totalItems);
        }

        // Change rows per page
        function changeRowsPerPage() {
            const select = document.getElementById('rows_per_page');
            rowsPerPage = parseInt(select.value);
            currentPage = 1; // Reset to first page
            renderTable();
            updatePaginationInfo();
        }

        // Edit product
        function editProduct(productId) {
            // Save the current editing row
            editingRow = productId;
            
            // Show edit buttons and hide regular buttons
            document.getElementById(`buttons_table_${productId}`).style.display = 'none';
            document.getElementById(`edit_buttons_${productId}`).style.display = 'flex';
            
            // Make fields editable
            const row = document.querySelector(`tr[data-id="${productId}"]`);
            const editableCells = row.querySelectorAll('.editable-cell');
            
            editableCells.forEach(cell => {
                const field = cell.getAttribute('data-field');
                const value = cell.textContent;
                
                // Replace with input field
                cell.innerHTML = `<input type="text" name="${field}" value="${value}" />`;
                cell.classList.add('editable');
            });
        }

        // Cancel edit
        function cancelEdit(productId) {
            // Get product data
            const product = products.find(p => p.id === productId);
            
            // Reset the row
            const row = document.querySelector(`tr[data-id="${productId}"]`);
            const editableCells = row.querySelectorAll('.editable-cell');
            
            editableCells.forEach(cell => {
                const field = cell.getAttribute('data-field');
                cell.innerHTML = product[field];
                cell.classList.remove('editable');
            });
            
            // Show regular buttons and hide edit buttons
            document.getElementById(`buttons_table_${productId}`).style.display = 'flex';
            document.getElementById(`edit_buttons_${productId}`).style.display = 'none';
            
            // Clear editing state
            editingRow = null;
        }

        // Save edit
        function saveEdit(productId) {
            // Get the row
            const row = document.querySelector(`tr[data-id="${productId}"]`);
            const editableCells = row.querySelectorAll('.editable-cell');
            
            // Find the product to update
            const productIndex = products.findIndex(p => p.id === productId);
            
            if (productIndex !== -1) {
                // Update product with new values
                editableCells.forEach(cell => {
                    const field = cell.getAttribute('data-field');
                    const input = cell.querySelector('input');
                    const value = input.value;
                    
                    // Update product data
                    if (field === 'price' || field === 'stock') {
                        products[productIndex][field] = parseFloat(value);
                    } else {
                        products[productIndex][field] = value;
                    }
                    
                    // Update cell content
                    cell.innerHTML = value;
                    cell.classList.remove('editable');
                });
                
                // Update the updated_date
                products[productIndex].updated_date = new Date().toISOString().slice(0, 10);
                row.cells[5].textContent = products[productIndex].updated_date;
                
                // In a real implementation, you would send the updates to the server here
                
                // Show success message or notification
                console.log('Product updated:', products[productIndex]);
            }
            
            // Show regular buttons and hide edit buttons
            document.getElementById(`buttons_table_${productId}`).style.display = 'flex';
            document.getElementById(`edit_buttons_${productId}`).style.display = 'none';
            
            // Clear editing state
            editingRow = null;
        }

        // Show confirmation popup
        function confirmationPopup(productId) {
            productToDelete = productId;
            document.getElementById('confirmation-modal').style.display = 'block';
        }

        // Close confirmation modal
        function closeConfirmationModal() {
            document.getElementById('confirmation-modal').style.display = 'none';
            productToDelete = null;
        }

        // Confirm delete
        function confirmDelete() {
            if (productToDelete !== null) {
                // Find the product index
                const productIndex = products.findIndex(p => p.id === productToDelete);
                
                if (productIndex !== -1) {
                    // Remove the product
                    products.splice(productIndex, 1);
                    
                    // In a real implementation, you would send the delete request to the server here
                    
                    // Refresh the table
                    renderTable();
                    updatePaginationInfo();
                    
                    // Show success message or notification
                    console.log('Product deleted:', productToDelete);
                }
                
                // Close the modal
                closeConfirmationModal();
            }
        }

        // Add product popup
        function addProPopup() {
            // In a real implementation, you would show a form to add a new product
            alert('Add Product functionality would be implemented here.');
            // You could implement a modal similar to the delete confirmation
        }
    </script>