<?php
// Connect to the university_finder database
$conn = new mysqli('localhost:3307', 'root', '', 'university_finder');

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize filter variables
$city = isset($_GET['city']) ? $_GET['city'] : '';
$course = isset($_GET['course']) ? $_GET['course'] : '';
$degree = isset($_GET['degree']) ? $_GET['degree'] : '';

// Query for filtering colleges
$sql = "SELECT college_name, coll_address, coll_city, courses, degree,coll_website,fees FROM college_info WHERE 1=1";

// Add conditions based on filters
if ($degree) {
    $sql .= " AND degree = '" . $conn->real_escape_string($degree) . "'";
}
if ($city) {
    $sql .= " AND coll_city LIKE '%" . $conn->real_escape_string($city) . "%'";
}
if ($course) {
    $sql .= " AND courses LIKE '%" . $conn->real_escape_string($course) . "%'";
}

$result = $conn->query($sql);



// Fetch distinct cities and courses for dropdowns
$cities = $conn->query("SELECT DISTINCT coll_city FROM college_info");
$courses = $conn->query("SELECT DISTINCT courses FROM college_info");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stylish College Filter</title>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
  <script src="animation.js"></script>
  <link rel="stylesheet" href="bootstrap.css">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Scheherazade+New&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Goudy+Bookletter+1911&family=Roboto+Slab:wght@800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
  <style>
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
    body {
      font-family: 'Montserrat', sans-serif;
      color: #e0e0e0;
      background: linear-gradient(135deg, #2c2c2c, #1e1e1e);
      margin: 0;
      padding: 0;
      overflow-x: hidden;
    }
    /* Container Styling */
    .container {
      max-width: 900px;
      margin: 20px auto;
      padding: 40px;
      background: linear-gradient(135deg, #2c2c2c, #1d1e22);
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
      transition: transform 0.3s, box-shadow 0.3s;

    }

    .container:hover {
      transform: translateY(-10px);
      box-shadow: 0 10px 25px rgb(220, 214, 214);
    }

    /* Filters Section */
    .filter-section {
      padding: 30px;
      background-color: #2a2a2a;
      border-radius: 12px;
      margin-top: 40px;
      color: linear-gradient(135deg, #2c2c2c, #2765e9);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .filter-section h2 {
      font-size: 28px;
      font-weight: 600;
      margin-bottom: 25px;
    }

    /* Stylish Filter Select */
    .filter-select {
      width: 100%;
      padding: 15px;
      font-size: 16px;
      border-radius: 6px;
      background-color: #333;
      color: #fff;
      border: 2px solid #444;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
      margin-bottom: 20px;
    }

    .filter-select:hover {
      background-color: #444;
      border-color: #efe3de;
    }

    .filter-select:focus {
      background-color: #555;
      border-color: #f7ece8;
    }

    /* Stylish Filter Button */
    .btn-filter {
      padding: 10px 20px;
      background-color: #2623e9;
      border: none;
      color: #fff;
      font-size: 16px;
      font-weight: 600;
      border-radius: 6px;
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(255, 127, 80, 0.4);
    }

    .btn-filter:hover {
      background-color: #1c62f8;
      box-shadow: 0 6px 15px rgba(255, 87, 34, 0.5);
    }

    .btn-filter:focus {
      background-color: #0033ff;
      box-shadow: 0 6px 15px rgba(255, 61, 0, 0.6);
    }

    /* Result Section */
    .result-section {
      margin-top: 40px;
      padding: 20px;
      background-color: #2a2a2a;
      border-radius: 10px;
      color: #fff;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    /* College Info */
    .college-info {
      background-color: #2a2a2a;
      color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
      transition: box-shadow 0.3s ease;
      margin-bottom: 20px;
    }

    .college-info:hover {
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
    }

    .college-info h3 {
      color: #fffcfb;
      margin-bottom: 10px;
    }

    .college-info p {
      color: #ddd;
    }
    /* Add to Wishlist button styles */
.add-to-wishlist-btn {
    background-color: #16b6cf; /* Coral color */
    color: white;
    padding: 10px 15px;
    font-size: 16px;
    margin-right: 10px; /* Space between buttons */
}

/* Add hover effect for Add to Wishlist button */
.add-to-wishlist-btn:hover {
    background-color: #24c6d1; /* Darker coral */
    transform: translateY(-2px); /* Slight lift effect */
    box-shadow: 0 10px 25px rgb(220, 214, 214);
    transition: transform 0.3s, box-shadow 0.3s;
}
    /* View College button styles */
.view-college-btn {
    background-color: #4CAF50; /* Green color */
    color: white;
    padding: 10px 15px;
    font-size: 16px;
}

/* Add hover effect for View College button */
.view-college-btn:hover {
    background-color: #45a049; /* Darker green */
    transform: translateY(-2px); /* Slight lift effect */
    box-shadow: 0 10px 25px rgb(220, 214, 214);
    transition: transform 0.3s, box-shadow 0.3s;
}

    /* Redirect Button */
    .btn-redirect {
      padding: 12px 24px;
      font-size: 16px;
      background-color: #2623e9;
      color: #fff;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease-in-out;
      display: block;
      margin: 30px auto;
    }

    .btn-redirect:hover {
      background-color: #1c62f8;
      box-shadow: 0 6px 15px rgba(255, 87, 34, 0.5);
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

<div class="container filter-section">
  <h2>Filter Colleges for MBA and MTECH</h2>
  
  <!-- Filter Form -->
  <form action="filter_page.php" method="GET">
    <div class="form-group">
      <label for="degree">Degree:</label>
      <select id="degree" name="degree" class="filter-select">
        <option value="">Select Degree</option>
        <option value="MBA" <?php echo ($degree === 'MBA') ? 'selected' : ''; ?>>MBA</option>
        <option value="MTech" <?php echo ($degree === 'MTech') ? 'selected' : ''; ?>>MTech</option>
      </select>
    </div>

    <div class="form-group">
      <label for="city">City:</label>
      <select id="city" name="city" class="filter-select">
        <option value="">Select City</option>
        <?php if ($cities): ?>
          <?php while ($row = $cities->fetch_assoc()): ?>
            <option value="<?php echo htmlspecialchars($row['coll_city']); ?>" <?php echo ($city === $row['coll_city']) ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($row['coll_city']); ?>
            </option>
          <?php endwhile; ?>
        <?php endif; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="course">Course:</label>
      <select id="course" name="course" class="filter-select">
        <option value="">Select Course</option>
        <?php if ($courses): ?>
          <?php while ($row = $courses->fetch_assoc()): ?>
            <option value="<?php echo htmlspecialchars($row['courses']); ?>" <?php echo ($course === $row['courses']) ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($row['courses']); ?>
            </option>
          <?php endwhile; ?>
        <?php endif; ?>
      </select>
    </div>

    <button type="submit" class="btn-filter">Apply Filters</button>
  </form>

  <!-- Redirect Button to Filter MBA and MTech 
  <button class="btn-redirect" onclick="window.location.href='filter_mba_mtech.php'">Filter MBA and MTech Colleges</button>
</div> -->

<div class="container result-section">
  <h2>Filtered Colleges</h2>
  <?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="college-info">
        <h3><?php echo htmlspecialchars($row['college_name']); ?></h3>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($row['coll_address']); ?></p>
        <p><strong>City:</strong> <?php echo htmlspecialchars($row['coll_city']); ?></p>
        <!--<p>Course: <?php echo htmlspecialchars($row['courses']); ?></p>-->
        <p><strong>Degree:</strong> <?php echo htmlspecialchars($row['degree']); ?></p>
        <p><strong>Fees:</strong> <?php echo htmlspecialchars($row['fees']); ?></p>


        <!-- Form to add college to wishlist -->
        <form method="POST" action="add_to_wishlist2.php" class="d-inline">
          <input type="hidden" name="college_name" value="<?php echo htmlspecialchars($row['college_name']); ?>">
          <button type="submit" class="btn btn-outline-success add-to-wishlist-btn">
            Add to wishlist
          </button>
        </form>

        <button class="view-college-btn" onclick="window.location.href='<?php echo htmlspecialchars($row['coll_website']); ?>'">View College</button>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No colleges found matching your criteria.</p>
  <?php endif; ?>
</div>

</body>

</html>

<?php
// Close the database connection
$conn->close();
?>
