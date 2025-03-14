<?php
//application type json 
header('Content-Type: application/json');
$connection = mysqli_connect('localhost', 'root', '', 'online_job_portal');
if (!$connection) {
    die(json_encode(["error" => "Connection failed"]));
}

$id = $_POST['id'] ?? '';
$role = $_POST['role'] ?? '';

$query = "SELECT * FROM users WHERE 1=1"; 

if (!empty($id)) {
    $query .= " AND id = " . intval($id);
}

if (!empty($role)) {
    $query .= " AND role = '" . mysqli_real_escape_string($connection, $role) . "'";
}

$result = mysqli_query($connection, $query);

$users = [];
while ($user = mysqli_fetch_assoc($result)) {
    $users[] = $user;
}

echo json_encode($users);
mysqli_close($connection);
?>
