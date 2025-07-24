<?php

header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost:3307", "root", "", "university_finder");

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Get form inputs for filtering
$state = isset($_POST['state']) ? $_POST['state'] : '';
$city = isset($_POST['city']) ? $_POST['city'] : '';
$course = isset($_POST['course']) ? $_POST['course'] : ''; // Use 'course' instead of 'courses'

// Build the query for fetching colleges based on selected filters
$query = "SELECT college_name, city, state, courses, genders_accepted, established_year, rating, university, college_type, average_fees, college_links
          FROM colleges   
          WHERE 1=1";

// Prioritize filtering by courses if selected
if (!empty($course)) {
    // Filter by courses (using LIKE for partial match)
    $query .= " AND courses LIKE '%" . $conn->real_escape_string($course) . "%'";
}

// Filter by state if selected
if (!empty($state)) {
    $query .= " AND state = '" . $conn->real_escape_string($state) . "'";
}

// Filter by city if selected
if (!empty($city)) {
    $query .= " AND city = '" . $conn->real_escape_string($city) . "'";
}

// Optionally order by rating in descending order and limit results
$query .= " ORDER BY rating DESC LIMIT 10";

// Execute the query
$result = $conn->query($query);

$colleges = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Split the courses by commas, remove duplicates, and rejoin them
        $unique_courses = array_unique(array_map('trim', explode(',', $row['courses'])));
        $row['courses'] = implode(', ', $unique_courses);
        $colleges[] = $row;
    }
}

// Return the results as a JSON response
echo json_encode($colleges);

$conn->close();
?>
