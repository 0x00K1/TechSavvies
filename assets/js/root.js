document.addEventListener('DOMContentLoaded', function() {
    /*buttons*/
    const EditPro_button = document.getElementById('EditPro_button');
    const EditUser_button = document.getElementById('EditUser_button');
    const search_button= document.getElementById('search_button')
    /* div or displays*/
    const EditProduct = document.getElementById('EditProduct');
    const EditUser = document.getElementById('EditUser');
    
    /* forms*/
    const addProduct_form=document.getElementById('addProduct_form');
    const 
    /*first time loading page */
    EditProduct.style.display = 'block';
    EditPro_button.classList.add('active');
    
    /*on click*/
    /*1st diale the other form*/
    EditPro_button.addEventListener('click', disableForm(Product_queries));
    /*second some styling*/
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

function openPopup() {
        document.getElementById("myPopup").style.display = "block";

}

function closePopup() {
        document.getElementById("myPopup").style.display = "none";
}