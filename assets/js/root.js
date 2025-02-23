document.addEventListener('DOMContentLoaded', function() {
<<<<<<< HEAD
    const EditPro_button = document.getElementById('EditPro_button');
    const EditUser_button = document.getElementById('EditUser_button');
    const Queries_button = document.getElementById('Queries_button');

    const EditProduct = document.getElementById('EditProduct');
    const EditUser = document.getElementById('EditUser');
    const Queries = document.getElementById('Queries');

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
=======
    const addButton = document.getElementById('BAdd');
    const removeButton = document.getElementById('BRemove');
    const editButton = document.getElementById('BEdit');

    const addContent = document.getElementById('addContent');
    const removeContent = document.getElementById('removeContent');
    const editContent = document.getElementById('editContent');

    addButton.addEventListener('click', function() {
        addContent.style.display = 'block';
        removeContent.style.display = 'none';
        editContent.style.display = 'none';
    });

    removeButton.addEventListener('click', function() {
        addContent.style.display = 'none';
        removeContent.style.display = 'block';
        editContent.style.display = 'none';
    });

    editButton.addEventListener('click', function() {
        addContent.style.display = 'none';
        removeContent.style.display = 'none';
        editContent.style.display = 'block';
>>>>>>> 6ca0705560e707a812b2f5d2d3f6394ed6314e41
    });
});