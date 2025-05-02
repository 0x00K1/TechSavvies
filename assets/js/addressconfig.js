// Show/hide popup functions
function openAddPopup() {
    document.getElementById('add-popup').style.display = 'flex';
}

function closePopup(popupId, event) {
    if (event && event.target !== event.currentTarget) {
        return;
    }
    document.getElementById(popupId).style.display = 'none';
}

// Address management functions
function editAddress(id) {
    // Fetch address data via AJAX
    fetch(`/includes/config_address.php?id=${id}`, {
        credentials: 'include',  // or 'same-origin' if same-domain
        headers: {
        'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Populate form fields
                const address = data.address;
                document.getElementById('edit_address_id').value = address.address_id;
                document.getElementById('edit_country').value = address.country;
                document.getElementById('edit_address_line1').value = address.address_line1;
                document.getElementById('edit_address_line2').value = address.address_line2 || '';
                document.getElementById('edit_city').value = address.city;
                document.getElementById('edit_state').value = address.state;
                document.getElementById('edit_postal_code').value = address.postal_code;
                document.getElementById('edit_is_primary').checked = address.is_primary == 1;
                
                // Display the popup
                document.getElementById('edit-popup').style.display = 'flex';
            } else {
                showDialog(data.message || 'Failed to load address data.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showDialog('An error occurred while fetching the address data.');
        });
}

function deleteAddress(id) {
    if (!confirm('Are you sure you want to delete this address?')) return;

    const formBody = new URLSearchParams();
    formBody.append('id', id);

    fetch('/includes/config_address.php', {
        method: 'POST',
        credentials: 'include',          // important for same-domain session cookies
        headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest'
        },
        body: formBody.toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
        showDialog(data.message);
        setTimeout(() => window.location.reload(), 1500);
        } else {
        showDialog(data.message || 'Failed to delete address.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showDialog('An error occurred while deleting the address.');
    });
    }

function setPrimaryAddress(id) {
    // Send request to set this address as primary
    const formBody = new URLSearchParams();
    formBody.append('action', 'set_primary');
    formBody.append('address_id', id);
    
    fetch('/includes/config_address.php', {
        method: 'POST',
        credentials: 'include',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formBody.toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showDialog(data.message);
            // Reload the page to show changes
            setTimeout(() => { window.location.reload(); }, 1500);
        } else {
            showDialog(data.message || 'Failed to set address as primary.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showDialog('An error occurred while updating the address.');
    });
}

function validateAddressForm(formId) {
    const form = document.getElementById(formId);
    const fields = {
        country: 2,
        address_line1: 5,
        city: 2,
        state: 2,
        postal_code: 4
    };

    let isValid = true;
    let errorMsg = '';

    for (const [fieldId, minLength] of Object.entries(fields)) {
        const input = form.querySelector(`[name="${fieldId}"]`);
        if (input) {
            const value = input.value.trim();
            if (value.length < minLength) {
                input.classList.add('input-error');
                errorMsg += `- ${fieldId.replace('_', ' ')} must be at least ${minLength} characters long.\n`;
                isValid = false;
            } else {
                input.classList.remove('input-error');
            }
        }
    }

    if (!isValid) {
        showDialog("Please correct the following:\n" + errorMsg);
    }

    function showDialog(message) {
        document.getElementById('dialogMessage').innerText = message;
        document.getElementById('dialogOverlay').style.display = 'flex';
    }
      
    return isValid;
}