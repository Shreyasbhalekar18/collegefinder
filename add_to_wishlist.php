<?php
session_start();

// Database connection
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "university_finder";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['college_name'])) {
    $userId = $_SESSION['userId']; // Assuming you have the userId stored in the session
    $collegeName = $_POST['college_name'];

    // Fetch current wishlist from the users table
    $query = "SELECT wishlist FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $wishlist = $row['wishlist'] ? json_decode($row['wishlist'], true) : [];

    // Add new college to the wishlist if not already present
    $newItem = ["college_name" => $collegeName];
    if (!in_array($newItem, $wishlist)) {
        $wishlist[] = $newItem;

        // Update the wishlist in the users table
        $updatedWishlist = json_encode($wishlist);
        $updateQuery = "UPDATE users SET wishlist = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('si', $updatedWishlist, $userId);
        $updateStmt->execute();
    }

    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'wishlist' => $wishlist]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}
?>
