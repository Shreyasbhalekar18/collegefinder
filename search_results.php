<?php
require 'db_connection.php'; // Your DB connection

$searchQuery = $_GET['query'];

// Fetch colleges from both tables based on search query
$sql = "(SELECT id, college_name, city, state, courses, genders_accepted, established_year, university, college_type, average_fees, rating, college_links, NULL as cid, NULL as coll_address, NULL as coll_city, NULL as degree
         FROM colleges WHERE college_name LIKE ? OR city LIKE ? OR state LIKE ? OR courses LIKE ?)
        UNION ALL
        (SELECT NULL as id, college_name, NULL as city, NULL as state, NULL as courses, NULL as genders_accepted, NULL as established_year, NULL as university, NULL as college_type, fees as average_fees, NULL as rating, coll_website as college_links, cid, coll_address, coll_city, degree
         FROM college_info WHERE college_name LIKE ? OR coll_city LIKE ? OR degree LIKE ?)";

$stmt = $conn->prepare($sql);
$searchTerm = "%" . $searchQuery . "%";

// Bind 7 parameters (4 for the first SELECT, 3 for the second)
$stmt->bind_param('sssssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Scheherazade+New&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Goudy+Bookletter+1911&family=Roboto+Slab:wght@800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <style>
         /* General background and text color */
body {
  font-family: 'Montserrat', sans-serif;
  color: #e0e0e0;
  background: linear-gradient(135deg, #2c2c2c, #1d1e22);
  margin: 0;
  padding: 0;
  overflow-x: hidden;
}

/* Navbar Styles */
  .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #343a40; /* Dark background */
      padding: 15px 30px; /* Increased padding */
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
  }

  .navbar-brand {
      font-size: 28px; /* Increased font size for brand */
      color: #ffffff; /* White text color */
      text-decoration: none;
      transition: color 0.3s;
  }

  .navbar-brand:hover {
      color: #f0f0f0; /* Slightly lighter on hover */
  }

  .navbar-nav {
      display: flex;
      gap: 20px; /* Space between navbar items */
  }

  .navbar-nav a {
      font-size: 20px; /* Increased font size for links */
      color: #ffffff; /* White text color */
      text-decoration: none;
      padding: 10px 15px; /* Padding around links */
      border-radius: 5px; /* Rounded corners for links */
      transition: background-color 0.3s, color 0.3s; /* Smooth transitions */
  }

  .navbar-nav a:hover {
      background-color: rgba(255, 255, 255, 0.2); /* Light background on hover */
      color: #f0f0f0; /* Slightly lighter text color on hover */
  }
   /* Responsive Adjustments */
   @media (max-width: 768px) {
      .navbar {
          flex-direction: column;
          align-items: flex-start;
      }

      .navbar-nav {
          flex-direction: column;
          gap: 10px; /* Space between items in mobile view */
      }

      .navbar-nav a {
          font-size: 18px; /* Slightly smaller font size for mobile */
      }
  }
  /* Container Styling */
.container {
  max-width: 900px;
  margin: 20px auto;
  padding: 40px;
  background-color: #222;
  border-radius: 15px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
  transition: transform 0.3s, box-shadow 0.3s;
}


.container:hover {
  transform: translateY(-10px);
  box-shadow: 0 10px 25px rgb(220, 214, 214);
}
        /* Result Cards */
        .college, .college-info  {
          background-color: #2a2a2a;
          color: #fff;
          padding: 20px;
          border-radius: 10px;
          box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
          transition: box-shadow 0.3s ease;
          margin-bottom: 20px; /* Adds space between college blocks vertically */
        }

        .college:hover, .college-info:hover  {
          box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
        }

        .college h3, .college-info h3 {
          color: #fffcfb;
          margin-bottom: 10px;
        }

        .college p, .college-info p  {
          color: #ddd;
        }

        /* Add to Wishlist button styles */
        .add-to-wishlist-btn {
          background-color: #16b6cf;
          color: white;
          padding: 10px 15px;
          font-size: 16px;
          margin-right: 10px;
        }

        .add-to-wishlist-btn:hover {
          background-color: #24c6d1;
          transform: translateY(-2px);
          box-shadow: 0 10px 25px rgb(220, 214, 214);
          transition: transform 0.3s, box-shadow 0.3s;
        }

        /* View College button styles */
        .view-college-btn {
          background-color: #4CAF50;
          color: white;
          padding: 10px 15px;
          font-size: 16px;
        }

        .view-college-btn:hover {
          background-color: #45a049;
          transform: translateY(-2px);
          box-shadow: 0 10px 25px rgb(220, 214, 214);
          transition: transform 0.3s, box-shadow 0.3s;
        }
    </style>    
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <img class="logo" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSadbBO57YIUp9s54yBrhwIRNNaLtbtH-A2Ug&usqp=CAU" alt="UniversityFindr Logo">
      <a class="navbar-brand">College Finder</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="index2.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="About.html">About Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="contact.html">Contact Us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="review.html">Reviews</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="profile.php">Profile</a>
          </li>
          </ul>
      </div>
    </div>
  </nav>

  <div class="container result-section">
  <h2>Filtered Colleges</h2>
  <div id="resultsContainer">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <!-- Check if the row is from 'updated_colleges' or 'college_info' -->
        <?php if (isset($row['id'])): ?>
          <!-- Display colleges from updated_colleges table -->
          <div class="college">
            <h3><?php echo htmlspecialchars($row['college_name']); ?></h3>
            <div class="displaycollege">
              <p><strong>Location:</strong> <?php echo htmlspecialchars($row['city']) . ', ' . htmlspecialchars($row['state']); ?></p>
              <p><strong>Courses:</strong>
    <?php
    // Split the courses string into an array, remove duplicates, and trim any whitespace
$coursesArray = array_unique(array_map('trim', explode(',', $row['courses'])));
$courseCount = count($coursesArray);

// Check if there are any courses
if (!empty($coursesArray)) {
    echo '<ul id="course-short-' . $row['id'] . '">'; // Start the unordered list for short view

    // Loop through the array, only accessing elements that exist
    for ($i = 0; $i < min(5, $courseCount); $i++) {
        if (isset($coursesArray[$i]) && !empty($coursesArray[$i])) {
            echo '<li>' . htmlspecialchars($coursesArray[$i]) . '</li>';
        }
    }
    echo '</ul>';

    // Full view (hidden by default)
    if ($courseCount > 5) {
        echo '<ul id="course-full-' . $row['id'] . '" style="display:none;">';
        foreach ($coursesArray as $course) {
            if (!empty($course)) {
                echo '<li>' . htmlspecialchars($course) . '</li>';
            }
        }
        echo '</ul>';

        // "Read More" link
        echo '<span id="read-more-btn-container-' . $row['id'] . '">';
        echo '<a href="javascript:void(0);" id="read-more-btn-' . $row['id'] . '" onclick="toggleReadMore(' . $row['id'] . ')">Read More</a>';
        echo '</span>';
    }
} else {
    echo 'No courses available'; // Optional: Display a message if no courses are available
}
?>
</p>
              <p><strong>Genders Accepted:</strong> <?php echo htmlspecialchars($row['genders_accepted']); ?></p>
              <p><strong>Established Year:</strong> <?php echo htmlspecialchars($row['established_year']); ?></p>
              <p><strong>University:</strong> <?php echo htmlspecialchars($row['university']); ?></p>
              <p><strong>College Type:</strong> <?php echo htmlspecialchars($row['college_type']); ?></p>
              <p><strong>Average Fees:</strong> ₹<?php echo number_format((float) preg_replace('/[^\d.]/', '', $row['average_fees'])); ?></p>
              <?php if (isset($row['rating'])): ?>
                <p><strong>Rating:</strong> <?php echo htmlspecialchars($row['rating']); ?></p>
              <?php endif; ?>
              <button class="add-to-wishlist-btn" onclick="addToWishlist('<?php echo htmlspecialchars($row['college_name']); ?>')">Add to Wishlist</button>
              <!-- Use college_links for updated_colleges -->
              <button class="view-college-btn" onclick="window.location.href='<?php echo htmlspecialchars($row['college_links']); ?>'">View College</button>
            </div>
          </div>

        <?php elseif (isset($row['cid'])): ?>
          <!-- Display colleges from college_info table -->
          <div class="college-info">
            <h3><?php echo htmlspecialchars($row['college_name']); ?></h3>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($row['coll_address']); ?></p>
            <p><strong>City:</strong> <?php echo htmlspecialchars($row['coll_city']); ?></p>
            <p><strong>Degree:</strong> <?php echo htmlspecialchars($row['degree']); ?></p>
            <p><strong>Fees:</strong> <?php echo htmlspecialchars($row['average_fees']); ?></p>

            <button class="add-to-wishlist-btn" onclick="addToWishlist('<?php echo htmlspecialchars($row['college_name']); ?>')">Add to Wishlist</button>
            <!-- Use college_links instead of coll_website -->
            <button class="view-college-btn" onclick="window.location.href='<?php echo htmlspecialchars($row['college_links']); ?>'">View College</button>
          </div>

        <?php endif; ?>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No colleges found for the selected filters.</p>
    <?php endif; ?>
  </div>
</div>

<script>
    function addToWishlist(collegeName) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'add_to_wishlist.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert('College added to your wishlist!');
                updateWishlist(response.wishlist); // Update the wishlist dynamically
            } else {
                alert('Failed to add to wishlist: ' + response.message);
            }
        }
    };
    xhr.send(`college_name=${encodeURIComponent(collegeName)}`);
} 
// Toggle between "Read More" and "Read Less"
function toggleReadMore(id) {
  const shortList = document.getElementById('course-short-' + id);
  const fullList = document.getElementById('course-full-' + id);
  const readMoreBtn = document.getElementById('read-more-btn-' + id);
  
  if (fullList.style.display === 'none') {
    fullList.style.display = 'block';
    shortList.style.display = 'none';
    readMoreBtn.innerHTML = 'Read Less';
  } else {
    fullList.style.display = 'none';
    shortList.style.display = 'block';
    readMoreBtn.innerHTML = 'Read More';
  }
}
</script>    
</body>
</html>
