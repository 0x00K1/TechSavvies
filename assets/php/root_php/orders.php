<div id="orders_display" class="content">
    <h2 class="page-title">Orders Management</h2>
    <div class="top-controls">
        <div class="search-section">
            <?php include("search_rows.php")?>
        </div>
    </div>
    
    <div class="table-container">
        <table id="orders-table" data-table-type="orders">
            <thead>
                <tr>
                    <th data-sort="Order_ID">Order ID <span class="sort-icon">↕</span></th>
                    <th data-sort="Order_Date">Order Date <span class="sort-icon">↕</span></th>
                    <th data-sort="Product_ID">Product ID <span class="sort-icon">↕</span></th>
                    <th data-sort="Product_Name">Product Name <span class="sort-icon">↕</span></th>
                    <th data-sort="Quantity">Quantity <span class="sort-icon">↕</span></th>
                    <th data-sort="Price_per_Unit">Price per Unit <span class="sort-icon">↕</span></th>
                    <th data-sort="Total_Price">Total Price <span class="sort-icon">↕</span></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="orders-table-body">
            <!-- Table content will be populated by JavaScript -->
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
        <div class="modal-title">Process Order</div>
        <p>Are you sure you want to process this order?</p>
        <div class="modal-actions">
            <button type="button" class="modal-button modal-button-cancel" onclick="window.ordersModule.closeConfirmationModal()">Cancel</button>
            <button type="button" class="modal-button modal-button-confirm" onclick="window.ordersModule.confirmProcess()">Process</button>
        </div>
    </div>
</div>

<script>
    // Check if variables are already defined
    if (typeof window.ordersModule === 'undefined') {
        window.ordersModule = {
            // Sample data for demonstration
            sampleOrders: [
            { 
                Order_ID: "ORD001",
                Order_Date: "2025-03-01",
                Product_ID: "PRD003",
                Product_Name: "HD Monitor",
                Quantity: 2,
                Price_per_Unit: 249.99,
                Total_Price: 499.98
            },
            { 
                Order_ID: "ORD002",
                Order_Date: "2025-03-01",
                Product_ID: "PRD001",
                Product_Name: "Laptop Pro",
                Quantity: 1,
                Price_per_Unit: 1299.99,
                Total_Price: 1299.99
            },
            { 
                Order_ID: "ORD003",
                Order_Date: "2025-03-02",
                Product_ID: "PRD005",
                Product_Name: "Mechanical Keyboard",
                Quantity: 3,
                Price_per_Unit: 129.99,
                Total_Price: 389.97
            },
            { 
                Order_ID: "ORD004",
                Order_Date: "2025-03-02",
                Product_ID: "PRD008",
                Product_Name: "Wireless Headphones",
                Quantity: 2,
                Price_per_Unit: 199.99,
                Total_Price: 399.98
            },
            { 
                Order_ID: "ORD005",
                Order_Date: "2025-03-03",
                Product_ID: "PRD002",
                Product_Name: "Wireless Mouse",
                Quantity: 5,
                Price_per_Unit: 29.99,
                Total_Price: 149.95
            }
            ],
        
            // State variables
            data: null,
            currentPage: 1,
            rowsPerPage: 100,
            sortColumn: 'Order_ID',
            sortDirection: 'asc',
            
            // Initialize the module
            init: function() {
                this.data = [...this.sampleOrders];
                this.loadOrders();
                this.setupSortingListeners();
                this.setupPaginationListeners();
                
                const rowsSelect = document.getElementById('rows_per_page');
                if (rowsSelect) {
                    rowsSelect.value = this.rowsPerPage;
                    rowsSelect.addEventListener('change', () => {
                        this.rowsPerPage = parseInt(rowsSelect.value);
                        this.currentPage = 1;
                        this.loadOrders();
                    });
                }
            },
            
            // Load orders to table
            loadOrders: function() {
                this.renderTable();
                this.updatePaginationInfo();
            },
        
            // Render table with current data and settings
            renderTable: function() {
                const tableBody = document.getElementById('orders-table-body');
                tableBody.innerHTML = '';
                
                const sortedData = [...this.data].sort((a, b) => {
                    let valA = a[this.sortColumn];
                    let valB = b[this.sortColumn];
                    
                    if (['Quantity', 'Price_per_Unit', 'Total_Price'].includes(this.sortColumn)) {
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
                const endIndex = Math.min(startIndex + this.rowsPerPage, sortedData.length);
                const paginatedData = sortedData.slice(startIndex, endIndex);
                
                paginatedData.forEach(order => {
                const row = document.createElement('tr');
                row.setAttribute('data-id', order.Order_ID);
                
                row.innerHTML = `
                    <td>${order.Order_ID}</td>
                    <td>${order.Order_Date}</td>
                    <td>${order.Product_ID}</td>
                    <td>${order.Product_Name}</td>
                    <td>${order.Quantity}</td>
                    <td>$${order.Price_per_Unit}</td>
                    <td>$${order.Total_Price}</td>
                    <td>
                        <div class="buttons_table">
                            <button type="button" class="edit_product_button_style" onclick="viewOrder('${order.Order_ID}')">View</button>
                            <button type="button" class="product_confirm_edit_style" onclick="processOrder('${order.Order_ID}')">Process</button>
                        </div>
                    </td>
                `;
                
                tableBody.appendChild(row);
            });
        
                this.updateSortIndicators();
            },
        
            // Add these utility functions
            updatePaginationInfo: function() {
                const total = this.data.length;
                const start = Math.min((this.currentPage - 1) * this.rowsPerPage + 1, total);
                const end = Math.min(start + this.rowsPerPage - 1, total);
        
                document.getElementById('showing-start').textContent = start;
                document.getElementById('showing-end').textContent = end;
                document.getElementById('total-items').textContent = total;
        
                document.getElementById('prev-page').disabled = this.currentPage === 1;
                document.getElementById('next-page').disabled = end >= total;
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
                    const maxPage = Math.ceil(this.data.length / this.rowsPerPage);
                    if (this.currentPage < maxPage) {
                        this.currentPage++;
                        this.renderTable();
                        this.updatePaginationInfo();
                    }
                });
            },
        
            updateSortIndicators: function() {
                const headers = document.querySelectorAll('th[data-sort]');
                headers.forEach(header => {
                    const icon = header.querySelector('.sort-icon');
                    if (header.getAttribute('data-sort') === this.sortColumn) {
                        icon.textContent = this.sortDirection === 'asc' ? '↑' : '↓';
                    } else {
                        icon.textContent = '↕';
                    }
                });
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
        
            viewOrder: function(orderId) {
                console.log('Viewing order:', orderId);
                // Implement view functionality
            },
        
            processOrder: function(orderId) {
                const modal = document.getElementById('confirmation-modal');
                modal.style.display = 'block';
                modal.setAttribute('data-order-id', orderId);
            },
        
            closeConfirmationModal: function() {
                const modal = document.getElementById('confirmation-modal');
                modal.style.display = 'none';
            },
        
            confirmProcess: function() {
                const modal = document.getElementById('confirmation-modal');
                const orderId = modal.getAttribute('data-order-id');
                console.log('Processing order:', orderId);
                // Implement process functionality
                this.closeConfirmationModal();
            }
        };
        // Add modal close on outside click
        window.onclick = function(event) {
            const modal = document.getElementById('confirmation-modal');
            if (event.target === modal) {
                window.ordersModule.closeConfirmationModal();
            }
        };

        // Initialize the module
        document.addEventListener('DOMContentLoaded', () => {
            window.ordersModule.init();
        });

        // Initialize immediately if DOM is already loaded
        if (document.readyState === 'complete') {
            window.ordersModule.init();
        }
    }
</script>
