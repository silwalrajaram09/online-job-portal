$(document).ready(function () {
    // Fetch job categories dynamically via AJAX
    // $(document).ready(function() {
    //     // Fetch job categories dynamically via AJAX
    //     $.ajax({
    //         url: '../../backend/controllers/user.php', // Modify this path if needed
    //         method: 'GET',
    //         data: {
    //             action: 'fetch_all_categories'
    //         }, // Action to fetch all categories
    //         dataType: 'json',
    //         success: function(response) {
    //             let dropdown = $('#job_category');
    //             dropdown.empty(); // Clear existing options
    //             dropdown.append('<option value="">Select Job Category</option>'); // Default option

    //             if (response.error) {
    //                 alert(response.error); // If there's an error
    //             } else {
    //                 // Loop through each category and add it to the dropdown
    //                 response.forEach(function(category) {
    //                     dropdown.append(
    //                         `<option value="${category.id}">${category.category_name}</option>`
    //                     );
    //                 });
    //             }
    //         },
    //         error: function() {
    //             alert('Failed to load categories.');
    //         }
    //     });
        
    //    });

    // Handle form submission
    $("#jobForm").submit(function (e) {
        e.preventDefault(); 

        $.ajax({
            url: "../../../Backend/controllers/JobController.php?action=create", 
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $("#Message").html("<span style='color:green;'>" + response.message + "</span>");
                    $("#jobForm")[0].reset();
                } else {
                    $("#Message").html("<span style='color:red;'>" + response.message + "</span>");
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error, xhr.responseText);
                $("#Message").html("<span style='color:red;'>An error occurred. Please try again.</span>");
            }
        });
    });
});
