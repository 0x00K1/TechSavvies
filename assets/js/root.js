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
         })
         userTable.fetchData();


        /* const userTable = new tableFetcher({
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
        //userTable.renderTable(usersTableBody);*/
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
    }
    fetchData(){
        
        const pageOffset = ((this.currentPage-1)* this.rowsPerPage)>-1?(this.currentPage-1)* this.rowsPerPage : 0 ; // make sure its positive
        fetch(`${this.url}?rowNumber=${this.rowsPerPage}&rowOffset=${pageOffset}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
           // console.log('Data from PHP:', data); //uncomment for console output of the data
            this.data = data;
            this.renderTable();
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

        
        /******************************************************************
                                event listeners
    ******************************************************************/
        const nextPageElement = document.getElementById((this.idNamingSuffix+'-next-page'));
        const prevPageElement = document.getElementById((this.idNamingSuffix+'-prev-page'));
        const currentPageElement = document.getElementById((this.idNamingSuffix+'-current-page')); 
        // console.log(nextPageElement+' '+prevPageElement+' '+currentPageElement+' '+this.idNamingSuffix+'-next-page');
        //console.log('rowDropdown element:', this.rowsPerPageElement);
        nextPageElement.addEventListener('click',(event) =>{
            console.log('this.currentPage'+this.currentPage);
            this.currentPage++;
            this.fetchData();
            // Enable prev button if not on the first page
            if (this.currentPage > 1) {
            prevPageElement.disabled = false;
            }
            // Disable next button if on the last page
            const totalPages = Math.ceil(this.totalRecords / this.rowsPerPage);
            if (this.currentPage >= totalPages) {
            nextPageElement.disabled = true;
            }
            
            currentPageElement.textContent = this.currentPage;
        })
        prevPageElement.addEventListener('click', () => {
            this.currentPage--;
            this.fetchData();
            // Enable next button (it might have been disabled)
            
              nextPageElement.disabled = false;
            
            // Disable prev button if on the first page
            if (this.currentPage <= 1) {
              prevPageElement.disabled = true;
            }
            
              currentPageElement.textContent = this.currentPage;
            
          });
        // console.log(this.rowsPerPageElement);
        this.rowsPerPageElement.addEventListener('input', (event)=>{
            const testElement = document.createElement('span');
            testElement.innerHTML='yay working go fuck yoursels!!!';
            currentPageElement.appendChild(testElement);
           // this.rowsPerPage = this.value;
            this.fetchData();
        })
        
    }//renderTable  
}//class