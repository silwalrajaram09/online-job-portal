<?php
// backend/models/Database.php

class Database {
    private static $instance = null;
    private static $connection;

    public function __construct() {
        // Private constructor to prevent direct instantiation
    }

    // Method to get the database connection
    public static function getConnection() {
        if (self::$connection === null) {
            try {
                // Database configuration
                $host = 'localhost'; // Your database host
                $dbname = 'online_job_portal'; // Your database name
                $username = 'root'; // Your database username
                $password = ''; // Your database password

                // Create a new PDO instance
                self::$connection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // Handle connection error
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }

    // Prevent cloning of the instance
    private function __clone() {}

    // Close the connection
    public static function closeConnection() {
        self::$connection = null;
    }
}
