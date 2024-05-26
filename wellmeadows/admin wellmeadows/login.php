<?php

$host = 'localhost'; // PostgreSQL server host
$dbname = 'Hospital'; // Your PostgreSQL database name
$user = 'postgres'; // Your PostgreSQL username
$password = 'viray'; // Your PostgreSQL password

try {
    $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    // Set PDO to throw exceptions on error
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>
<?php
session_start();

require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Prepare a SQL statement to fetch user by email and password
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Login successful, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            // Redirect to dashboard or home page
            header("Location: index.php");
            exit();
        } else {
            // Login failed
            echo "Invalid email or password. Please try again.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>
