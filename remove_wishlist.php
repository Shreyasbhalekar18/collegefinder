<?php
// remove_wishlist.php
session_start();
require 'db_connection.php'; // Adjust this according to your setup

// Get the logged-in user ID and college name to be removed
$college_name = $_POST['college_name'];
$user_id = $_SESSION['userId']; // Assuming the userId is stored in the session

// Fetch the current wishlist from the database
$sql = "SELECT wishlist FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$wishlist = json_decode($user['wishlist'], true);

// Remove the college from the wishlist
foreach ($wishlist as $key => $item) {
    if ($item['college_name'] === $college_name) {
        unset($wishlist[$key]);
    }
}

// Re-index array to avoid JSON errors
$wishlist = array_values($wishlist);

// Update the database with the modified wishlist
$updated_wishlist = json_encode($wishlist);
$sql = "UPDATE users SET wishlist = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $updated_wishlist, $user_id);
$stmt->execute();

// Redirect back to profile page
header("Location: profile.php");
exit;
?>
