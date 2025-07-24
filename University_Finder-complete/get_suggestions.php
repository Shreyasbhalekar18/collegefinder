<?php
require 'db_connection.php'; // Your DB connection

$query = $_GET['q'];

// SQL query to fetch college names from both tables
$sql = "
    SELECT DISTINCT college_name 
    FROM colleges 
    WHERE college_name LIKE ? OR city LIKE ? OR state LIKE ? OR courses LIKE ? 
    UNION
    SELECT DISTINCT college_name 
    FROM college_info 
    WHERE college_name LIKE ? OR coll_city LIKE ? OR courses LIKE ?   ";

// Prepare the statement
$stmt = $conn->prepare($sql);
$searchTerm = "%" . $query . "%";
$stmt->bind_param('sssssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// Fetch suggestions
$suggestions = [];
while ($row = $result->fetch_assoc()) {
    $suggestions[] = $row['college_name'];
}

// Return suggestions as JSON
echo json_encode($suggestions);
?>
