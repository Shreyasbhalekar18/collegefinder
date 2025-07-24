<?php
// Start session
session_start();

// Database connection
$host = "localhost:3307"; // Adjust the port if necessary
$user = "root"; // Your database username
$pass = ""; // Your database password
$db = "university_finder"; // Your database name
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];

    // Add points for sharing a link
    $pointsEarned = 10;  // Points per share
    $updatePointsQuery = "UPDATE users SET points = points + ? WHERE id = ?";
    $stmt = $conn->prepare($updatePointsQuery);
    $stmt->bind_param("ii", $pointsEarned, $userId);
    
    if ($stmt->execute()) {
        // Fetch updated points and username for the leaderboard
        $getUserPointsQuery = "SELECT username, points FROM users WHERE id = ?";
        $stmt = $conn->prepare($getUserPointsQuery);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Return response with username and updated points
        echo json_encode(['username' => $user['username'], 'points' => $user['points']]);
    } else {
        echo json_encode(['error' => 'Failed to update points']);
    }
} else {
    echo json_encode(['error' => 'User not logged in']);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>