document.addEventListener('DOMContentLoaded', function() {
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
            if(display) display.style.display = 'none';
        });
    }

    // Function to remove active class from all buttons (toolbar)
    function removeAllActiveClasses() {
        const buttons = [manageProbutton, manageUserbutton, Ordersbutton, transactionbutton, reviewbutton];
        buttons.forEach(button => {
            if(button) button.classList.remove('active');
        });
    }

    // Function to set active tab (toolbar)
    function setActiveTab(displayElement, activeButton) {
        hideAllDisplays();
        removeAllActiveClasses();
        if(displayElement) displayElement.style.display = 'block';
        if(activeButton) activeButton.classList.add('active');
    }

    /* first time loading page  (toolbar)*/ 
    setActiveTab(EditProduct, manageProbutton);

    // calling the table render and fetching and tab switch (toolbar)   [TRFS]*
    manageProbutton.addEventListener('click', function() {
        setActiveTab(EditProduct, manageProbutton);//tab switch(toolbar)
        const productTable = new fetchTable({  // generating the object
            url : '../../api/users/list.php',
            tableName : 'products', //must be like sql100%
            columnName : [`product_id`,`category_id`,`product_name`,`picture`,`description`,`color`,`size`,`price`,
                `stock`,`created_at`,`updated_at`,`created_by`],  //must be like sql100%
            rowsPerPage :3,
            idNamingSuffix :'products',    // to locate the next prev current page ids following the standard suffix-next-page so on
         });
         productTable.fetchData();  //calling the fetch methode
    });

    // calling the table render and fetching and tab switch(toolbar) [TRFS]&
    manageUserbutton.addEventListener('click', function() {
        setActiveTab(usersdisplay, manageUserbutton);//tab switch(toolbar)
        const userTable = new fetchTable({  // generating the object
           url : '../../api/users/list.php',
           tableName : 'customers', //must be like sql100%
           columnName : ['email','customer_id','username','created_at','iamfakeandbad'],  //must be like sql100%
           rowsPerPage :3,
           idNamingSuffix :'users',    // to locate the next prev current page ids following the standard suffix-next-page so on
        });
        userTable.fetchData();  //calling the fetch methode

    });
    
    // calling the table render and fetching and tab switch(toolbar) [TRFS]&
    Ordersbutton.addEventListener('click', function() {
        setActiveTab(ordersdisplay, Ordersbutton);
        const orderTable = new fetchTable({
           url : '../../api/users/list.php',
           tableName : 'orders',
           columnName : ['order_id','customer_id','status','total_amount','order_date'],
           rowsPerPage :19,
           idNamingSuffix :'orders',    // to locate the next prev current page ids following the standard suffix-next-page so on
        });
        orderTable.fetchData();
       
    });
    
    // calling the table render and fetching and tab switch(toolbar) [TRFS]&
    transactionbutton.addEventListener('click', function() {
        setActiveTab(Transactiondisplay, transactionbutton);
        const transactionTable = new fetchTable({
            url : '../../api/users/list.php',
            tableName : 'payments',
            columnName : [`payment_id`,`order_id`,`customer_id`,`payment_method`,`payment_status`,`transaction_id`,`amount`,`created_at`],
            rowsPerPage :19,
            idNamingSuffix :'transactions',    // to locate the next prev current page ids following the standard suffix-next-page so on
         });
         transactionTable.fetchData();
    });
    
    // calling the table render and fetching and tab switch(toolbar) [TRFS]&
    reviewbutton.addEventListener('click', function() {
        setActiveTab(Reviewsdisplay, reviewbutton);
        const reviewTable = new fetchTable({
            url : '../../api/users/list.php',
            tableName : 'reviews',
            columnName : [`review_id`,`customer_id`,`product_id`,`rating`,`review_text`,`created_at`],
            rowsPerPage :19,
            idNamingSuffix :'reviews',    // to locate the next prev current page ids following the standard suffix-next-page so on
         });
         reviewTable.fetchData();
    });
    

    //search functionality
  //  searchbutton.addEventListener('click', function() {    
   // });
    //LOGOUT   button
    logoutbutton.addEventListener('click', function() {
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

window.product_editbutton = function() {
   document.getElementById('productEditDisplay').style.display = "block";
   document.getElementById('buttonsTableDisplay').style.display = "none";
};

window.product_cancel_edit = function() {
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
function closeaddProPopup(){
   document.getElementById("addProPopupDisplay").style.display = "none";
}
addProductButton.addEventListener('click', addProPopup);
closeProductPopUpButton.addEventListener('click',closeaddProPopup);

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
       this.tableName = options.tableName;   // name in DB
       this.columnName = options.columnName || []; // name in DB
       this.currentPage = options.currentPage || 1;
       this.rowsPerPage = options.rowsPerPage || 100;
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
   renderTable(){
       const display = document.getElementById(`${this.idNamingSuffix}TableDisplay`);
       const tableElement= document.createElement('table');
       display.innerHTML='';
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
           hdata.innerHTML = column +`<span class="sort-icon" data-sort="${column}">↕</span>`;
           hrow.appendChild(hdata);
       });

       //create and fill table body
       const tbody = document.createElement('tbody');
       tableElement.appendChild(tbody);

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
   paginationControls(){

       const nextPageElement = document.getElementById(`${this.idNamingSuffix}NextPage`);
       const prevPageElement = document.getElementById(`${this.idNamingSuffix}PrevPage`);
       const currentPageElement = document.getElementById(`${this.idNamingSuffix}CurrentPage`);
       
       
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
       nextPageElement.disabled = this.currentPage <= 1;
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
       const rowsPerPage = document.getElementById(`${this.idNamingSuffix}RowsPerPage`);
       console.log(`${this.idNamingSuffix}RowsPerPage`);
           rowsPerPage.addEventListener('change', (event) =>{ // Use an arrow function here
               this.rowsPerPage = parseInt(rowsPerPage.value); // Get the value and parse it as an integer
               this.currentPage = 1; // Reset to the first page when rows per page changes
               this.fetchData(); // Fetch data with the new rows per page
   
               // Update pagination elements (you might want to move this to a separate function if it gets complex)
               const currentPageElement = document.getElementById(`${this.idNamingSuffix}CurrentPage`);
               if(currentPageElement) currentPageElement.textContent = this.currentPage;
               const prevPageElement = document.getElementById(`${this.idNamingSuffix}PrevPage`);
               if(prevPageElement) prevPageElement.disabled = true;
               this.flagPage = true; // Reset the flag so total records are fetched again
           });
   } //paginationControls


}//class