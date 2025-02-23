document.addEventListener('DOMContentLoaded', function() {
    const EditPro_button = document.getElementById('EditPro_button');
    const EditUser_button = document.getElementById('EditUser_button');
    const Queries_button = document.getElementById('Queries_button');

    const EditProduct = document.getElementById('EditProduct');
    const EditUser = document.getElementById('EditUser');
    const Queries = document.getElementById('Queries');
    /*first time loading page */
    EditProduct.style.display = 'block';
    EditPro_button.classList.add('active');
    
    /*on click*/
    EditPro_button.addEventListener('click', function() {
        EditProduct.style.display = 'block';
        EditUser.style.display = 'none';
        Queries.style.display = 'none';
        EditPro_button.classList.add('active');
        EditUser_button.classList.remove('active');
        Queries_button.classList.remove('active');
    });

    EditUser_button.addEventListener('click', function() {
        EditProduct.style.display = 'none';
        EditUser.style.display = 'block';
        Queries.style.display = 'none';
        EditPro_button.classList.remove('active');
        EditUser_button.classList.add('active');
        Queries_button.classList.remove('active');
    });

    Queries_button.addEventListener('click', function() {
        EditProduct.style.display = 'none';
        EditUser.style.display = 'none';
        Queries.style.display = 'block';
        EditPro_button.classList.remove('active');
        EditUser_button.classList.remove('active');
        Queries_button.classList.add('active');
    });

    User_Query_Button.addEventListener('click', function(){
        /*Retreives user data from DATABASe and fills it into the fields*/
    })
});