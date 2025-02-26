document.addEventListener('DOMContentLoaded', function() {
    /*buttons*/
    const EditPro_button = document.getElementById('EditPro_button');
    const EditUser_button = document.getElementById('EditUser_button');
    const search_button= document.getElementById('search_button')
    /* div or displays*/
    const EditProduct = document.getElementById('EditProduct');
    const EditUser = document.getElementById('EditUser');
    
    /* forms*/
    const addProduct_form =document.getElementById('addProduct_form');
    const editUser_form =document.getElementById('editUser_form');
    const viewUser_form= document.getElementById('viewUser_form');

    /*first time loading page */
    EditProduct.style.display = 'block';
    EditPro_button.classList.add('active');/*for button styles*/
    
    
    /*diale the other form*/
    disableForm(editUser_form);
    disableForm(viewUser_form);
    /* second on clicksome styling*/
    EditPro_button.addEventListener('click', function() {
        EditProduct.style.display = 'block';
        EditUser.style.display = 'none';
        EditPro_button.classList.add('active');
        EditUser_button.classList.remove('active');
        
    });

    EditUser_button.addEventListener('click', function() {
        Product_queries.SubmitEvent.disable;
        EditProduct.style.display = 'none';
        EditUser.style.display = 'block';
        EditPro_button.classList.remove('active');
        EditUser_button.classList.add('active');

    });

   

    search_button.addEventListener('click', function(){
        /*Retreives user data from DATABASe and fills it into the fields*/
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

function openPopup() {
        document.getElementById("myPopup").style.display = "block";

}

function closePopup() {
        document.getElementById("myPopup").style.display = "none";
}