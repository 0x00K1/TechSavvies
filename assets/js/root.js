document.addEventListener('DOMContentLoaded', function() {
    /*buttons*/
    const managePro_button = document.getElementById('managePro_button');
    const manageUser_button = document.getElementById('manageUser_button');
    const Orders_button= document.getElementById('Orders_button');
    const search_button= document.getElementById('search_button');
    /* div or displays*/
    const EditProduct = document.getElementById('EditProduct');
    const EditUser = document.getElementById('EditUser');
    const orders_display=document.getElementById('orders_display');
    /* forms*/
    const addProduct_form =document.getElementById('addProduct_form');
    const editUser_form =document.getElementById('editUser_form');
    const viewUser_form= document.getElementById('viewUser_form');

    /*first time loading page */
    EditProduct.style.display = 'block';
    EditUser.style.display = 'none';
    orders_display.style.display= 'none';
    managePro_button.classList.add('active');/*for button styles*/
    
    
    
    /*diale the other form*/
    disableForm(editUser_form);
    disableForm(viewUser_form);
    disableForm(addProduct_form);
    /* second on click some styling*/
    managePro_button.addEventListener('click', function() {
        EditProduct.style.display = 'block';
        EditUser.style.display = 'none';
        orders_display.style.display= 'none';
        managePro_button.classList.add('active');
        manageUser_button.classList.remove('active');
        Orders_button.classList.remove('active'); 
    });

    manageUser_button.addEventListener('click', function() {
        EditProduct.style.display = 'none';
        EditUser.style.display = 'block';
        orders_display.style.display= 'none';
        managePro_button.classList.remove('active');
        manageUser_button.classList.add('active');
        Orders_button.classList.remove('active'); 
    });

    Orders_button.addEventListener('click',function(){
        EditProduct.style.display = 'none';
        EditUser.style.display = 'none';
        orders_display.style.display= 'block';
        managePro_button.classList.remove('active');
        manageUser_button.classList.remove('active');
        Orders_button.classList.add('active');
    });
   

    search_button.addEventListener('click', function(){
        /*Retreives user data from DATABASe and fills it into the tabel*/
    })



});
function disableForm(formToDiable) {
    const form = document.getElementById(formToDiable); // Replace with your form's ID

    if (form) {
        for (let i = 0; i < form.elements.length; i++) {
            form.elements[i].disabled = true;
        }
    }
}
function enableForm(formToEnable) {
    const form = document.getElementById(formToEnable); // Replace with your form's ID

    if (form) {
        for (let i = 0; i < form.elements.length; i++) {
            form.elements[i].disabled = false;
        }
    }
}

function addProPopup() {
        document.getElementById("addProPopup_display").style.display = "block";
}

function closeaddProPopup() {
        document.getElementById("addProPopup_display").style.display = "none";
}

function confirmationPopup(){
    document.getElementById("confirm_input").style.display= "block";
}
function closeconfirmationPopup(){
    document.getElementById("confirm_input").style.display= "none";
}