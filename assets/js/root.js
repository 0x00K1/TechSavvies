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
     search_button.addEventListener('click', function() {
         //nothing for now
     });
 
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