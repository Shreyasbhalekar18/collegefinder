<?php
// update_marks.php

session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['userId'];

$cetMarks = isset($_POST['cetMarks']) ? $_POST['cetMarks'] : null;
$tenthMarks = isset($_POST['tenthMarks']) ? $_POST['tenthMarks'] : null;
$twelfthMarks = isset($_POST['twelfthMarks']) ? $_POST['twelfthMarks'] : null;

if ($cetMarks !== null && $tenthMarks !== null && $twelfthMarks !== null) {
    // Database connection
    $servername = "localhost:3307"; // Adjust as needed
    $username = "root";        // Adjust as needed
    $password = "";            // Adjust as needed
    $dbname = "university_finder"; // Adjust as needed

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update user's marks
    $sql = "UPDATE users SET cet_marks = ?, tenth_marks = ?, twelfth_marks = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $cetMarks, $tenthMarks, $twelfthMarks, $userId);

    if ($stmt->execute()) {
        header("Location: profile.php");
    } else {
        echo "Sorry, there was an error updating your marks.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Please fill out all fields.";
}
?>
