<?php
$connection = mysqli_connect('localhost', 'root', '', 'online_job_portal');
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['id'];
    $userPassword = $_POST['password'];

    // Get user's hashed password
    $stmt = $connection->prepare("SELECT password, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($hashedPassword, $userRole);
    $stmt->fetch();
    $stmt->close();

    // Prevent deleting admin accounts
    if ($userRole === 'admin') {
        echo "Admins cannot be deleted!";
        exit;
    }

    // Validate password
    if (!password_verify($userPassword, $hashedPassword)) {
        echo "Incorrect password!";
        exit;
    }

    // Delete user
    $stmt = $connection->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "success";
    } else {
        echo "Error deleting user.";
    }

    $stmt->close();
}

mysqli_close($connection);
?>
