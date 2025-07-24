<?php
// Database connection
$conn = new mysqli("localhost:3307", "root", "", "university_finder");

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Get the selected state from the query parameter
$selectedState = isset($_GET['state']) ? $_GET['state'] : '';

// Fetch cities based on the selected state
if ($selectedState) {
    $stmt = $conn->prepare("SELECT DISTINCT city FROM colleges WHERE state = ?");
    $stmt->bind_param("s", $selectedState);
    $stmt->execute();
    $result = $stmt->get_result();

    $cities = [];
    while ($row = $result->fetch_assoc()) {
        $cities[] = $row['city'];
    }
    
    // Return the cities as a JSON response
    echo json_encode(['cities' => $cities]);
} else {
    echo json_encode(['cities' => []]);
}

$conn->close();
?>
