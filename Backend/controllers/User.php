<?php
// models/User.php

require_once 'Database.php';

class User {
    public $id;
    public $email;
    public $password;
    public $role; //  'jobseeker', 'company'
    public $company_name; // Only for 'company' role
    public $company_address; // Only for 'company' role
    public $first_name; // Only for 'jobseeker' role
    public $last_name; // Only for 'jobseeker' role
    public $phone; // For both 'jobseeker' and 'company' roles
    public $job_category; // Only for 'jobseeker' role

    // Get a user by ID
    public static function findById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        return $stmt->fetch();
    }

    // Get a user by email
    public static function findByEmail($email) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        return $stmt->fetch();
    }

    // Get all users by role
    public static function getAllByRole($role) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE role = ?");
        $stmt->execute([$role]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
    }

    // Register user in the `users` table
    public function registerUser($email, $password, $role) {
        $db = Database::getConnection();
         $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $db->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$email, $hashedPassword, $role]);

        return $db->lastInsertId(); // Return the new user's ID
    }

    // Register jobseeker-specific details
    public function registerJobseekerDetails($userId, $firstName, $lastName, $phone, $jobCategory,$skills) {
        $db = Database::getConnection();
        // $skillsString = is_array($skills) ? json_encode($skills) : $skills;
        $stmt = $db->prepare("INSERT INTO jobseeker (user_id, first_name, last_name, phone, job_category_id,skills) VALUES (?, ?, ?, ?, ?,?)");
        return $stmt->execute([$userId, $firstName, $lastName, $phone, $jobCategory,$skills]);
    }

    // Register company-specific details
    public function registerCompanyDetails($userId, $companyName, $phone, $companyType, $city, $location, $contactName, $contactEmail) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO companyRegistration (user_id, company_name, phone, company_type_id, city, location, contact_name, contact_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$userId, $companyName, $phone, $companyType, $city, $location, $contactName, $contactEmail]);
    }
    
    public static function getAllCategories() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM job_category ORDER BY category_name ASC");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($categories);
    }
    public static function getCompanyType() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT * FROM company_type ORDER BY type_name ASC");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($categories);
    }
    // Update an existing user (e.g., changing details)
    public function updateUserDetails() {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE users SET email = ?, password = ?, role = ? WHERE id = ?");
        return $stmt->execute([$this->email, $this->password, $this->role, $this->id]);
    }

    // Delete a user by ID and role
    public static function deleteByIdAndRole($id, $role) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM users WHERE id = ? AND role = ?");
        return $stmt->execute([$id, $role]);
    }
    public function emailExists($email) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT email FROM users WHERE email = ?"); 
        $stmt->execute([$email]);
        return $stmt->rowCount() > 0;
    }
    public function updatePassword($email, $newPassword) {
        $db = Database::getConnection();
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $db->prepare($query);
        return $stmt->execute([$hashedPassword, $email]);
    }
}
    if (isset($_GET['action']) && $_GET['action'] == 'fetch_all_categories') {
      // $categories=self::getAllCategories();
        $categories = User::getAllCategories();
        echo $categories; 
        exit; 
}
if (isset($_GET['action']) && $_GET['action'] == 'fetch_all_company_type') {
    // $categories=self::getAllCategories();
      $categories = User::getCompanyType();
      echo $categories; 
      exit; 
}


?>
