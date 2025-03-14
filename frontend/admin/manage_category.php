<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        #addCategoryForm { display: none; margin-top: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Job Categories</h2>
    <button id="addCategoryButton">Add Category</button>

    <div id="addCategoryForm">
        <input type="text" id="newCategoryName" placeholder="Category Name" required>
        <button id="submitCategory">Submit</button>
    </div>

    <div id="updateCategoryForm" style="display:none;">
        <input type="hidden" id="updateCategoryId">
        <input type="text" id="updateCategoryName" required>
        <button id="updateCategorySubmit">Update</button>
    </div>

    <table id="categoryTable">
        <thead>
            <tr>
                <th>Category Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            </tbody>
    </table>

    <script>
        $(document).ready(function() {
            loadCategories();

            $("#addCategoryButton").click(function() {
                $("#addCategoryForm").slideToggle();
            });

            $("#submitCategory").click(function() {
                $.ajax({
                    url: 'backend/manage_category_backend.php?action=add_category',
                    type: 'POST',
                    dataType: 'json',
                    data: { category_name: $("#newCategoryName").val() },
                    success: function(response) {
                        if (response.success) {
                            loadCategories();
                            $("#addCategoryForm").hide();
                            $("#newCategoryName").val('');
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });

            $("#categoryTable").on('click', '.delete-button', function() {
                $.ajax({
                    url: 'backend/manage_category_backend.php?action=delete_category',
                    type: 'POST',
                    dataType: 'json',
                    data: { category_id: $(this).data('id') },
                    success: function(response) {
                        if (response.success) {
                            loadCategories();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });

            $("#categoryTable").on('click', '.update-button', function() {
                $("#updateCategoryId").val($(this).data('id'));
                $("#updateCategoryName").val($(this).data('name'));
                $("#updateCategoryForm").show();
            });

            $("#updateCategorySubmit").click(function() {
                $.ajax({
                    url: 'backend/manage_category_backend.php?action=update_category',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        category_id: $("#updateCategoryId").val(),
                        category_name: $("#updateCategoryName").val()
                    },
                    success: function(response) {
                        if (response.success) {
                            loadCategories();
                            $("#updateCategoryForm").hide();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });

            function loadCategories() {
                $.ajax({
                    url: 'backend/manage_category_backend.php?action=get_categories',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            let tableBody = $("#categoryTable tbody");
                            tableBody.empty();
                            response.categories.forEach(category => {
                                tableBody.append(`
                                    <tr>
                                        <td>${category.category_name}</td>
                                        <td>
                                            <button class="delete-button" data-id="${category.id}">Delete</button>
                                            <button class="update-button" data-id="${category.id}" data-name="${category.category_name}">Update</button>
                                        </td>
                                    </tr>
                                `);
                            });
                        } else {
                            alert(response.message);
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>