<?php
// add_to_wishlist.php
session_start();

// Database connection
$servername = "localhost:3307"; // Adjust as needed
$username = "root";        // Adjust as needed
$password = "";            // Adjust as needed
$dbname = "university_finder"; // Adjust as needed

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: login.html");
    exit();
}

$userId = $_SESSION['userId'];

// Check if the college name is submitted
if (isset($_POST['college_name'])) {
    $collegeName = $_POST['college_name'];

    // Fetch the current wishlist
    $query = "SELECT wishlist FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $wishlist = !empty($row['wishlist']) ? json_decode($row['wishlist'], true) : [];

    // Check if the college is already in the wishlist
    $isInWishlist = false;
    foreach ($wishlist as $item) {
        if ($item['college_name'] === $collegeName) {
            $isInWishlist = true;
            break;
        }
    }

    if (!$isInWishlist) {
        // Add the college to the wishlist
        $wishlist[] = ['college_name' => $collegeName];
        $updatedWishlist = json_encode($wishlist);

        // Update the wishlist in the database
        $updateQuery = "UPDATE users SET wishlist = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param('si', $updatedWishlist, $userId);
        $stmt->execute();
    }
}

// Redirect back to the filter page
header("Location: filter_page.php");
exit();
?>
