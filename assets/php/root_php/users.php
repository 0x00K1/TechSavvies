<div id="EditUser" class="content" >
            <form name="viewUser_form" id="viewUser_form">
                <div class="EditUser_bar">
                    <button type="button" id="Add_user">Add</button>
                    <button type="button" id="Edit_User">Edit</button>
                </div>
                <div class="EditUser">
                    <p>
                        <label for="User_ID">User ID:</label>
                        <input type="text" id="User_ID" required>
                    </p>
                    <button class="search-button" id="User_Query_Button">Search</button>
                    <p>
                        <label for="Username">Username:</label>
                        <input type="text" id="Username" required>
                    </p>
                    <p>
                        <label for="User_Email">Email:</label>
                        <input type="email" id="User_Email" required>
                    </p>
                    <p>
                        <label for="User_Role">Role:</label>
                        <select id="User_Role" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </p>
                </div>
        </div>