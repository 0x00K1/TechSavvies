// JavaScript code to handle the form submission for adding a new product
document.getElementById('addProductForm').addEventListener('submit', function (e) {
    e.preventDefault(); // prevent normal form submit

    const form = e.target;
    const formData = new FormData(form);

    fetch('add_product_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'success') {
            alert(result.message);
            form.reset(); // clear form after success
            document.getElementById('addProPopupDisplay').style.display = 'none'; // hide popup
            location.reload(); // optional: reload products list
        } else {
            alert('Error: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('Unexpected error.');
    });
});