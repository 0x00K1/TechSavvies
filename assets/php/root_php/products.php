<div id="EditProduct" class="content">
    <h2 class="page-title">Products Management</h2>
    <div class="top-controls">
        <div class="search-section">
            <?php include("search_rows.php")?>
        </div>
        <button id="addProPopup_button" class="addProPopup_button_style" type="button" onclick="window.productsModule.addProPopup()">
            <i class="fas fa-plus"></i> Add Product
        </button>
    </div>
    
    <div class="table-container">
        <table id="products-table" data-table-type="products">
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
</div>

    <!-- Confirmation Modal -->
    <div id="confirmation-modal" class="modal">
        <div class="modal-content">
            <div class="modal-title">Confirm Deletion</div>
            <p>Are you sure you want to delete this product? This action cannot be undone.</p>
            <div class="modal-actions">
                <button type="button" class="modal-button modal-button-cancel" onclick="window.productsModule.closeConfirmationModal()">Cancel</button>
                <button type="button" class="modal-button modal-button-confirm" onclick="window.productsModule.confirmDelete()">Delete</button>
            </div>
        </div>
    </div>

    <script>
        // Check if variables are already defined
        if (typeof window.productsModule === 'undefined') {
            window.productsModule = {
                // Sample data for demonstration
                sampleProducts: [
                    { id: 1, name: "Laptop Pro", price: 1299.99, stock: 25, category: "Electronics", updated_date: "2025-02-28" },
                    { id: 2, name: "Wireless Mouse", price: 29.99, stock: 150, category: "Accessories", updated_date: "2025-03-01" },
                    { id: 3, name: "HD Monitor", price: 249.99, stock: 42, category: "Electronics", updated_date: "2025-02-25" },
                    { id: 4, name: "Bluetooth Speaker", price: 79.99, stock: 78, category: "Audio", updated_date: "2025-03-02" },
                    { id: 5, name: "Mechanical Keyboard", price: 129.99, stock: 55, category: "Accessories", updated_date: "2025-02-20" },
                    { id: 6, name: "USB-C Hub", price: 49.99, stock: 120, category: "Accessories", updated_date: "2025-02-15" },
                    { id: 7, name: "Smartphone X", price: 899.99, stock: 30, category: "Mobile", updated_date: "2025-03-01" },
                    { id: 8, name: "Wireless Headphones", price: 199.99, stock: 65, category: "Audio", updated_date: "2025-02-28" },
                    { id: 9, name: "External SSD", price: 159.99, stock: 85, category: "Storage", updated_date: "2025-02-27" },
                    { id: 10, name: "Gaming Mouse", price: 89.99, stock: 70, category: "Gaming", updated_date: "2025-03-01" }
                ],
            
                // State variables
                products: null,
                currentPage: 1,
                rowsPerPage: 100,
                sortColumn: 'id',
                sortDirection: 'asc',
                editingRow: null,
                productToDelete: null,
            
                init: function() {
                    this.products = [...this.sampleProducts];
                    this.loadProducts();
                    this.setupSortingListeners();
                    this.setupPaginationListeners();
                    
                    // Initialize card view
                    this.initializeCardView();
                    
                    // Handle window resize
                    window.addEventListener('resize', () => {
                        this.handleResponsiveView();
                    });
                    
                    const rowsSelect = document.getElementById('rows_per_page');
                    if (rowsSelect) {
                        rowsSelect.value = this.rowsPerPage;
                        rowsSelect.addEventListener('change', () => {
                            this.rowsPerPage = parseInt(rowsSelect.value);
                            this.currentPage = 1;
                            this.loadProducts();
                        });
                    }
                },

                initializeCardView: function() {
                    const container = document.querySelector('.content');
                    const cardView = document.createElement('div');
                    cardView.className = 'card-view';
                    container.appendChild(cardView);
                    
                    this.renderCards();
                },

                renderCards: function() {
                    const cardView = document.querySelector('.card-view');
                    cardView.innerHTML = '';
                    
                    this.products.forEach(product => {
                        const card = document.createElement('div');
                        card.className = 'data-card';
                        card.setAttribute('data-id', product.id);
                        
                        card.innerHTML = `
                            <div class="card-header">
                                <div>
                                    <h3>${product.name}</h3>
                                    <div class="card-id">ID: ${product.id}</div>
                                </div>
                                <span class="card-toggle">▼</span>
                            </div>
                            <div class="card-body">
                                <div class="card-row">
                                    <div class="card-label">Price</div>
                                    <div class="card-value" data-field="price">${product.price}</div>
                                </div>
                                <div class="card-row">
                                    <div class="card-label">Stock</div>
                                    <div class="card-value" data-field="stock">${product.stock}</div>
                                </div>
                                <div class="card-row">
                                    <div class="card-label">Category</div>
                                    <div class="card-value">${product.category}</div>
                                </div>
                                <div class="card-row">
                                    <div class="card-label">Updated Date</div>
                                    <div class="card-value">${product.updated_date}</div>
                                </div>
                                <div class="card-actions">
                                    <div class="buttons_table" id="buttons_table_${product.id}">
                                        <button type="button" class="edit_product_button_style" onclick="window.productsModule.editProduct(${product.id})">Edit</button>
                                        <button type="button" class="remove_product_button_style" onclick="window.productsModule.confirmationPopup(${product.id})">Remove</button>
                                    </div>
                                    <div class="buttons_table" id="edit_buttons_${product.id}" style="display:none;">
                                        <button type="button" class="product_cancel_edit_style" onclick="window.productsModule.cancelEdit(${product.id})">Cancel</button>
                                        <button type="button" class="product_confirm_edit_style" onclick="window.productsModule.saveEdit(${product.id})">Save</button>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        // Add click handler for expanding/collapsing
                        const header = card.querySelector('.card-header');
                        const body = card.querySelector('.card-body');
                        header.addEventListener('click', () => {
                            body.classList.toggle('active');
                            header.querySelector('.card-toggle').classList.toggle('active');
                        });
                        
                        cardView.appendChild(card);
                    });
                },
            
                // Function to fetch products (simulated)
                fetchProducts: async function() {
                    await new Promise(resolve => setTimeout(resolve, 300));
                    return [...this.sampleProducts];
                },
            
                // Load products to table
                loadProducts: async function() {
                    this.renderTable();
                    this.updatePaginationInfo();
                    this.renderCards(); // Add this to update card view
                },

                handleResponsiveView: function() {
                    const width = window.innerWidth;
                    const tableContainer = document.querySelector('.table-container');
                    const cardView = document.querySelector('.card-view');
                    
                    if (width <= 768) {
                        tableContainer.style.display = 'none';
                        cardView.style.display = 'block';
                    } else {
                        tableContainer.style.display = 'block';
                        cardView.style.display = 'none';
                    }
                },

                // Render table with current data and settings
                renderTable: function() {
                    const tableBody = document.getElementById('products-table-body');
                    tableBody.innerHTML = '';
                    
                    const sortedProducts = [...this.products].sort((a, b) => {
                        let valA = a[this.sortColumn];
                        let valB = b[this.sortColumn];
                        
                        if (this.sortColumn === 'price' || this.sortColumn === 'stock' || this.sortColumn === 'id') {
                            valA = parseFloat(valA);
                            valB = parseFloat(valB);
                        }
                        
                        if (this.sortDirection === 'asc') {
                            return valA > valB ? 1 : -1;
                        } else {
                            return valA < valB ? 1 : -1;
                        }
                    });
                    
                    const startIndex = (this.currentPage - 1) * this.rowsPerPage;
                    const endIndex = Math.min(startIndex + this.rowsPerPage, sortedProducts.length);
                    const paginatedProducts = sortedProducts.slice(startIndex, endIndex);
                    
                    paginatedProducts.forEach(product => {
                        const row = document.createElement('tr');
                        row.setAttribute('data-id', product.id);
                        
                        row.innerHTML = `
                            <td>${product.id}</td>
                            <td class="editable-cell" data-field="name">${product.name}</td>
                            <td class="editable-cell" data-field="price">${product.price}</td>
                            <td class="editable-cell" data-field="stock">${product.stock}</td>
                            <td>${product.category}</td>
                            <td>${product.updated_date}</td>
                            <td>
                                <div class="buttons_table" id="buttons_table_${product.id}">
                                    <button type="button" class="edit_product_button_style" onclick="window.productsModule.editProduct(${product.id})">Edit</button>
                                    <button type="button" class="remove_product_button_style" onclick="window.productsModule.confirmationPopup(${product.id})">Remove</button>
                                </div>
                                <div class="buttons_table" id="edit_buttons_${product.id}" style="display:none;">
                                    <button type="button" class="product_cancel_edit_style" onclick="window.productsModule.cancelEdit(${product.id})">Cancel</button>
                                    <button type="button" class="product_confirm_edit_style" onclick="window.productsModule.saveEdit(${product.id})">Save</button>
                                </div>
                            </td>
                        `;
                        
                        tableBody.appendChild(row);
                    });
                    
                    this.updateSortIndicators();
                },
            
                setupSortingListeners: function() {
                    const headers = document.querySelectorAll('th[data-sort]');
                    headers.forEach(header => {
                        header.addEventListener('click', () => {
                            const column = header.getAttribute('data-sort');
                            if (this.sortColumn === column) {
                                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                            } else {
                                this.sortColumn = column;
                                this.sortDirection = 'asc';
                            }
                            this.renderTable();
                        });
                    });
                },
            
                updateSortIndicators: function() {
                    const headers = document.querySelectorAll('th[data-sort]');
                    headers.forEach(header => {
                        const column = header.getAttribute('data-sort');
                        const sortIcon = header.querySelector('.sort-icon');
                        if (column === this.sortColumn) {
                            sortIcon.textContent = this.sortDirection === 'asc' ? '↑' : '↓';
                        } else {
                            sortIcon.textContent = '↕';
                        }
                    });
                },
            
                setupPaginationListeners: function() {
                    document.getElementById('prev-page').addEventListener('click', () => {
                        if (this.currentPage > 1) {
                            this.currentPage--;
                            this.renderTable();
                            this.updatePaginationInfo();
                        }
                    });
                    
                    document.getElementById('next-page').addEventListener('click', () => {
                        const maxPage = Math.ceil(this.products.length / this.rowsPerPage);
                        if (this.currentPage < maxPage) {
                            this.currentPage++;
                            this.renderTable();
                            this.updatePaginationInfo();
                        }
                    });
                },
            
                updatePaginationInfo: function() {
                    const totalItems = this.products.length;
                    const startItem = Math.min((this.currentPage - 1) * this.rowsPerPage + 1, totalItems);
                    const endItem = Math.min(startItem + this.rowsPerPage - 1, totalItems);
                    
                    document.getElementById('showing-start').textContent = startItem;
                    document.getElementById('showing-end').textContent = endItem;
                    document.getElementById('total-items').textContent = totalItems;
                    
                    document.getElementById('prev-page').disabled = (this.currentPage === 1);
                    document.getElementById('next-page').disabled = (endItem === totalItems);
                },
            
                changeRowsPerPage: function() {
                    const select = document.getElementById('rows_per_page');
                    this.rowsPerPage = parseInt(select.value);
                    this.currentPage = 1;
                    this.renderTable();
                    this.updatePaginationInfo();
                },
            
                editProduct: function(productId) {
                    this.editingRow = productId;
                    
                    document.getElementById(`buttons_table_${productId}`).style.display = 'none';
                    document.getElementById(`edit_buttons_${productId}`).style.display = 'flex';
                    
                    const row = document.querySelector(`tr[data-id="${productId}"]`);
                    const editableCells = row.querySelectorAll('.editable-cell');
                    
                    editableCells.forEach(cell => {
                        const field = cell.getAttribute('data-field');
                        const value = cell.textContent;
                        cell.innerHTML = `<input type="text" name="${field}" value="${value}" />`;
                        cell.classList.add('editable');
                    });
                },
            
                cancelEdit: function(productId) {
                    const product = this.products.find(p => p.id === productId);
                    
                    const row = document.querySelector(`tr[data-id="${productId}"]`);
                    const editableCells = row.querySelectorAll('.editable-cell');
                    
                    editableCells.forEach(cell => {
                        const field = cell.getAttribute('data-field');
                        cell.innerHTML = product[field];
                        cell.classList.remove('editable');
                    });
                    
                    document.getElementById(`buttons_table_${productId}`).style.display = 'flex';
                    document.getElementById(`edit_buttons_${productId}`).style.display = 'none';
                    
                    this.editingRow = null;
                },
            
                saveEdit: function(productId) {
                    const row = document.querySelector(`tr[data-id="${productId}"]`);
                    const editableCells = row.querySelectorAll('.editable-cell');
                    
                    const productIndex = this.products.findIndex(p => p.id === productId);
                    
                    if (productIndex !== -1) {
                        editableCells.forEach(cell => {
                            const field = cell.getAttribute('data-field');
                            const input = cell.querySelector('input');
                            const value = input.value;
                            
                            if (field === 'price' || field === 'stock') {
                                this.products[productIndex][field] = parseFloat(value);
                            } else {
                                this.products[productIndex][field] = value;
                            }
                            
                            cell.innerHTML = value;
                            cell.classList.remove('editable');
                        });
                        
                        this.products[productIndex].updated_date = new Date().toISOString().slice(0, 10);
                        row.cells[5].textContent = this.products[productIndex].updated_date;
                        
                        console.log('Product updated:', this.products[productIndex]);
                    }
                    
                    document.getElementById(`buttons_table_${productId}`).style.display = 'flex';
                    document.getElementById(`edit_buttons_${productId}`).style.display = 'none';
                    
                    this.editingRow = null;
                },
            
                confirmationPopup: function(productId) {
                    this.productToDelete = productId;
                    document.getElementById('confirmation-modal').style.display = 'block';
                },
            
                closeConfirmationModal: function() {
                    document.getElementById('confirmation-modal').style.display = 'none';
                    this.productToDelete = null;
                },
            
                confirmDelete: function() {
                    if (this.productToDelete !== null) {
                        const productIndex = this.products.findIndex(p => p.id === this.productToDelete);
                        
                        if (productIndex !== -1) {
                            this.products.splice(productIndex, 1);
                            this.renderTable();
                            this.updatePaginationInfo();
                            console.log('Product deleted:', this.productToDelete);
                        }
                        
                        this.closeConfirmationModal();
                    }
                },
            
                addProPopup: function() {
                    document.getElementById("addProPopup_display").style.display = "block";
                }
            };
            
            // Update HTML button onclick handlers to use the module
            document.querySelectorAll('.edit_product_button_style').forEach(button => {
                button.setAttribute('onclick', `window.productsModule.editProduct(${button.getAttribute('data-id')})`);
            });
            
            document.querySelectorAll('.remove_product_button_style').forEach(button => {
                button.setAttribute('onclick', `window.productsModule.confirmationPopup(${button.getAttribute('data-id')})`);
            });
            
            // Initialize the module
            document.addEventListener('DOMContentLoaded', () => {
                window.productsModule.init();
            });
            
            // Initialize immediately if DOM is already loaded
            if (document.readyState === 'complete') {
                window.productsModule.init();
            }
        }
    </script>
