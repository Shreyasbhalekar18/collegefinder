<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost:3307';
$db = 'university_finder';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if userId is set in session
if (!isset($_SESSION['userId'])) {
    echo '<script>
            alert("Error: User not logged in.");
            window.location.href = "login.html"; // Redirect to login page or any other page
          </script>';
    exit();
}

$userId = $_SESSION['userId'];

// Check if review is set in POST data
if (!isset($_POST['review'])) {
    echo '<script>
            alert("Error: Review not provided.");
            window.location.href = "review.html"; // Redirect to review page or any other page
          </script>';
    exit();
}
$review = $_POST['review'];

// Fetch user's name and email
$sql = "SELECT username, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['username'];
    $email = $row['email'];
} else {
    echo '<script>
            alert("Error: User not found.");
            window.location.href = "review.html"; // Redirect to review page or any other page
          </script>';
    exit();
}

// Insert into reviews table
$sql = "INSERT INTO reviews (user_id, name, email, review, created_at) VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isss", $userId, $name, $email, $review);

if ($stmt->execute()) {
    echo '<script>
            alert("Success: Review submitted successfully!");
            window.location.href = "review.html"; // Redirect to the review page or any other page
          </script>';
} else {
    echo '<script>
            alert("Error: ' . $stmt->error . '");
            window.location.href = "review.html"; // Redirect to review page or any other page
          </script>';
}

$stmt->close();
$conn->close();
?>
