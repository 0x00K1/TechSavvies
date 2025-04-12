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
         const userTable = new fetchTable({
            url : '../../api/users/list.php',
            tableBodyElement : document.getElementById('users-table'), 
            tableName : 'customers',
            columnName : ['email','customer_id','username','created_at','iamfakeandbad'],
            paginationContainerId : 'users-pagination', 
            rowsPerPageElement: document.getElementById('users_rows_per_page'), 
            //currentPage : 1, DEFAULT
            rowsPerPage :3,
            idNamingSuffix :'users',    // to locate the next prev current page ids following the standard suffix-next-page so on
        //this.sortColumn = options.sortColumn || (this.columnName.length > 0 ? this.columnName[0] : null); default
        //this.sortDirection = options.sortDirection || 'asc'; default
         });
         userTable.fetchData();

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


_________________________________________________________________________________________________________*/

class fetchTable{
    
    constructor (options){
        this.url = options.url;
        this.tableBodyElement = options.tableBodyElement ; 
        this.tableName = options.tableName;   // name in DB
        this.columnName = options.columnName || []; // name in DB
        this.paginationContainerId = options.paginationContainerId ; 
        this.rowsPerPageElement = options.rowsPerPageElement ; 
        this.currentPage = options.currentPage || 1;
        this.rowsPerPage = options.rowsPerPage || 100;
        this.sortColumn = options.sortColumn || (this.columnName.length > 0 ? this.columnName[0] : null);
        this.sortDirection = options.sortDirection || 'asc';
        this.data = options.data||[]; // if using an array data with no connection use it
        this.idNamingSuffix = options.idNamingSuffix||'';
        this.totalRecords= options.totalRecords|| 0;
        this.pageOffset = options.pageOffset;
        this.totalPages = options.totalPages;
        this.flagPage = options.flagPage ||true;
        this.paginationControls();
    }
    fetchData(){
        
        this.pageOffset = Math.max(0, (this.currentPage - 1) * this.rowsPerPage);//posiive

        //fetch data
        fetch(`${this.url}?rowNumber=${this.rowsPerPage}&rowOffset=${this.pageOffset}`)
        .then(response => {
            if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
          })
          .then(response => {
            this.data = response.records;
            if(this.flagPage){  // change flag if rows changed
                this.totalRecords = response.totalRecords;
                this.totalPages = Math.ceil(this.totalRecords / this.rowsPerPage);
                this.flagPage = false;
            }
            
            this.renderTable();
            this.updatePaginationInfo();
            
            // UPDATE PAGINATION STUFF
            const nextPageElement = document.getElementById(`${this.idNamingSuffix}-next-page`);
            

            
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
    renderTable(){
        const tableBodyE= this.tableBodyElement;
         
        if (!tableBodyE) {
            console.error('Table body element is not provided.');
            return;
        }
        tableBodyE.innerHTML = '';

        //creating the table head
        const header = document.createElement('thead');
        const hrow = document.createElement('tr'); 
        
       //fill table head
        tableBodyE.appendChild(header);
        header.appendChild(hrow);

        this.columnName.forEach(column => {
            const hdata = document.createElement('th');
            hdata.innerHTML = column +'<span class="sort-icon">↕</span>';
            hrow.appendChild(hdata);
        });

        //create and fill table body
        const tbody = document.createElement('tbody');
        tableBodyE.appendChild(tbody);

        this.data.forEach(data =>{
            const brow = document.createElement('tr');
            tbody.appendChild(brow);
            this.columnName.forEach( bcolumn => {           //for(int i=0 ; i < columnName.length; i++)
                const bdata = document.createElement('td');
                bdata.textContent = data[bcolumn] ;
                
                if (data[bcolumn] === undefined) {
                    bdata.textContent= 'not found'; bdata.style.color = 'orange';
                }else if (data[bcolumn] === null) {
                    bdata.textContent= '-'; bdata.style.color = 'orange';
                }
                brow.appendChild(bdata);
            })
        })

        
        
        
    }//renderTable 
    updatePaginationInfo(){
        const paginationInfo = document.getElementById(`${this.idNamingSuffix}-pagination-info`);
        const startItem = this.pageOffset + 1;
        const endItem = Math.min(this.pageOffset + this.rowsPerPage, this.totalRecords);
        if (!paginationInfo) {
            console.error('Pagination elements not found. Make sure they have the correct IDs.');
            return;
            
        } else {
            paginationInfo.innerHTML = `Showing <span>${startItem}
                </span> to <span >${endItem}
                </span> of <span>${this.totalRecords}</span> items`;
        }
    }
    paginationControls(){

        const nextPageElement = document.getElementById(`${this.idNamingSuffix}-next-page`);
        const prevPageElement = document.getElementById(`${this.idNamingSuffix}-prev-page`);
        const currentPageElement = document.getElementById(`${this.idNamingSuffix}-current-page`);
        
        
        if (!nextPageElement || !prevPageElement || !currentPageElement) {
            console.error('Pagination elements not found. Make sure they have the correct IDs.');
            return;
        }

        currentPageElement.textContent = this.currentPage;
        


        prevPageElement.disabled = this.currentPage <= 1;  
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
                
                // Update buttons state
                prevPageElement.disabled = this.currentPage <= 1;
                currentPageElement.textContent = this.currentPage;
            }
        });
        
        // Handle rows per page change
        if (this.rowsPerPageElement) {
            this.rowsPerPageElement.addEventListener('change', (event) => {
                this.rowsPerPage = parseInt(event.target.value);
                this.currentPage = 1; // Reset to first page when changing rows per page
                this.fetchData();
                currentPageElement.textContent = this.currentPage;
                prevPageElement.disabled = true; // Disable prev button when on page 1
            });
        }
    } //pagination
}//class