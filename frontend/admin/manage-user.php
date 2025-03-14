<?php
$connection = mysqli_connect('localhost', 'root', '', 'online_job_portal');
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$selectquery = 'SELECT * FROM users';
$result = mysqli_query($connection, $selectquery);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

$users = [];
if (mysqli_num_rows($result) == 0) {
    echo "No records found.";
} else {
    while ($user = mysqli_fetch_assoc($result)) {
        array_push($users, $user);
    }
}

mysqli_close($connection);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .delete-btn { background: red; color: white; border: none; padding: 5px 10px; cursor: pointer; }
    </style>
</head>

<body>

    <h2>List of Users</h2>
    <div class="search-bar">
        <input type="text" id="search-id" placeholder="Search by ID">
        <select id="search-role">
            <option value="">All Roles</option>
           
            <option value="company">Company</option>
            <option value="jobseeker">Jobseeker</option>
        </select>
        <button onclick="searchUsers()">Search</button>
    </div>
    <table id=user-data>
        <tr>
            <th>SN</th>
            <th>ID</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $index => $user) { ?>
        <tr id="user-<?php echo $user['id']; ?>">
            <td><?php echo $index + 1; ?></td>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['role']; ?></td>
            <td>
                <button class="delete-btn" onclick="confirmDelete(<?php echo $user['id']; ?>)">Delete</button>
            </td>
        </tr>
        <?php } ?>
    </table>

    <script>
        function confirmDelete(userId) {
            let userPassword = prompt("Enter User's Password to Confirm Deletion:");
            if (userPassword) {
                $.ajax({
                    url: "backend/manage_userBackend.php",
                    type: "POST",
                    data: { id: userId, password: userPassword },
                    success: function(response) {
                        if (response.trim() === "success") {
                            $("#user-" + userId).fadeOut();
                        } else {
                            alert(response);
                        }
                    },
                    error: function() {
                        alert("AJAX request failed.");
                    }
                });
            }
        }
        function searchUsers() {
            let userId = $("#search-id").val();
            let userRole = $("#search-role").val();

            $.ajax({
                url: "backend/search_user.php",
                type: "POST",
                data: { id: userId, role: userRole },
                dataType: "json",
                success: function(response) {
                    let rows = "";
                    if (response.length === 0) {
                        rows = "<tr><td colspan='5'>No records found.</td></tr>";
                    } else {
                        response.forEach((user, index) => {
                            rows += `
                                <tr id="user-${user.id}">
                                    <td>${index + 1}</td>
                                    <td>${user.id}</td>
                                    <td>${user.email}</td>
                                    <td>${user.role}</td>
                                    <td>
                                        <button class="delete-btn" onclick="confirmDelete(${user.id})">Delete</button>
                                    </td>
                                </tr>`;
                        });
                    }
                    $("#user-data").html(rows);
                },
                error: function() {
                    alert("Search request failed.");
                }
            });
        }
    </script>

</body>
</html>
