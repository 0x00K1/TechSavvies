document.addEventListener('DOMContentLoaded', function () {
    /*toolbar buttons*/
    const manageProbutton = document.getElementById('manageProButton');
    const manageUserbutton = document.getElementById('manageUserButton');
    const Ordersbutton = document.getElementById('OrdersButton');
    const transactionbutton = document.getElementById('transactionsButton');
    const reviewbutton = document.getElementById('ReviewsButton');


    const logoutbutton = document.getElementById('LogoutButton');
    const searchbutton = document.getElementById('searchButton');

    /* toolbar div or displays*/

    const EditProduct = document.getElementById('editProduct');
    const usersdisplay = document.getElementById('usersDisplay');
    const ordersdisplay = document.getElementById('ordersDisplay');
    const Transactiondisplay = document.getElementById('transactionsDisplay');
    const Reviewsdisplay = document.getElementById('reviewsDisplay');


    // Function to hide all displays (toolbar)
    function hideAllDisplays() {
        const displays = [EditProduct, usersdisplay, ordersdisplay, Transactiondisplay, Reviewsdisplay];
        displays.forEach(display => {
            if (display) display.style.display = 'none';
        });
    }

    // Function to remove active class from all buttons (toolbar)
    function removeAllActiveClasses() {
        const buttons = [manageProbutton, manageUserbutton, Ordersbutton, transactionbutton, reviewbutton];
        buttons.forEach(button => {
            if (button) button.classList.remove('active');
        });
    }

    // Function to set active tab (toolbar)
    function setActiveTab(displayElement, activeButton) {
        hideAllDisplays();
        removeAllActiveClasses();
        if (displayElement) displayElement.style.display = 'block';
        if (activeButton) activeButton.classList.add('active');
    }

    /* first time loading page  (toolbar)*/
    setActiveTab(EditProduct, manageProbutton);
    //### declaring them here within the document load to avoid reapeted fetch requests##
    //product
    const productTable = new fetchTable({  // generating the object OUTSIDE THE BUTTON SINCE products is the first page
        url: '../../api/users/list.php',
        tableName: 'products', //must be like sql100%
        columnName: [`product_id`, `category_id`, `product_name`, `picture`, `description`, `color`, `size`, `price`,
            `stock`, `created_at`, `updated_at`, `created_by`],  //must be like sql100%
        rowsPerPage: 10,
        idNamingSuffix: 'products',    // to locate the next prev current page ids following the standard suffix-next-page so on
    });
    //users
    const userTable = new fetchTable({
        url: '../../api/users/list.php',
        tableName: 'customers',
        columnName: ['email', 'customer_id', 'username', 'created_at', 'iamfakeandbad'],
        rowsPerPage: 10,
        idNamingSuffix: 'users',
    });
    //orders
    const orderTable = new fetchTable({
        url: '../../api/users/list.php',
        tableName: 'orders',
        columnName: ['order_id', 'customer_id', 'status', 'total_amount', 'order_date'],
        rowsPerPage: 10,
        idNamingSuffix: 'orders',
    });
    //transactions
    const transactionTable = new fetchTable({
        url: '../../api/users/list.php',
        tableName: 'payments',
        columnName: [`payment_id`, `order_id`, `customer_id`, `payment_method`, `payment_status`, `transaction_id`, `amount`, `created_at`],
        rowsPerPage: 10,
        idNamingSuffix: 'transactions',
    });
    //review
    const reviewTable = new fetchTable({
        url: '../../api/users/list.php',
        tableName: 'reviews',
        columnName: [`review_id`, `customer_id`, `product_id`, `rating`, `review_text`, `created_at`],
        rowsPerPage: 10,
        idNamingSuffix: 'reviews',
    });


    // button listeners for toolbar
    manageProbutton.addEventListener('click', function () {
        setActiveTab(EditProduct, manageProbutton);//tab switch(toolbar)
    });

    // calling the table render and fetching and tab switch(toolbar) [TRFS]&
    manageUserbutton.addEventListener('click', function () {
        setActiveTab(usersdisplay, manageUserbutton);
    });

    // calling the table render and fetching and tab switch(toolbar) [TRFS]&
    Ordersbutton.addEventListener('click', function () {
        setActiveTab(ordersdisplay, Ordersbutton);
    });

    // calling the table render and fetching and tab switch(toolbar) [TRFS]&
    transactionbutton.addEventListener('click', function () {
        setActiveTab(Transactiondisplay, transactionbutton);
    });

    // calling the table render and fetching and tab switch(toolbar) [TRFS]&
    reviewbutton.addEventListener('click', function () {
        setActiveTab(Reviewsdisplay, reviewbutton);
    });


    //search functionality
    //  searchbutton.addEventListener('click', function() {    
    // });
    //LOGOUT   button
    logoutbutton.addEventListener('click', function () {
        const confirmLogout = confirm("Are you sure you want to log out?");
        if (confirmLogout) {
            window.location.href = "/includes/cls.php";
        }
    });
});

// edit remove in product table buttons [these gose in class better maybe]
function confirmationPopup() {
    document.getElementById("confirmationPopupDisplay").style.display = "block";
}

function closeconfirmationPopup() {
    document.getElementById("confirmationPopupDisplay").style.display = "none";
}

window.product_editbutton = function () {
    document.getElementById('productEditDisplay').style.display = "block";
    document.getElementById('buttonsTableDisplay').style.display = "none";
};

window.product_cancel_edit = function () {
    document.getElementById('productEditDisplay').style.display = "none";
    document.getElementById('buttonsTableDisplay').style.display = "block";
};

//document.getElementById('editProductButton').onclick = window.product-editbutton;
//document.getElementById('productCancelEdit').onclick = window.product-cancel-edit;




//add product button things
const addProPopupDisplay = document.getElementById('addProPopupDisplay');
const addProductButton = document.getElementById('addProPopupButton');
const closeProductPopUpButton = document.getElementById('closeProductPopUpButton');
function addProPopup() {
    document.getElementById("addProPopupDisplay").style.display = "block";
}
function closeaddProPopup() {
    document.getElementById("addProPopupDisplay").style.display = "none";
}
addProductButton.addEventListener('click', addProPopup);
closeProductPopUpButton.addEventListener('click', closeaddProPopup);

/*-------------------------------------------------------------------------------------------------------

#######################################---Global table retriver---#######################################


-------------------------------------------------------------------------------------------------------------------------------------
| Type     | Name                     | Description                                                                 | Default Value |
| :------- | :------------------------| :-------------------------------------------------------------------------- | :------------ |
| Variable | `url`                    | URL to fetch data from.(or path)                                            |               |
| Variable | `tableName`              | Name of the table/data source. (must match database name)                   |               |
| Variable | `columnName`             | Array of column names to display.  (must match database name)               | `[]`          |
| Variable | `currentPage`            | Current page number.                                                        | `1`           |
| Variable | `rowsPerPage`            | Number of rows per page.                                                    | `100`         |
| Variable | `sortDirection`          | Sorting direction ('asc' or 'desc').                                        | `'asc'`       |                                           | `[]`          |
| Variable | `idNamingSuffix`         | Suffix for generating unique HTML element IDs. ex(id= {suffix}Table)        | `''`          |
| Variable | `totalRecords`           | Total number of records.(this fetched from database)                        | `0`           |
| Variable | `pageOffset`             | Starting index of records for the current page.(for logic display of pages) |               |
| Variable | `totalPages`             | Total number of pages.(calculated in the class)                             |               |
| Variable | `flagPage`               | Flag to indicate if total records need to be fetched.                       | `true`        |
| Variable | `cachedData`             | stores already fetched data to avoid repeated fetch requests                | `[]`          |
| Method   | `constructor(options)`   | Initializes the `WorkspaceTable` with provided options.               
| Method   | `WorkspaceData()`        | Fetches data from the `url` based on current pagination.                
| Method   | `renderTable()`          | Renders the HTML table with the fetched `data`.                             
| Method   | `updatePaginationInfo()` | Updates the display showing pagination information (e.g., "Showing..."). 
| Method   | `paginationControls()`   | Sets up event listeners for pagination buttons and rows per page select. 
-------------------------------------------------------------------------------------------------------------------------------------
----------------------------------------------------------------Usage----------------------------------------------------------------
const reviewTable = new fetchTable({              //create an object
            url : '../../api/users/list.php',     //specify the URL
            tableName : 'reviews',                // the table name from database
            columnName : [`review_id`,`customer_id`,`product_id`,`rating`,`review_text`,`created_at`], //column names from database in any order wanted to be in the table
            rowsPerPage :19,            //optional rowperpage
            idNamingSuffix :'reviews',  //suffix that matches the html IDs  
         });
         reviewTable.fetchData();       //call fetchdata is a must to render table and call everything in the class
---------------------------------------------------------------------------------------------------------*/

class fetchTable {
    constructor(options) {
        this.url = options.url;
        this.tableName = options.tableName;
        this.columnName = options.columnName || [];
        this.currentPage = options.currentPage || 1;
        this.rowsPerPage = options.rowsPerPage || 100;
        this.sortDirection = options.sortDirection || 'asc';
        this.idNamingSuffix = options.idNamingSuffix || '';
        this.totalRecords = options.totalRecords || 0;
        this.pageOffset = options.pageOffset;
        this.totalPages = options.totalPages;
        this.flagPage = options.flagPage || true;
        this.cachedData = [];
        this.sortColumn = null;
        this.paginationControls();
        if (this.cachedData.length === 0) { // Fetch initial data if not provided
            this.fetchData();
        } else {
            this.renderTable();
            this.updatePaginationInfo();
        }
    }
    fetchData() {

        this.pageOffset = Math.max(0, (this.currentPage - 1) * this.rowsPerPage);//posiive

        //uses the cashed data if exists
        if (this.cachedData[this.currentPage]) {
            this.renderTable();
            this.updatePaginationInfo();
            this.updatePaginationButtons();
            return;
        }

        //fetch data
        fetch(`${this.url}?tableName=${this.tableName}&rowNumber=${this.rowsPerPage}&rowOffset=${this.pageOffset}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(response => {
                this.cachedData[this.currentPage] = response.records; // Cache the fetched data
                if (this.flagPage) {  // change flag if rows changed
                    this.totalRecords = response.totalRecords;// lazy sulotion to avoid repeated fetch of the same value
                    this.totalPages = Math.ceil(this.totalRecords / this.rowsPerPage);
                    this.flagPage = false;
                }

                this.renderTable();
                this.updatePaginationInfo();
                this.updatePaginationButtons();

                // UPDATE PAGINATION STUFF
                const nextPageElement = document.getElementById(`${this.idNamingSuffix}NextPage`);



                // Disable next button if on last page or no more data
                if (nextPageElement) {
                    nextPageElement.disabled = this.currentPage >= this.totalPages;
                }
            })
            .catch(error => {
                // Handle any errors that occurred during the fetch operation
                console.error('Error fetching data:', error);
            });
    };//fetchdata
    updatePaginationButtons() {
        const nextPageElement = document.getElementById(`${this.idNamingSuffix}NextPage`);
        const prevPageElement = document.getElementById(`${this.idNamingSuffix}PrevPage`);

        if (nextPageElement) {
            nextPageElement.disabled = this.currentPage >= this.totalPages;
        }
        if (prevPageElement) {
            prevPageElement.disabled = this.currentPage <= 1;
        }
        const currentPageElement = document.getElementById(`${this.idNamingSuffix}CurrentPage`);
        if (currentPageElement) {
            currentPageElement.textContent = this.currentPage;
        }
    }//update paginationButtons
    sortData(columnName) {
        if (this.sortColumn === columnName) {
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortColumn = columnName;
            this.sortDirection = 'asc';
        }

        this.cachedData[this.currentPage].sort((a, b) => {
            const valueA = a[columnName];
            const valueB = b[columnName];

            let comparison = 0;
            if (typeof valueA === 'string' && typeof valueB === 'string') {
                comparison = valueA.localeCompare(valueB);
            } else if (typeof valueA === 'number' && typeof valueB === 'number') {
                comparison = valueA - valueB;
            } else if (valueA > valueB) {
                comparison = 1;
            } else if (valueA < valueB) {
                comparison = -1;
            }

            return this.sortDirection === 'asc' ? comparison : comparison * -1;
        });
        this.renderTable();
    }//sort
    renderTable() {
        const display = document.getElementById(`${this.idNamingSuffix}TableDisplay`);
        const tableElement = document.createElement('table');
        display.innerHTML = '';
        display.appendChild(tableElement);
        if (!tableElement) {
            console.error('Table body element is not provided.');
            return;
        }

        //creating the table head
        const header = document.createElement('thead');
        const hrow = document.createElement('tr');

        //fill table head

        tableElement.appendChild(header);
        header.appendChild(hrow);

        this.columnName.forEach(column => {
            const hdata = document.createElement('th');
            hdata.innerHTML = column + `<span class="sort-icon" data-sort="${column}">↕</span>`;
            hdata.dataset.column = column; // Store column name for sorting
            hdata.style.cursor = 'pointer'; // Indicate it's clickable
            hdata.addEventListener('click', (event) => {
                const clickedColumn = event.target.dataset.column;
                if (clickedColumn) {
                    this.sortData(clickedColumn);
                }});

            // Update sort icon
            const sortIconSpan = hdata.querySelector('.sort-icon');
            if (sortIconSpan) {
                if (this.sortColumn === column) {
                    sortIconSpan.textContent = this.sortDirection === 'asc' ? '↑' : '↓';
                } else {
                    sortIconSpan.textContent = '↕';
                }
            }
            hrow.appendChild(hdata);
        });
        
        //create and fill table body
        const tbody = document.createElement('tbody');
        tableElement.appendChild(tbody);

        this.cachedData[this.currentPage].forEach(data => {
            const brow = document.createElement('tr');
            tbody.appendChild(brow);
            this.columnName.forEach(bcolumn => {           //for(int i=0 ; i < columnName.length; i++)
                const bdata = document.createElement('td');
                bdata.textContent = data[bcolumn];
                if (data[bcolumn] === undefined) {
                    bdata.textContent = 'not found'; bdata.style.color = 'orange';
                } else if (data[bcolumn] === null) {
                    bdata.textContent = '-'; bdata.style.color = 'orange';
                }
                brow.appendChild(bdata);
            })
        })




    }//renderTable 
    updatePaginationInfo() {
        const paginationInfo = document.getElementById(`${this.idNamingSuffix}PaginationInfo`);
        const startItem = this.pageOffset + 1;
        const endItem = Math.min(this.pageOffset + this.rowsPerPage, this.totalRecords);
        if (!paginationInfo) {
            console.error('Paginationinfo not found. Make sure they have the correct IDs.');
            return;

        } else {
            paginationInfo.innerHTML = `Showing <span>${startItem}
               </span> to <span >${endItem}
               </span> of <span>${this.totalRecords}</span> items`;
        }
    }
    paginationControls() {

        const nextPageElement = document.getElementById(`${this.idNamingSuffix}NextPage`);
        const prevPageElement = document.getElementById(`${this.idNamingSuffix}PrevPage`);
        const currentPageElement = document.getElementById(`${this.idNamingSuffix}CurrentPage`);


        if (!nextPageElement || !prevPageElement || !currentPageElement) {
            if (!nextPageElement)
                console.error('nextPageElement not found. Make sure they have the correct IDs.');
            if (!prevPageElement)
                console.error('prevPageElement not found. Make sure they have the correct IDs.');
            if (!currentPageElement)
                console.error('currentPageElement not found. Make sure they have the correct IDs.');
            return;
        }

        currentPageElement.textContent = this.currentPage;

        this.updatePaginationButtons();

        nextPageElement.addEventListener('click', () => {
            this.currentPage++;
            this.fetchData();

            // Update buttons state
            prevPageElement.disabled = false;
            currentPageElement.textContent = this.currentPage;
        });

        prevPageElement.addEventListener('click', () => {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.fetchData();
            }
        });

        // Handle rows per page change
        const rowsPerPage = document.getElementById(`${this.idNamingSuffix}RowsPerPage`);
        rowsPerPage.addEventListener('change', (event) => { // Use an arrow function here
            this.rowsPerPage = parseInt(rowsPerPage.value); // Get the value and parse it as an integer
            this.currentPage = 1; // Reset to the first page when rows per page changes
            this.cachedData = {}; // Clear cache on rows per page change
            this.flagPage = true; // Reset the flag so total records are fetched again
            this.fetchData(); // Fetch data with the new rows per page
        });
    } //paginationControls
}//class