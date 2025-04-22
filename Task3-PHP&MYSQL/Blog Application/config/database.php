<?php
$host = 'localhost';
$dbname = 'blog';
$username = 'root';
$password = '';

// Establish a connection to the database
$connect = mysqli_connect($host, $username, $password, $dbname);

// Check if the connection was successful
if (!$connect) {
    // Log the error and display a user-friendly message
    error_log("Database connection failed: " . mysqli_connect_error());
    die("Failed to connect to the database. Please try again later.");
}

echo "Database connected successfully!";

// Close the connection (optional, as PHP will close it automatically at the end of the script)
mysqli_close($connect);
