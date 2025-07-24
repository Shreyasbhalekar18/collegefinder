<?php
// upload_certificates.php

session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['userId'];

// Ensure that the upload directory exists
$uploadDir = "uploads/certificates/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Handle the file upload
if (isset($_FILES['certificateUpload']) && $_FILES['certificateUpload']['error'] == 0) {
    $fileName = basename($_FILES['certificateUpload']['name']);
    $targetFilePath = $uploadDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Allow only image file formats
    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES['certificateUpload']['tmp_name'], $targetFilePath)) {
            // Insert certificate into database
            $conn = new mysqli("localhost:3307", "root", "", "university_finder");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $stmt = $conn->prepare("INSERT INTO certificates (user_id, certificate) VALUES (?, ?)");
            $stmt->bind_param("is", $userId, $fileName);

            if ($stmt->execute()) {
                echo "Certificate uploaded successfully.";
                header("Location: profile.php"); // Redirect back to the profile page to see the changes
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Sorry, only image files (JPG, JPEG, PNG, GIF) are allowed.";
    }
} else {
    echo "No file uploaded or there was an upload error.";
}
?>
