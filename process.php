<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost:3307';
$db = 'university_finder'; // Replace with your database name
$user = 'root'; // Replace with your database username
$pass = ''; // Replace with your database password if it's not empty

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start(); // Start session at the beginning

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['signup'])) {
        // Sign Up
        $username = $_POST['signupUsername'];
        $email = $_POST['signupEmail'];
        $password = password_hash($_POST['signupPassword'], PASSWORD_DEFAULT);
        $phonenumber = $_POST['phonenumber'];
        $address = $_POST['address'];

        $sql = "INSERT INTO users (username, email, password, phonenumber, address) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $username, $email, $password, $phonenumber, $address);

        if ($stmt->execute()) {
            // After successful signup, retrieve the user ID
            $userId = $stmt->insert_id; // Get the ID of the newly inserted user
            session_regenerate_id(true);
            // Store the user ID in session

            // Redirect to the homepage or any other page
            header("Location: index2.php"); // Redirect to the homepage or any page you prefer
            exit(); // Make sure to stop further script execution
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } elseif (isset($_POST['login'])) {
        // Login
        $username = $_POST['loginUsername'];
        $password = $_POST['loginPassword'];

        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // Successful login
                $_SESSION['userId'] = $row['id'];
                session_regenerate_id(true);
                // Store user ID in session
                header("Location: index2.php"); // Redirect to the homepage or any page you prefer
                exit(); // Make sure to stop further script execution
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "No user found with that username.";
        }

        $stmt->close();
    }
}

$conn->close();
?>
