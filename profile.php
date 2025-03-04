<!DOCTYPE html>
<html lang="en">
<head>
  <?php require_once __DIR__ . '/assets/php/main.php'; ?>
  <title>Profile</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/main.css" />
  <style>
    body {
        font-family: Arial, sans-serif;
    }

    form {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-top: 10px;
    }

    input[type="text"], input[type="email"], input[type="tel"], input[type="password"] {
        width: 25%;
        padding: 8px;
        margin-top: 5px;
    }

    .address-section {
        background: white;
        border-radius: 8px;
    }

    .add-button, .cancel-button {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-bottom: 20px;
        display: block;
        margin: 20 auto;
    }

    .add-button {
        background: linear-gradient(135deg, #d42d2d, #8d07cc, #0117ff);
        color: white;
        display: block;
        margin: 20px auto; /* Centers the button horizontally */
    }

    .cancel-button {
        background-color: #000000;
        color: white;
        display: block;
        margin: 20px auto; /* Centers the button horizontally */
    }
    
    .popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .form-container {
        background: white;
        padding: 20px;
        border-radius: 8px;
        width: 400px;
        max-width: 90%;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .form-row {
        margin-bottom: 15px;
    }

    .form-row label {
        display: block;
        margin-bottom: 5px;
    }

    .form-row input, .form-row select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .inline-fields {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .profile {
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgb(0, 0, 0);
        width: 100%;
        max-width: 600px;
        text-align: center;
        margin-bottom: 10%;
        margin: 0 auto;
    }

    .profile h1 {
        font-size: 24px;
        margin-bottom: 10px;
    }

    .profile form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .profile input {
        width: 90%;
        padding: 10px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .profile button {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 10px;
    }

    .address-section {
    background: #fff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgb(0, 0, 0);
    width: 100%;
    max-width: 600px;
    margin-top: 20px;
    margin: 0 auto;
    text-align: center;
}

    .address-section h2 {
    font-size: 20px;
    text-align: center;
    margin-bottom: 20px;
    }

    .address-card {
    background: #f9f9f9;
    padding: 15px;
    border-radius: 5px;
    border: 1px solid #ccc;
    width: 100%;
    text-align: center;
    margin-bottom: 10px;
    margin: 0 auto;
}

    .address-card button {
        margin-left: 10px;
        padding: 5px 10px;
        cursor: pointer;
    }

    .saved-addresses {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
  </style>
</head>

<body>
  <!-- Header Section -->
  <?php require_once __DIR__ . '/assets/php/header.php'; ?>

  <!-- Profile Content -->
  <div class="main-content">
    <div class="profile">
    <h1>Profile</h1>
    <form>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Username" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Email" required>
        
        <br><br>

        <button type="submit" class="add-button">Update Profile</button>
        <button type="button" class="cancel-button" onclick="window.location.href='/';">Cancel</button>
    </form>
    </div>

    <br><br>

        <div class="address-section">
            <br><h2>Addresses</h2>
            
            <button class="add-button" onclick="openPopup()">Add</button>
            <div id="popup" class="popup" onclick="closePopup(event)">
              <div class="form-container" onclick="event.stopPropagation()">

                <h3>Add Address</h3>
                <form>
                    <input type="hidden" name="update_address">

                    <div class="form-row">
                    <label for="country">Country:</label>
                    <input type="text" id="country" name="country" placeholder="Country" required>
                    </div>

                    <div class="form-row">
                    <label for="address_line1">Address Line 1:</label>
                    <input type="text" id="address_line1" name="address_line1" placeholder="Address Line 1" required>
                    </div>

                    <div class="form-row">
                    <label for="address_line2">Address Line 2:</label>
                    <input type="text" id="address_line2" name="address_line2" placeholder="Address Line 2">
                    </div>

                    <div class="form-row inline-fields">
                        <div>
                        <label for="city">City:</label>
                        <input type="text" id="city" name="city" placeholder="City" required>
                        </div>

                        <div>
                        <label for="state">State/Province:</label>
                        <input type="text" id="state" name="state" placeholder="State/Province" required>
                        </div>

                        <div>
                        <label for="postal_code">Postal Code:</label>
                        <input type="text" id="postal_code" name="postal_code" placeholder="Postal Code" required>
        
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="add-button">Save</button>
                        <button type="button" class="cancel-button" onclick="closePopup()">Cancel</button>
                    </div>
                </form>
              </div>
            </div>
        </div>
        </div>

        <br>

        <!-- Displaying saved addresses -->
        <div class="saved-addresses">
    <?php if (!empty($addresses)): ?>
        <?php foreach ($addresses as $address): ?>
            <div class="address-card">
                <p><strong><?php echo htmlspecialchars($address['address_line1']); ?></strong></p>
                <p><?php echo htmlspecialchars($address['address_line2']); ?></p>
                <p><?php echo htmlspecialchars($address['city'] . ', ' . $address['state'] . ' ' . $address['postal_code'] . ', ' . $address['country']); ?></p>
                <button onclick="editAddress(<?php echo $address['address_id']; ?>)">Edit</button>
                <button onclick="deleteAddress(<?php echo $address['address_id']; ?>)">Delete</button>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No saved addresses.</p>
    <?php endif; ?>
</div>
    

  <!-- Authentication Modal -->
  <?php require_once __DIR__ . '/assets/php/auth.php'; ?>

  <script src="assets/js/main.js"></script>
  <script>

    function openPopup(){
      document.getElementById('popup').style.display = 'flex';
    }

    function closePopup(event){
        if (event) {
            event.preventDefault();
        }
        document.getElementById('popup').style.display = 'none';
    }

    function editAddress(id) {
        // Redirect to edit address page (You can create a separate page for editing)
        window.location.href = 'edit_address.php?id=' + id;
    }

    function deleteAddress(id) {
        // Make a delete request to delete the address
        if(confirm("Are you sure you want to delete this address?")) {
            window.location.href = 'delete_address.php?id=' + id;
        }
    }

  </script>

</body>
</html>
