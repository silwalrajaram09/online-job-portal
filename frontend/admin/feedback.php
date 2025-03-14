<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Feedback</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Admin Feedback</h2>

    <table id="feedbackTable">
        <thead>
            <tr>
                <th>User Type</th>
                <th>User ID</th>
                <th>Feedback Text</th>
                <th>Rating</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            loadFeedback();

            function loadFeedback() {
                $.ajax({
                    url: 'backend/feedback_backend.php?action=get_feedback',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            let tableBody = $("#feedbackTable tbody");
                            tableBody.empty();
                            response.feedback.forEach(feedback => {
                                tableBody.append(`
                                    <tr>
                                        <td>${feedback.user_type}</td>
                                        <td>${feedback.user_id}</td>
                                        <td>${feedback.feedback_text}</td>
                                        <td>${feedback.rating || 'N/A'}</td>
                                        <td>${feedback.feedback_date}</td>
                                        <td>${feedback.status}</td>
                                        <td>
                                            <select class="status-select" data-id="${feedback.id}">
                                                <option value="pending" ${feedback.status === 'pending' ? 'selected' : ''}>Pending</option>
                                                <option value="reviewed" ${feedback.status === 'reviewed' ? 'selected' : ''}>Reviewed</option>
                                                <option value="resolved" ${feedback.status === 'resolved' ? 'selected' : ''}>Resolved</option>
                                            </select>
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

            $("#feedbackTable").on('change', '.status-select', function() {
                let feedbackId = $(this).data('id');
                let status = $(this).val();

                $.ajax({
                    url: 'backend/feedback_backend.php?action=update_status',
                    type: 'POST',
                    dataType: 'json',
                    data: { feedback_id: feedbackId, status: status },
                    success: function(response) {
                        if (response.success) {
                            loadFeedback(); // Refresh the table
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>