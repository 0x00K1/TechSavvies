document.addEventListener('DOMContentLoaded', function() {
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
    });
});