document.addEventListener('DOMContentLoaded', function() {
     /*buttons*/
     const managePro_button = document.getElementById('managePro_button');
     const manageUser_button = document.getElementById('manageUser_button');
     const Orders_button = document.getElementById('Orders_button');
     const transaction_button = document.getElementById('transactions_button');
     const review_button = document.getElementById('Reviews_button');
     const logout_button = document.getElementById('Logout_button');
     const search_button = document.getElementById('search_button');
     
     /* div or displays*/
     const EditProduct = document.getElementById('EditProduct');
     const users_display = document.getElementById('users_display');
     const orders_display = document.getElementById('orders_display');
     const Transaction_display = document.getElementById('Transactions_display');
     const Reviews_display = document.getElementById('Reviews_Display'); 
 
     // Function to hide all displays
     function hideAllDisplays() {
         const displays = [EditProduct, users_display, orders_display, Transaction_display, Reviews_display];
         displays.forEach(display => {
             if(display) display.style.display = 'none';
         });
     }
 
     // Function to remove active class from all buttons
     function removeAllActiveClasses() {
         const buttons = [managePro_button, manageUser_button, Orders_button, transaction_button, review_button];
         buttons.forEach(button => {
             if(button) button.classList.remove('active');
         });
     }
 
     // Function to set active tab
     function setActiveTab(displayElement, activeButton) {
         hideAllDisplays();
         removeAllActiveClasses();
         if(displayElement) displayElement.style.display = 'block';
         if(activeButton) activeButton.classList.add('active');
     }
 
     /* first time loading page */
     setActiveTab(EditProduct, managePro_button);
 
     /* Event Listeners for tab switching */
     managePro_button.addEventListener('click', function() {
         setActiveTab(EditProduct, managePro_button);
     });
 
     // Implementation for manageUser_button
     manageUser_button.addEventListener('click', function() {
         setActiveTab(users_display, manageUser_button);
         const userTable = new tableFetcher({
            url: '../../api/users/list.php',
            connectionType: 'api',
            tableName: 'customers',  //name in database
            columnNames: ['customer_id','email','username','created_at'], // names in the database
            currentPage: 1,
            rowsPerPage: 2,
            sortColumn: 'customer_id',
            sortDirection: 'asc'
        }); 

        const usersTableBody = document.getElementById('users-table-body');
        userTable.fetchData();
        userTable.renderTable(usersTableBody);
     });
 
     Orders_button.addEventListener('click', function() {
         setActiveTab(orders_display, Orders_button);
         
        
     });
 
     transaction_button.addEventListener('click', function() {
         setActiveTab(Transaction_display, transaction_button);
     });
 
     review_button.addEventListener('click', function() {
         setActiveTab(Reviews_display, review_button);
     });
 
     //search functionality
   //  search_button.addEventListener('click', function() {    
    // });
 
     logout_button.addEventListener('click', function() {
         const confirmLogout = confirm("Are you sure you want to log out?");
         if (confirmLogout) {
             window.location.href = "/includes/cls.php";
         }
     });
});

function closeaddProPopup() {
    document.getElementById("addProPopup_display").style.display = "none";
}

function confirmationPopup() {
    document.getElementById("confirmationPopup_display").style.display = "block";
}

function closeconfirmationPopup() {
    document.getElementById("confirmationPopup_display").style.display = "none";
}

// Define these functions in the global scope
window.product_edit_button = function() {
    document.getElementById('product_edit_display').style.display = "block";
    document.getElementById('buttons_table_display').style.display = "none";
};

window.product_cancel_edit = function() {
    document.getElementById('product_edit_display').style.display = "none";
    document.getElementById('buttons_table_display').style.display = "block";
};

// Make sure the buttons have the correct onclick handlers after the page loads
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('edit_product_button')) {
        document.getElementById('edit_product_button').onclick = window.product_edit_button;
    }

    if (document.getElementById('product_cancel_edit')) {
        document.getElementById('product_cancel_edit').onclick = window.product_cancel_edit;
    }
});

/*_______________________________________________________________________________________________________

#######################################___Global table retriver___#######################################


------------------------------------------------Usage----------------------------------------------------
Option	            Type	        Default Value	            Description
connectionType  	string	        'local'	                        Specifies the type of data source. Possible values: 'local', 'mysql', or 'api'. This currently only serves as an indicator; you'll need to implement the actual fetching logic in WorkspaceData().
tableName	        string	        ''	                            The name of the table in your database or the endpoint of your API. Used in the WorkspaceData() method (you need to implement the fetching logic).
columnNames     	array	        []	                            An array of strings representing the names of the columns to be displayed in the table. The order in this array determines the order of the columns in the rendered table.
currentPage	        number	        1	                            The initial page number to display.
rowsPerPage	        number	        100	                            The number of rows to display on each page.
sortColumn	        string	        First                           column name in columnNames (or null if columnNames is empty)	The name of the column to sort the table by initially.
sortDirection	    string	        'asc'	                        The initial sorting direction. Possible values: 'asc' (ascending) or 'desc' (descending).


i. array data (within code)
            // Sample data
            const myData = [
                { id: 1, name: 'Apple', price: 1.00 },
                { id: 2, name: 'Banana', price: 0.50 },
                { id: 3, name: 'Orange', price: 0.75 }
            ];

            // Get the table body element where the data will be rendered
            const myTableBody = document.getElementById('my-table-body');

            // Instantiate the tableFetcher class
            const myTable = new tableFetcher({
                columnNames: ['id', 'name', 'price'], // Define the columns to display
                rowsPerPage: 2 // Set the number of rows per page
            });

            // Set the data for the table
            myTable.setData(myData);

            // Render the table in the specified table body
            myTable.renderTable(myTableBody);

            // To enable sorting, you'll need to add event listeners to your table headers (see section ii).
ii.implement sorting
            <table>
                <thead>
                    <tr>
                        <th data-sort="id">ID <span class="sort-icon">↕</span></th>
                        <th data-sort="name">Name <span class="sort-icon">↕</span></th>
                        <th data-sort="price">Price <span class="sort-icon">↕</span></th>
                    </tr>
                </thead>
                <tbody id="products-table-body">
                    </tbody>
            </table>
            <div id="pagination-controls">
                </div>

            <script>
                // ... (your tableFetcher instantiation and data setting) ...

                const tableHeaders = document.querySelectorAll('#products-table th[data-sort]');
                tableHeaders.forEach(header => {
                    header.addEventListener('click', () => {
                        const column = header.getAttribute('data-sort');
                        myTableFetcher.updateSort(column);
                    });
                });
            </script>
iii. use options
            const myAdvancedTable = new tableFetcher({
            connectionType: 'api',
            tableName: 'products',
            columnNames: ['product_id', 'product_name', 'price', 'quantity'],
            currentPage: 2,
            rowsPerPage: 50,
            sortColumn: 'product_name',
            sortDirection: 'desc'
        });
iv. full example
            <!DOCTYPE html>
<html>
<head>
    <title>Table Fetcher Example</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            cursor: pointer;
        }
        .sort-icon {
            margin-left: 5px;
            font-size: 0.8em;
        }
        .pagination-controls {
            margin-top: 10px;
        }
        .pagination-controls button {
            margin-right: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }
        .pagination-controls button.active {
            font-weight: bold;
        }
    </style>
</head>
<body>

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
            </tbody>
    </table>
    <div id="pagination-controls">
        </div>

    <script>
        class tableFetcher {
            //the class
        }

        document.addEventListener('DOMContentLoaded', initializeTable);
    </script>

</body>
</html>

v. more example
            // Example usage:
                async function initializeTable() {
                    // Sample data (replace with your actual data fetching)
                    const productsData = [
                        { id: 1, name: 'Laptop', price: 1200, stock: 50, category: 'Electronics', updated_date: '2024-01-15' },
                        { id: 2, name: 'Keyboard', price: 75, stock: 100, category: 'Electronics', updated_date: '2024-01-20' },
                        { id: 3, name: 'T-Shirt', price: 25, stock: 200, category: 'Apparel', updated_date: '2024-01-25' },
                        { id: 4, name: 'Mouse', price: 30, stock: 150, category: 'Electronics', updated_date: '2024-01-30' },
                        { id: 5, name: 'Jeans', price: 60, stock: 120, category: 'Apparel', updated_date: '2024-02-05' },
                        { id: 6, name: 'Monitor', price: 300, stock: 75, category: 'Electronics', updated_date: '2024-02-10' },
                        { id: 7, name: 'Book', price: 15, stock: 300, category: 'Books', updated_date: '2024-02-15' },
                        { id: 8, name: 'Headphones', price: 150, stock: 90, category: 'Electronics', updated_date: '2024-02-20' },
                        { id: 9, name: 'Shoes', price: 80, stock: 180, category: 'Apparel', updated_date: '2024-02-25' },
                        { id: 10, name: 'Coffee Maker', price: 50, stock: 60, category: 'Appliances', updated_date: '2024-03-01' },
                        { id: 11, name: 'Smartphone', price: 900, stock: 40, category: 'Electronics', updated_date: '2024-03-05' },
                        { id: 12, name: 'Desk Chair', price: 200, stock: 110, category: 'Furniture', updated_date: '2024-03-10' },
                        { id: 13, name: 'Notebook', price: 5, stock: 500, category: 'Stationery', updated_date: '2024-03-15' },
                        { id: 14, name: 'Backpack', price: 40, stock: 250, category: 'Accessories', updated_date: '2024-03-20' },
                        { id: 15, name: 'Tablet', price: 400, stock: 80, category: 'Electronics', updated_date: '2024-03-25' },
                        { id: 16, name: 'Lamp', price: 35, stock: 160, category: 'Home Decor', updated_date: '2024-03-30' },
                        { id: 17, name: 'Pants', price: 55, stock: 130, category: 'Apparel', updated_date: '2024-04-01' },
                        { id: 18, name: 'Smartwatch', price: 250, stock: 70, category: 'Electronics', updated_date: '2024-04-05' },
                        { id: 19, name: 'Mug', price: 10, stock: 400, category: 'Kitchenware', updated_date: '2024-04-08' },
                        { id: 20, name: 'Sofa', price: 500, stock: 30, category: 'Furniture', updated_date: '2024-04-10' },
                    ];

                    const myTableFetcher = new tableFetcher({
                        // connectionType: 'api', // Or 'mysql'
                        // tableName: 'products',
                        columnNames: ['id', 'name', 'price', 'stock', 'category', 'updated_date', 'actions'],
                        sortColumn: 'name',
                        rowsPerPage: 10
                    });

                    const productsTableBody = document.getElementById('products-table-body');
                    if (productsTableBody) {
                        myTableFetcher.setData(productsData); // Set the data
                        // myTableFetcher.fetchData(); // If you have implemented data fetching
                    }

                    // Example of adding sort functionality to table headers
                    const tableHeaders = document.querySelectorAll('#products-table th[data-sort]');
                    tableHeaders.forEach(header => {
                        header.addEventListener('click', () => {
                            const column = header.getAttribute('data-sort');
                            myTableFetcher.updateSort(column);
                        });
                    });
_________________________________________________________________________________________________________*/

class tableFetcher {
    constructor(options) {
        this.url = options.url || 'http://techsavvies.local';
        this.connectionType = options.connectionType || 'local'; // 'mysql' or 'api'
        this.tableName = options.tableName || '';
        this.columnNames = options.columnNames || [];
        this.currentPage = options.currentPage || 1;
        this.rowsPerPage = options.rowsPerPage || 100;
        this.sortColumn = options.sortColumn || (this.columnNames.length > 0 ? this.columnNames[0] : null);
        this.sortDirection = options.sortDirection || 'asc';
        this.data = []; // To use data in array
        this.paginationContainerId = options.paginationContainerId || 'users-pagination'; // Default for pagination container
        this.rowsPerPageInputId = options.rowsPerPageInputId || 'users_rows_per_page'; // Default for rows per page input
    }

    // Method to set the data (for local data or after fetching from API/DB)
    setData(data) {
        this.data = data;
        this.renderTable(this.tableBodyElement); // Re-render if data is updated
    }

    // Method to fetch data from the API
    fetchData() {
        if (this.connectionType === 'api') {
            console.log(`Workspaceing data from API endpoint: ${this.url}`);
            fetch(`${this.url}?page=${this.currentPage}&limit=${this.rowsPerPage}&sort=${this.sortColumn}&order=${this.sortDirection}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (Array.isArray(data)) { // Check if the data is an array
                        this.setData(data);
                        this.renderPaginationControls(data.length); // Total rows is the length of the array
                    } else if (data && data.error) {
                        console.error('API Error:', data.error);
                        // Optionally display an error message to the user
                    } else {
                        console.warn('API response is not in the expected format:', data);
                        this.setData([]); // Set to empty array if data is not in expected format
                        this.renderPaginationControls(0);
                    }
                })
                .catch(error => {
                    console.error('Error fetching data from API:', error);
                    // Optionally display an error message to the user
                });
        } else {
            console.log('No API endpoint specified or connection type is not set to "api".');
            this.renderPaginationControls(this.data.length); // For local data, use the length of the data array
            this.renderTable(this.tableBodyElement);
        }
    }
    updateSort(column) {
        if (this.sortColumn === column) {
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortColumn = column;
            this.sortDirection = 'asc';
        }
        this.currentPage = 1; // Reset to the first page after sorting
        if (this.connectionType === 'api') {
            this.fetchData(); // Re-fetch data from API with updated sorting
        } else {
            this.renderTable(this.tableBodyElement);
        }
    }

    updatePagination(pageNumber) {
        this.currentPage = pageNumber;
        if (this.connectionType === 'api') {
            this.fetchData(); // Re-fetch data from API for the new page
        } else {
            this.renderTable(this.tableBodyElement);
        }
    }

    updateRowsPerPage(rows) {
        this.rowsPerPage = rows;
        this.currentPage = 1; // Reset to the first page after changing rows per page
        if (this.connectionType === 'api') {
            this.fetchData(); // Re-fetch data from API with updated rows per page
        } else {
            this.renderTable(this.tableBodyElement);
        }
    }

    renderTable(tableBody) {
        if (!tableBody) {
            console.error('Table body element is not provided.');
            return;
        }
        this.tableBodyElement = tableBody; // Store the table body element
        tableBody.innerHTML = '';

        let dataToRender = this.data;
        if (this.connectionType !== 'api') {
            const sortedData = this.sortData();
            dataToRender = this.paginateData(sortedData);
        }

        dataToRender.forEach(item => {
            const row = document.createElement('tr');
            row.setAttribute('data-id', item.id); // Assuming each item has an 'id'

            this.columnNames.forEach(column => {
                const cell = document.createElement('td');
                cell.textContent = item[column];
                row.appendChild(cell);
            });

            // Add action buttons if needed
            if (this.columnNames.includes('actions')) {
                const actionsCell = document.createElement('td');
                const buttonsDiv = document.createElement('div');
                buttonsDiv.className = 'buttons_table';
                buttonsDiv.id = `buttons_table_${item.id}`;

                const editButton = document.createElement('button');
                editButton.type = 'button';
                editButton.className = 'edit_product_button_style';
                editButton.textContent = 'Edit';
                editButton.onclick = () => this.editRow(item.id); // Example action
                buttonsDiv.appendChild(editButton);

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'remove_product_button_style';
                removeButton.textContent = 'Remove';
                removeButton.onclick = () => this.confirmRemove(item.id); // Example action
                buttonsDiv.appendChild(removeButton);

                actionsCell.appendChild(buttonsDiv);
                row.appendChild(actionsCell);
            }

            tableBody.appendChild(row);
        });

        this.updateSortIndicators();
        if (this.connectionType !== 'api') {
            this.renderPaginationControls(this.data.length); // Use the total number of fetched data for pagination
        }
        // For API, renderPaginationControls is called after fetching data
    }

    sortData() {
        if (!this.sortColumn) {
            return [...this.data]; // Return a copy if no sort column is defined
        }

        return [...this.data].sort((a, b) => {
            let valA = a[this.sortColumn];
            let valB = b[this.sortColumn];

            // Attempt to handle numeric sorts
            if (!isNaN(parseFloat(valA)) && isFinite(valA) && !isNaN(parseFloat(valB)) && isFinite(valB)) {
                valA = parseFloat(valA);
                valB = parseFloat(valB);
            } else if (typeof valA === 'string' && typeof valB === 'string') {
                valA = valA.toLowerCase();
                valB = valB.toLowerCase();
            }

            if (this.sortDirection === 'asc') {
                return valA > valB ? 1 : (valA < valB ? -1 : 0);
            } else {
                return valA < valB ? 1 : (valA > valB ? -1 : 0);
            }
        });
    }

    paginateData(data) {
        const startIndex = (this.currentPage - 1) * this.rowsPerPage;
        const endIndex = Math.min(startIndex + this.rowsPerPage, data.length);
        return data.slice(startIndex, endIndex);
    }

    updateSortIndicators() {
        const headers = document.querySelectorAll('th[data-sort]');
        headers.forEach(header => {
            const column = header.getAttribute('data-sort');
            const sortIcon = header.querySelector('.sort-icon');

            if (sortIcon) {
                if (column === this.sortColumn) {
                    sortIcon.textContent = this.sortDirection === 'asc' ? '↑' : '↓';
                } else {
                    sortIcon.textContent = '↕';
                }
            }
        });
    }

    renderPaginationControls(totalRows) {
        const paginationContainer = document.getElementById(this.paginationContainerId);
        if (!paginationContainer) {
            console.error(`Pagination container with ID "${this.paginationContainerId}" not found.`);
            return;
        }
    
        const prevButton = paginationContainer.querySelector('#prev-page');
        const nextButton = paginationContainer.querySelector('#next-page');
        const paginationInfo = paginationContainer.querySelector('.pagination-info');
        const rowsPerPageInput = document.getElementById(this.rowsPerPageInputId); // Get it directly by ID
    
        if (!prevButton || !nextButton  || !paginationInfo || !rowsPerPageInput) {
            console.warn('One or more pagination control elements are missing in the DOM.'); // Changed to warn as rowsPerPageInput might be outside
            if (!prevButton) console.warn('#prev-page missing');
            if (!nextButton) console.warn('#next-page missing');
            if (!paginationInfo) console.warn('.pagination-info missing');
            if (!rowsPerPageInput) console.warn(`#${this.rowsPerPageInputId} missing`);
            return;
        }
    
        const totalPages = Math.ceil(totalRows / this.rowsPerPage);
    
        prevButton.disabled = this.currentPage === 1;
        nextButton.disabled = this.currentPage === totalPages;
        

        var pageNumberInput = this.currentPage;
        rowsPerPageInput.value = this.rowsPerPage; // Set the initial value of the input
    
        const startItem = (this.currentPage - 1) * this.rowsPerPage + 1;
        const endItem = Math.min(this.currentPage * this.rowsPerPage, totalRows);
    
        paginationInfo.innerHTML = `Showing <span id="showing-start">${startItem}</span> to <span id="showing-end">${endItem}</span> of <span id="total-items">${totalRows}</span> items`;
    
        // Update event listeners
        prevButton.onclick = () => {
            if (this.currentPage > 1) {
                this.updatePagination(this.currentPage - 1);
            }
        };
    
        nextButton.onclick = () => {
            if (this.currentPage < totalPages) {
                this.updatePagination(this.currentPage + 1);
            }
        };
    
        pageNumberInput.onchange = (event) => {
            const page = parseInt(event.target.value);
            if (!isNaN(page) && page >= 1 && page <= totalPages) {
                this.updatePagination(page);
            } else {
                pageNumberInput.value = this.currentPage; // Reset to current page if invalid input
            }
        };
    
        // Attach event listener to the existing rows per page input
        rowsPerPageInput.addEventListener('change', (event) => {
            const rows = parseInt(event.target.value);
            if (!isNaN(rows) && rows > 0) {
                this.updateRowsPerPage(rows);
            } else {
                rowsPerPageInput.value = this.rowsPerPage; // Reset to current value if invalid input
            }
        });
    }

    // Example methods for row actions (you would implement these based on your needs)
    editRow(id) {
        console.log(`Editing row with ID: ${id}`);
        // Implement your edit logic here
    }

    confirmRemove(id) {
        console.log(`Confirming removal of row with ID: ${id}`);
        // Implement your confirmation and removal logic here
    }
}