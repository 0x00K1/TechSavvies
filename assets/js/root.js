document.addEventListener('DOMContentLoaded', function() {
    /*buttons*/
    const managePro_button = document.getElementById('managePro_button');
    const Orders_button= document.getElementById('Orders_button');
    const transaction_button= document.getElementById('transactions_button');
    const review_button= document.getElementById('Reviews_button');

    const search_button= document.getElementById('search_button');

    /* div or displays*/
    const EditProduct = document.getElementById('EditProduct');
    const orders_display=document.getElementById('orders_display');
    const Transaction_display = document.getElementById('Transactions_display'); // New div for transactions
    const Reviews_display = document.getElementById('Reviews_Display'); // New div for reviews

    /*first time loading page */
    EditProduct.style.display = 'block';
    orders_display.style.display= 'none';
    Transaction_display.style.display = 'none'; // Initially hide
    Reviews_display.style.display = 'none'; // Initially hide
    managePro_button.classList.add('active');/*for button styles*/
    
    

    /* second on click some styling*/
    managePro_button.addEventListener('click', function() {
        EditProduct.style.display = 'block';
        orders_display.style.display= 'none';
        Transaction_display.style.display = 'none';
        Reviews_display.style.display = 'none';
        managePro_button.classList.add('active');
        Orders_button.classList.remove('active');
        transaction_button.classList.remove('active');
        review_button.classList.remove('active');
    });


    Orders_button.addEventListener('click',function(){
        EditProduct.style.display = 'none';
        orders_display.style.display= 'block';
        Transaction_display.style.display = 'none';
        Reviews_display.style.display = 'none';
        managePro_button.classList.remove('active');
        Orders_button.classList.add('active');
        transaction_button.classList.remove('active');
        review_button.classList.remove('active');
    });
   
    transaction_button.addEventListener('click', function() {
        EditProduct.style.display = 'none';
        orders_display.style.display = 'none';
        Transaction_display.style.display = 'block'; // Show reviews div
        Reviews_display.style.display = 'none'; // Hide transactions div
        managePro_button.classList.remove('active');
        Orders_button.classList.remove('active');
        transaction_button.classList.add('active');
        review_button.classList.remove('active');
    });

    review_button.addEventListener('click', function() {
        EditProduct.style.display = 'none';
        orders_display.style.display = 'none';
        Transaction_display.style.display = 'none'; // Hide reviews div
        Reviews_display.style.display = 'block'; // Show transactions div
        managePro_button.classList.remove('active');
        Orders_button.classList.remove('active');
        transaction_button.classList.remove('active');
        review_button.classList.add('active');
    });

    search_button.addEventListener('click', function(){
        /*Retreives user data from DATABASe and fills it into the tabel*/
    });



});


function addProPopup(){
    document.getElementById("addProPopup_display").style.display = "block";
}

function closeaddProPopup() {
        document.getElementById("addProPopup_display").style.display = "none";
}

function confirmationPopup(){
    document.getElementById("confirmationPopup_display").style.display= "block";
}
function closeconfirmationPopup(){
    document.getElementById("confirmationPopup_display").style.display= "none";
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