<?php
// Database connection
$conn = new mysqli("localhost:3307", "root", "", "university_finder");

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Fetch unique states
$stateResult = $conn->query("SELECT DISTINCT state FROM colleges");
$states = [];
if ($stateResult) {
    while ($row = $stateResult->fetch_assoc()) {
        $states[] = $row['state'];
    }
}

// Fetch unique cities
$cityResult = $conn->query("SELECT DISTINCT city FROM colleges");
$cities = [];
if ($cityResult) {
    while ($row = $cityResult->fetch_assoc()) {
        $cities[] = $row['city'];
    }
}

// Fetch unique courses and split by commas
$courseResult = $conn->query("SELECT courses FROM colleges");
$courses = [];
if ($courseResult) {
    while ($row = $courseResult->fetch_assoc()) {
        // Assuming courses are stored as comma-separated values in a single field
        $courseArray = array_map('trim', explode(',', $row['courses']));
        foreach ($courseArray as $course) {
            if (!in_array($course, $courses)) {
                $courses[] = $course; // Add only distinct courses
            }
        }
    }
}

// Return the results as a JSON response
echo json_encode([
    'states' => $states,
    'cities' => $cities,
    'courses' => $courses,
]);

$conn->close();
?>
