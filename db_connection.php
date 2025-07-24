<?php
// Database connection
$servername = "localhost:3307"; // Adjust as needed
$username = "root";        // Adjust as needed
$password = "";            // Adjust as needed
$dbname = "university_finder"; // Adjust as needed

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>