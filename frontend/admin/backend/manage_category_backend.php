<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online_job_portal";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['action'])) {
        $action = $_GET['action'];

        switch ($action) {
            case 'get_categories':
                $stmt = $conn->prepare("SELECT * FROM job_category");
                $stmt->execute();
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(['success' => true, 'categories' => $categories]);
                break;

            case 'add_category':
                $categoryName = $_POST['category_name'];
                $stmt = $conn->prepare("INSERT INTO job_category (category_name) VALUES (:category_name)");
                $stmt->bindParam(':category_name', $categoryName);
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Category added successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to add category']);
                }
                break;

            case 'delete_category':
                $categoryId = $_POST['category_id'];
                $stmt = $conn->prepare("DELETE FROM job_category WHERE id = :category_id");
                $stmt->bindParam(':category_id', $categoryId);
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Category deleted successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete category']);
                }
                break;

            case 'update_category':
                $categoryId = $_POST['category_id'];
                $categoryName = $_POST['category_name'];
                $stmt = $conn->prepare("UPDATE job_category SET category_name = :category_name WHERE id = :category_id");
                $stmt->bindParam(':category_id', $categoryId);
                $stmt->bindParam(':category_name', $categoryName);
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Category updated successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update category']);
                }
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
                break;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Action not specified']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

$conn = null;
?>