<?php
$host = 'localhost';
$dbname = 'blog';
$username = 'root';
$password = '';
$conn = '';

// Establish a connection to the database
$connect = mysqli_connect($host, $dbname, $username, $password);

// Check if the connection was successful
if (!$connect) {
    // Log the error and display a user-friendly message
    error_log("Database connection failed: " . mysqli_connect_error());
    die("Failed to connect to the database. Please try again later.");
}

echo "Database connected successfully!";

// Close the connection (optional, as PHP will close it automatically at the end of the script)
mysqli_close($connect);
<?php
// Set secure session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Enable if using HTTPS
ini_set('session.use_strict_mode', 1);

$host = 'localhost';
$dbname = 'blog';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("A database error occurred. Please try again later.");
}
?>