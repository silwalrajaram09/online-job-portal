<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Applications</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .status-approve { color: green; font-weight: bold; }
        .status-reject { color: red; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Job Applications</h2>
    <table id="applicationsTable">
        <thead>
            <tr>
                <th>Job Title</th>
                <th>Company Name</th>
                <th>Jobseeker Name</th>
                <th>Applied Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            </tbody>
    </table>

    <script>
        $(document).ready(function() {
            function loadApplications() {
                $.ajax({
                    url: 'backend/applicationBackend.php', // Replace with your backend file name
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            displayApplications(response.applications);
                        } else {
                            alert('Failed to load applications.');
                        }
                    },
                    error: function() {
                        alert('An error occurred loading applications.');
                    }
                });
            }

            function displayApplications(applications) {
                var tableBody = $('#applicationsTable tbody');
                tableBody.empty();

                applications.forEach(function(app) {
                    var row = $('<tr>');
                    row.append('<td>' + app.job_title + '</td>');
                    row.append('<td>' + app.company_name + '</td>');
                    row.append('<td>' + app.jobseeker_name + '</td>');
                    row.append('<td>' + app.created_at + '</td>');
                    row.append('<td class="status-' + app.status + '">' + app.status.charAt(0).toUpperCase() + app.status.slice(1) + '</td>');

                    var actions = $('<td>');
                    if (app.status === 'pending') {
                        actions.append('<button class="approveBtn" data-id="' + app.id + '">Approve</button>');
                        actions.append('<button class="rejectBtn" data-id="' + app.id + '">Reject</button>');
                    }
                    row.append(actions);
                    tableBody.append(row);
                });

                $('.approveBtn, .rejectBtn').click(function() {
                    var jobId = $(this).data('id');
                    var action = $(this).hasClass('approveBtn') ? 'approve' : 'reject';
                    var row = $(this).closest('tr');

                    $.ajax({
                        url: 'backend/applicationBackend.php', // Replace with your backend file name
                        type: 'POST',
                        dataType: 'json',
                        data: { job_id: jobId, action: action },
                        success: function(response) {
                            if (response.success) {
                                row.find('td:nth-child(5)').text(action.charAt(0).toUpperCase() + action.slice(1)).removeClass('status-pending').addClass('status-' + action);
                                row.find('td:nth-child(6)').empty(); // Remove buttons
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function() {
                            alert('An error occurred during update.');
                        }
                    });
                });
            }

            loadApplications();
        });
    </script>
</body>
</html>