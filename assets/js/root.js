document.addEventListener('DOMContentLoaded', function() {
     /*buttons*/
     const manageProbutton = document.getElementById('manageProbutton');
     const manageUserbutton = document.getElementById('manageUserbutton');
     const Ordersbutton = document.getElementById('Ordersbutton');
     const transactionbutton = document.getElementById('transactionsbutton');
     const reviewbutton = document.getElementById('Reviewsbutton');
     const logoutbutton = document.getElementById('Logoutbutton');
     const searchbutton = document.getElementById('searchbutton');
     
     /* div or displays*/
     const EditProduct = document.getElementById('EditProduct');
     const usersdisplay = document.getElementById('usersdisplay');
     const ordersdisplay = document.getElementById('ordersdisplay');
     const Transactiondisplay = document.getElementById('Transactionsdisplay');
     const Reviewsdisplay = document.getElementById('Reviewsdisplay'); 
 
     // Function to hide all displays
     function hideAllDisplays() {
         const displays = [EditProduct, usersdisplay, ordersdisplay, Transactiondisplay, Reviewsdisplay];
         displays.forEach(display => {
             if(display) display.style.display = 'none';
         });
     }
 
     // Function to remove active class from all buttons
     function removeAllActiveClasses() {
         const buttons = [manageProbutton, manageUserbutton, Ordersbutton, transactionbutton, reviewbutton];
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
     setActiveTab(EditProduct, manageProbutton);
 
     /* Event Listeners for tab switching */
     manageProbutton.addEventListener('click', function() {
         setActiveTab(EditProduct, manageProbutton);
     });
 
     // Implementation for manageUserbutton
     manageUserbutton.addEventListener('click', function() {
         setActiveTab(usersdisplay, manageUserbutton);
         const userTable = new fetchTable({
            url : '../../api/users/list.php',
            tableBodyElement : document.getElementById('users-table'), 
            tableName : 'customers',
            columnName : ['email','customer-id','username','created-at','iamfakeandbad'],
            rowsPerPage :3,
            idNamingSuffix :'users',    // to locate the next prev current page ids following the standard suffix-next-page so on
         });
         userTable.fetchData();

     });
 
     Ordersbutton.addEventListener('click', function() {
         setActiveTab(ordersdisplay, Ordersbutton);
         const userTable = new fetchTable({
            url : '../../api/users/list.php',
            tableBodyElement : document.getElementById('orders-table'), 
            tableName : 'orders',
            columnName : ['order-id','customer-id','status','total-amount','orderdate'],
            rowsPerPage :3,
            idNamingSuffix :'orders',    // to locate the next prev current page ids following the standard suffix-next-page so on
         });
         userTable.fetchData();
        
     });
 
     transactionbutton.addEventListener('click', function() {
         setActiveTab(Transactiondisplay, transactionbutton);
     });
 
     reviewbutton.addEventListener('click', function() {
         setActiveTab(Reviewsdisplay, reviewbutton);
     });
 
     //search functionality
   //  searchbutton.addEventListener('click', function() {    
    // });
 
     logoutbutton.addEventListener('click', function() {
         const confirmLogout = confirm("Are you sure you want to log out?");
         if (confirmLogout) {
             window.location.href = "/includes/cls.php";
         }
     });
});

function closeaddProPopup() {
    document.getElementById("addProPopupdisplay").style.display = "none";
}

function confirmationPopup() {
    document.getElementById("confirmationPopupdisplay").style.display = "block";
}

function closeconfirmationPopup() {
    document.getElementById("confirmationPopupdisplay").style.display = "none";
}

// Define these functions in the global scope
window.product_editbutton = function() {
    document.getElementById('product-editdisplay').style.display = "block";
    document.getElementById('buttons-tabledisplay').style.display = "none";
};

window.product_cancel_edit = function() {
    document.getElementById('product-editdisplay').style.display = "none";
    document.getElementById('buttons-tabledisplay').style.display = "block";
};

// Make sure the buttons have the correct onclick handlers after the page loads
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('edit-productbutton')) {
        document.getElementById('edit-productbutton').onclick = window.product-editbutton;
    }

    if (document.getElementById('product-cancel-edit')) {
        document.getElementById('product-cancel-edit').onclick = window.product-cancel-edit;
    }
});

/*-------------------------------------------------------------------------------------------------------

#######################################---Global table retriver---#######################################


------------------------------------------------Usage----------------------------------------------------
Option	            Type	        Default Value	            Description
connectionType  	string	        'local'	                        Specifies the type of data source. Possible values: 'local', 'mysql', or 'api'. This currently only serves as an indicator; you'll need to implement the actual fetching logic in WorkspaceData().
tableName	        string	        ''	                            The name of the table in your database or the endpoint of your API. Used in the WorkspaceData() method (you need to implement the fetching logic).
columnNames     	array	        []	                            An array of strings representing the names of the columns to be displayed in the table. The order in this array determines the order of the columns in the rendered table.
currentPage	        number	        1	                            The initial page number to display.
rowsPerPage	        number	        100	                            The number of rows to display on each page.
sortColumn	        string	        First                           column name in columnNames (or null if columnNames is empty)	The name of the column to sort the table by initially.
sortDirection	    string	        'asc'	                        The initial sorting direction. Possible values: 'asc' (ascending) or 'desc' (descending).


---------------------------------------------------------------------------------------------------------*/

class fetchTable{
    
    constructor (options){
        this.url = options.url;
        this.tableBodyElement = options.tableBodyElement ; 
        this.tableName = options.tableName;   // name in DB
        this.columnName = options.columnName || []; // name in DB
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
        fetch(`${this.url}?tableName=${this.tableName}&rowNumber=${this.rowsPerPage}&rowOffset=${this.pageOffset}`)
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
        const display = document.getElementById(`${this.idNamingSuffix}-table-display`);
        const tableBodyE= this.tableBodyElement;

        display.appendChild(tableContainer);
        tableContainer.setAttribute(`class="table-container" ${this.idNamingSuffix}-table-container`) ;
        tableContainer.appendChild(tableBodyE);
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
            console.error('Paginationinfo not found. Make sure they have the correct IDs.');
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
            if(!nextPageElement)
                console.error('nextPageElement not found. Make sure they have the correct IDs.');
            if(!prevPageElement)
                console.error('prevPageElement not found. Make sure they have the correct IDs.');
            if(!currentPageElement)
                console.error('currentPageElement not found. Make sure they have the correct IDs.');
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
        const rowsPerPage = document.getElementById(`${this.idNamingSuffix}-rows-per-page`);
            rowsPerPage.addEventListener('change', (event) =>{ // Use an arrow function here
                this.rowsPerPage = parseInt(rowsPerPage.value); // Get the value and parse it as an integer
                this.currentPage = 1; // Reset to the first page when rows per page changes
                this.fetchData(); // Fetch data with the new rows per page
    
                // Update pagination elements (you might want to move this to a separate function if it gets complex)
                const currentPageElement = document.getElementById(`${this.idNamingSuffix}-current-page`);
                if(currentPageElement) currentPageElement.textContent = this.currentPage;
                const prevPageElement = document.getElementById(`${this.idNamingSuffix}-prev-page`);
                if(prevPageElement) prevPageElement.disabled = true;
                this.flagPage = true; // Reset the flag so total records are fetched again
            });
    } //paginationControls


}//class