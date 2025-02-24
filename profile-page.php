<!DOCTYPE html>
<html lang="en">
<head>
  <title>Profile - TechSavvies</title>
  <?php require_once __DIR__ . '/assets/php/main.php'; ?>
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
    input[type="text"], input[type="email"], input[type="tel"] {
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
    }

    .add-button {
        background-color: #8d07cc;
        color: white;
    }

    .cancel-button {
        background-color: #000000;
        color: white;
        margin-left: 10px;
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

    .address-card {
        background-color: #f9f9f9;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 10px;
    }

    .address-card button {
        margin-left: 10px;
        padding: 5px 10px;
        cursor: pointer;
    }
  </style>
</head>
<body>
  <!-- Header Section -->
  <?php require_once __DIR__ . '/assets/php/header.php'; ?>

  <!-- Profile Content -->
  <div class="main-content">
    <h1>Profile</h1>
    <p>Welcome, [Username]! Here you can update your information, review your orders, and manage your account settings.</p>

    <form action="profile-page.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="" placeholder="Username" required>
        
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="" placeholder="First name" required>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="" placeholder="Last name">

        <br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="" placeholder="Email address" required>

        <br>

        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" value="" placeholder="Phone number" required>

        <br>

        <div class="address-section">
            <br><h2>Address Section</h2>
            <button class="add-button" onclick="openPopup()">Add</button>
            <div id="popup" class="popup" onclick="closePopup(event)">
              <div class="form-container" onclick="event.stopPropagation()">
                <h3>Add Address</h3>
                <form method="post" action="save_address.php">
                    <div class="form-row">
                        <label for="country">Country:</label>
                        <select name="country" id="country" required>
                            <option value="">Select a country</option>
                            <option value="Saudi Arabia">Saudi Arabia</option>
                            <option value="Kuwait">Kuwait</option>
                            <option value="United Arab Emirates">United Arab Emirates</option>
                            <option value="United Kingdom">United Kingdom</option>
                            <option value="United States">United States</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <label for="first_name">First Name:</label>
                        <input type="text" name="first_name" id="first_name" required>
                    </div>

                    <div class="form-row">
                        <label for="last_name">Last Name:</label>
                        <input type="text" name="last_name" id="last_name" required>
                    </div>

                    <div class="form-row">
                        <label for="address">Address:</label>
                        <input type="text" name="address" id="address" required>
                    </div>

                    <div class="form-row inline-fields">
                        <div>
                            <label for="city">City:</label>
                            <input type="text" name="city" id="city" required>
                        </div>
                        <div>
                            <label for="province">State/Province:</label>
                            <input type="text" name="province" id="province" required>
                        </div>
                        <div>
                            <label for="zip_code">Zip Code:</label>
                            <input type="text" name="zip_code" id="zip_code" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <label for="phone">Phone Number:</label>
                        <input type="text" name="phone" id="phone" required>
                    </div>

                    <div class="button-group">
                        <button type="button" class="cancel-button" onclick="closePopup()">Cancel</button>
                        <button type="submit" class="add-button">Save</button>
                    </div>
                </form>
              </div>
            </div>
        </div>

        <br>

        <!-- Displaying saved addresses -->
        <div class="saved-addresses">

        </div>

        <div class="button-group">
            <button type="submit" class="add-button">Save Profile</button>
            <button type="button" class="cancel-button" onclick="window.location.href='profile-page.php';">Cancel</button>
        </div>
    </form>
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