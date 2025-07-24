<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['userId'])) {
  header("Location: login.html"); // Redirect to login if user is not logged in
  exit();
}

$userId = $_SESSION['userId'];
// Fetch user details
$sql = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CollegeFinder</title>
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
  <!-- Add Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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
    gap: 30px; /* Space between navbar items */
    flex-grow: 1; /* Stretch the navbar items to occupy available space */
    justify-content: space-around; /* Space them out evenly */
    margin-right: 5px; /* Add some space before the welcome message */
  }

  .navbar-nav a {
    font-size: 20px; /* Increased font size for links */
    color: #ffffff; /* White text color */
    text-decoration: none;
    padding: 10px 15px; /* Padding around links */
    border-radius: 5px; /* Rounded corners for links */
    transition: background-color 0.3s, color 0.3s; /* Smooth transitions */
    text-align: center; /* Center the text */
    flex: 1; /* Make each nav item take up equal space */
  }

  .navbar-nav a:hover {
      background-color: rgba(255, 255, 255, 0.2); /* Light background on hover */
      color: #f0f0f0; /* Slightly lighter text color on hover */
  }
  /* Import Google Font */
@import url('https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&family=Pacifico&display=swap');

  /* Welcome Message Styles */
  .welcome-message {
    
    font-weight: 450;
    color: light grey; /* Soft yellow color */
    margin-top: 10px;
    font-size: 20px; /* Set the font size */
    letter-spacing: 1px; /* Add some space between the letters */
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5); /* Text shadow for effect */
}
  /*Test prep dropdown*/
  .dropdown-item {
  color: black !important;
  text-allign:left;
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
/* Header Carousel */
.carousel-item img {
  filter: brightness(65%);
  
}

.carousel-caption h5 {
  font-size: 28px;
  text-transform: uppercase;
  letter-spacing: 2px;
  color: #f5f5f5;
  animation: slideIn 2s ease-in-out;
  box-shadow: #2a2a2a;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Main Section */
.main {
  padding: 50px 20px;
}

.animated-text {
  color: #e0e0e0;
  text-align: center;
  font-size: 40px;
  font-family: 'Roboto Slab', serif;
  letter-spacing: 1.5px;
  animation: fadeIn 1.5s ease-in;
}

 /* Center search box inside container */
  /* Container styling remains the same */
    /* Center the search box */
    .search-box {
      display: flex;
      justify-content: center;
      width: 100%;
      position:relative;
      left:-250px;
      top:900px;
    }

    /* Search form adjustments */
    .search-form {
      display: flex;
      justify-content: center;
      width: 10px;
      height:10px;
    }

    /* Input field with smaller height */
    .search-input {
      width: 450px; /* Medium length */
      height:70px;/* Smaller padding for height */
      font-size: 20px; /* Reduced font size */
      border-radius: 40px; /* Slightly rounded edges */
      background-color: #363331;
      color: #fff;
      border: 2px solid #555;
      transition: all 0.3s ease;
    }

    .search-input:focus {
      border-color: #ff9800;
      background-color: #f5f0f0;
      box-shadow: 0px 3px 8px rgba(255, 152, 0, 0.3);
      outline: none;
    }

    /* Button with smaller height */
    .search-btn {
      
      width: 200px;
      height: 70px;; /* Reduced padding for height */
      font-size: 20px; /* Reduced font size */
      border-radius: 50px; /* Rounded edges */
      background-color: #4caf50;
      color: white;
      border: none;
      transition: all 0.3s ease-in-out;
    
      
    }

    .search-btn:hover {
      background-color: #388e3c;
      box-shadow: 0px 3px 8px rgba(76, 175, 80, 0.5);
      transform: scale(1.05);
    }

    /* Adding slight margin between input and button */
    .me-2 {
      margin-right: 8px;
      
    }
/* Suggestions Box */
.suggestions-box {
  display: none; /* Initially hidden, displayed dynamically */
  position: absolute;
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 8px;
  max-height: 250px;
  overflow-y: auto;
  z-index: 9999;
  width: 50%;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  margin-top: 70px; /* Increased margin to shift the box below the search bar */
}

.suggestion-item {
  padding: 12px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  font-size: 16px;
  color: #333;
}

.suggestion-item:hover {
  background-color: #f5f5f5;
  color: #007bff; /* Highlight color */
}

.suggestions-box::-webkit-scrollbar {
  width: 6px;
}

.suggestions-box::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 8px;
}

.suggestions-box::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 8px;
}

.suggestions-box::-webkit-scrollbar-thumb:hover {
  background: #555;
}


/* Container Styling */
.container {
  max-width: 900px;
  margin: 60px auto;
  padding: 40px;
  background-color: #222;
  border-radius: 15px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
  transition: transform 0.3s, box-shadow 0.3s;
  position: relative;
  top:400px;
}


.container:hover {
  transform: translateY(-10px);
  box-shadow: 0 10px 25px rgb(220, 214, 214);
}

/* Filters Section */
.filter-section {
  padding: 30px;
  background-color: 0 4px 10px rgba(0, 0, 0, 0.3);
  border-radius: 12px;
  margin-top: 40px;
  color: #f0f0f0;
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
}
/* Make the placeholder text bold */
.filter-select::placeholder {
  font-weight: 16px;
  color:#fff ; /* Lighter color for the placeholder */
}


.filter-select:hover {
  background-color: #444;
  border-color: #efe3de;
  box-shadow: 0 10px 25px rgb(220, 214, 214);
}

.filter-select:focus {
  background-color: #555;
  border-color: #f7ece8;
}
/* Dropdown Container */
.dropdown-select {
  position: absolute;
  width: 100%;
  max-height: 200px;
  overflow-y: auto;
  background-color: #333;
  border: 2px solid #444;
  border-radius: 6px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
  display: none; /* Hidden by default, will be shown when typing */
  z-index: 10;
}

.filter-select:focus + .dropdown-select {
  display: block; /* Show dropdown when input is focused */
}

.dropdown-option {
  padding: 15px;
  color: #fff;
  cursor: pointer;
}

.dropdown-option:hover {
  background-color: #444;
}

.dropdown-option:first-child {
  background-color: #555;
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
  margin:2.5%;
  position:relative;
  left:-20px;
}

.btn-filter:hover {
  background-color: #1c62f8;
  box-shadow: 0 6px 15px rgba(255, 87, 34, 0.5);
}

.btn-filter:focus {
  background-color: #0033ff;
  box-shadow: 0 6px 15px rgba(255, 61, 0, 0.6);
}
/* Result Cards */
.college {
  background-color: #2a2a2a;
  color: #fff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
  transition: box-shadow 0.3s ease;
  margin-bottom: 20px; /* Adds space between college blocks vertically */
}

.college:hover {
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
}

.college h3 {
  color: #fffcfb;
  margin-bottom: 10px;
}

.college p {
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
/*styles for button mba and mtech colleges*/
/* Button Styling */
.btn-filter-page {
      padding: 12px 24px;
      font-size: 25px;
      background-color: #e00909;
      color: #fff;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease-in-out;
      position: relative;
      left:320px;
      top:400px;
    }

    .btn-filter-page:hover {
      background-color: #ff3f3f;
      transform: translateY(-10px);
      box-shadow: 0 10px 25px rgb(220, 214, 214);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    /* Centering the button */
    .button-container {
      text-align: center;
      margin-top: 40px;
      
    }
    /*sharing links style*/
    .share-link-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 20px;
}

.share-link-container input {
  width: 60%;
}

#sharePoints p {
  color: green;
  font-weight: bold;
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
          <li class="nav-item"><a class="nav-link" href="chatbot.html">Ask Ai</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="testPrepDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Test Prep</a>
            <ul class="dropdown-menu" aria-labelledby="testPrepDropdown">
              <li><a class="dropdown-item" href="cet.html">CET</a></li>
              <li><a class="dropdown-item" href="neet.html">NEET</a></li>
              <li><a class="dropdown-item" href="cat.html">CAT</a></li>
              <li><a class="dropdown-item" href="gate.html">GATE</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="leaderboard.php">Leaderboards</a>
          </li>
          <!-- Welcome Message -->
      <div class="welcome-message">
        Welcome, <?php echo htmlspecialchars($username); ?>!
      </div>
        </ul>
      </div>
    </div>
  </nav>

  <header>
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="https://assets.telegraphindia.com/abped/2020/Sep/1600357172_iit-roorkee.jpg" class="d-block w-100" alt="IIT Roorkee">
          <div class="carousel-caption d-none d-md-block">
            <h5>Indian Institute Of Technology Roorkee</h5>
          </div>
        </div>
        <div class="carousel-item">
          <img src="https://www.nitt.edu/home/landing_img_5.jpg" class="d-block w-100" alt="NIT Trichy">
          <div class="carousel-caption d-none d-md-block">
            <h5>National Institute of Technology Trichy</h5>
          </div>
        </div>
        <div class="carousel-item">
          <img src="https://www.amity.edu/backoffice/uploads/HomeBanner/4fblg_gurgaon.jpg" class="d-block w-100" alt="Amity University">
          <div class="carousel-caption d-none d-md-block">
            <h5>Amity University</h5>
          </div>
        </div>
      </div>
    </div>
  </header>

  <div class="main">
    <p class="animated-text">Find More than 5,000 Colleges in India</p>
  </div>

 <!-- Search and Explore Section -->

  <div class="search-box d-flex justify-content-center">
    <form action="search_results.php" method="GET" class="d-flex justify-content-center">
      <!-- Input for search query -->
      <input class="form-control me-2 search-input" type="search" id="searchQuery" name="query" placeholder="Search for colleges, city, state or union territory" autocomplete="off" required>
      <button class="btn btn-outline-success search-btn" type="submit">Search</button>

      <!-- Suggestions dropdown -->
      <div id="suggestions-box" class="suggestions-box"></div>
    </form>
  </div>


 <!-- Explore Section -->
 <div class="container"> 
  <div class="section">
      <h1>EXPLORE ALMOST EVERYTHING</h1>
      <p>
          CollegeFinder.com is an extensive search engine for students, parents, and education industry players who are seeking information.
      </p>
  </div>
</div>
<!-- Filter Section -->
<div class="container filter-section">
  <h2>Filter TOP  Colleges</h2>
  <form id="filterForm">
    <div class="form-group">
      <label for="state">State and union territory:</label>
      <select id="state" name="state" class="filter-select">
        <option value="">Select State or union territory</option>
      </select>
    </div>

    <div class="form-group">
      <label for="city">City:</label>
      <select id="city" name="city" class="filter-select">
        <option value="">Select City</option>
      </select>
    </div>
    <div class="form-group">
  <label for="courses">Courses:</label>
  <input type="text" id="course-search" class="filter-select" placeholder="Search or Select Course" oninput="filterDropdown()" onclick="showDropdown()">
  
  <!-- Simulated Dropdown using <div> -->
  <div id="dropdown-courses" class="dropdown-select">
    <div class="dropdown-option" onclick="selectCourse('')">Select Course</div>
  </div>
</div>



    <button type="submit" class="btn-filter">Apply Filters</button>
  </form>
</div>

<div class="container results-section">
  <h2>Filtered Colleges</h2>
  <div id="resultsContainer">
    <!-- Filtered results will appear here after form submission -->
  </div>
</div>
<div class="button-container"></div>
  <button class="btn-filter-page" onclick="window.location.href='filter_page.php'">Filter More MBA and M.Tech Colleges</button>
</div>
<!-- Sharing Links Feature -->
<!-- Sharing Links Feature -->
<div class="container mt-5">
  <h3>Share this Link and Earn Points</h3>
  <div class="share-link-container d-flex align-items-center">
    <input type="text" id="randomLink" class="form-control me-3" readonly>
    <button class="btn btn-primary" id="generateLinkBtn">Generate Link</button>
    <button class="btn btn-success ms-3" id="shareLinkBtn" disabled>Share Link</button>
  </div>
  <div id="shareOptions" class="mt-3"></div>
  <div id="sharePoints" class="mt-3"></div>
</div>
<!-- Script (unchanged) -->
<script>
// For search form
document.getElementById("searchQuery").addEventListener("input", function () {
  const query = this.value;

  if (query.length > 2) {
    fetch(`get_suggestions.php?q=${query}`)
      .then(response => response.json())
      .then(data => {
        const suggestionsBox = document.getElementById("suggestions-box");
        suggestionsBox.innerHTML = '';

        if (data.length > 0) {
          suggestionsBox.style.display = 'block';
          data.forEach(item => {
            const suggestion = document.createElement("div");
            suggestion.textContent = item;
            suggestion.classList.add("suggestion-item");
            suggestion.onclick = () => {
              document.getElementById("searchQuery").value = item;
              suggestionsBox.style.display = 'none';
            };
            suggestionsBox.appendChild(suggestion);
          });
        } else {
          suggestionsBox.style.display = 'none';
        }
      })
      .catch(error => console.error("Error fetching suggestions:", error));
  }
});

  // Load filter options using AJAX when the page loads
  document.addEventListener("DOMContentLoaded", function () {
    fetch("fetch_filters.php")
      .then(response => response.json())
      .then(data => {
        // Populate the state dropdown
        const stateSelect = document.getElementById("state");
        data.states.forEach(state => {
          let option = document.createElement("option");
          option.value = state;
          option.textContent = state;
          stateSelect.appendChild(option);
        });

        // Populate the city dropdown
        const citySelect = document.getElementById("city");
        data.cities.forEach(city => {
          let option = document.createElement("option");
          option.value = city;
          option.textContent = city;
          citySelect.appendChild(option);
        });
             
                // Event listener for state change
    document.getElementById("state").addEventListener("change", function () {
        const selectedState = this.value;
        const citySelect = document.getElementById("city");
        citySelect.innerHTML = '<option value="">Select City</option>'; // Reset city dropdown

        if (selectedState) {
            fetch(`fetch_cities.php?state=${encodeURIComponent(selectedState)}`)
              .then(response => response.json())
              .then(data => {
                data.cities.forEach(city => {
                  let option = document.createElement("option");
                  option.value = city;
                  option.textContent = city;
                  citySelect.appendChild(option);
                });
              });
        }
    });

        // Populate the courses dropdown
        const dropdown = document.getElementById("dropdown-courses");
            data.courses.forEach(course => {
                let optionDiv = document.createElement("div");
                optionDiv.classList.add("dropdown-option");
                optionDiv.textContent = course;
                optionDiv.setAttribute("onclick", `selectCourse('${course}')`);
                dropdown.appendChild(optionDiv);
            });
        })
        .catch(error => console.error("Error loading filter options:", error));
});


// Function to filter dropdown options based on input
function filterDropdown() {
    const searchInput = document.getElementById("course-search").value.toLowerCase();
    const options = document.querySelectorAll("#dropdown-courses .dropdown-option");
    
    options.forEach(option => {
        if (option.textContent.toLowerCase().includes(searchInput)) {
            option.style.display = ""; // Show matching options
        } else {
            option.style.display = "none"; // Hide non-matching options
        }
    });
}
// Function to select a course from the dropdown
function selectCourse(course) {
    const searchInput = document.getElementById("course-search");
    searchInput.value = course; // Set the input value to the selected course
    hideDropdown(); // Hide the dropdown after selection
}

// Show dropdown when the input is clicked
function showDropdown() {
    document.getElementById("dropdown-courses").style.display = "block";
}

// Hide dropdown
function hideDropdown() {
    document.getElementById("dropdown-courses").style.display = "none";
}
// Event listener to hide dropdown when clicking outside the input or dropdown
document.addEventListener("click", function (event) {
    const dropdown = document.getElementById("dropdown-courses");
    const searchInput = document.getElementById("course-search");

    // Check if the click is outside the dropdown and the input field
    if (!dropdown.contains(event.target) && !searchInput.contains(event.target)) {
        hideDropdown();
    }
});

// Show dropdown when clicking inside the input field
document.getElementById("course-search").addEventListener("click", function (event) {
    showDropdown();
    event.stopPropagation(); // Prevent the document click event from immediately hiding the dropdown
});
  

  // Handle form submission with AJAX
  document.getElementById("filterForm").addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent form submission

    const formData = new FormData(this);

    fetch("results.php", {
      method: "POST",
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        const resultsContainer = document.getElementById("resultsContainer");
        resultsContainer.innerHTML = ""; // Clear previous results

        if (data.length > 0) {
          data.forEach(college => {
            const collegeDiv = document.createElement("div");
            collegeDiv.classList.add("college");
            collegeDiv.innerHTML = `
              <h3>${college.college_name}</h3>
            <div class="displaycollege">
              <p><strong>Location:</strong> ${college.city}, ${college.state}</p>
              <p><strong>Courses:</strong> 
  <span id="course-short-${college.college_name}">${formatShortCourses(college.courses)}</span>
  <span id="course-full-${college.college_name}" style="display:none;">${formatFullCourses(college.courses)}</span>
  <a  href="javascript:void(0);" id="read-more-btn-${college.college_name}" onclick="toggleReadMore('${college.college_name}')">Read More</a>
</p>
              <p><strong>Genders Accepted:</strong> ${college.genders_accepted}</p>
              <p><strong>Established Year:</strong> ${college.established_year}</p>
              <!--<p><strong>Rating:</strong> ${college.rating} </p>-->
              <p><strong>University:</strong> ${college.university}</p>
              <p><strong>College Type:</strong> ${college.college_type}</p>
              <p><strong>Average Fees:</strong> ₹${new Intl.NumberFormat().format(college.average_fees)} </p>
             <!-- <p><strong>Rating:</strong> ${college.rating}</p>-->

               <button class="add-to-wishlist-btn" onclick="addToWishlist('${college.college_name}')">Add to Wishlist</button>
                <button class="view-college-btn" onclick="window.location.href='${college.college_links}'">View College</button>
             </div>
             
            `;
            resultsContainer.appendChild(collegeDiv);
          });
        } else {
          resultsContainer.innerHTML = "<p>No colleges found for the selected filters.</p>";
        }
      })
      .catch(error => console.error("Error fetching filtered results:", error));
  });


  // Helper function to format courses as a list with bullet points (Full)
function formatFullCourses(courses) {
  const courseArray = courses.split(',').map(course => course.trim());
    const uniqueCourses = [...new Set(courseArray)]; // Ensure uniqueness
    const fullArray = uniqueCourses.slice(5); // Skip the first 5 courses

    // Check if there are any remaining courses after the first 5
    if (fullArray.length === 0) {
        return '<p>No additional courses available.</p>'; // Optional message if no additional courses
    }

    return '<ul>' + fullArray.map(course => `<li>${course}</li>`).join('') + '</ul>';
}

// Helper function to format only the first few courses for short view
function formatShortCourses(courses) {
  const courseArray = courses.split(',').map(course => course.trim());
    const uniqueCourses = [...new Set(courseArray)]; // Ensure uniqueness
    const shortArray = uniqueCourses.slice(0, 5); // Show only the first 5 unique courses
    return '<ul>' + shortArray.map(course => `<li>${course}</li>`).join('') + '</ul>';
}

// Toggle between "Read More" and "Read Less"
function toggleReadMore(collegeName) {
  const shortCoursesElement = document.getElementById(`course-short-${collegeName}`);
    const fullCoursesElement = document.getElementById(`course-full-${collegeName}`);
    const readMoreBtn = document.getElementById(`read-more-btn-${collegeName}`);
    
    if (fullCoursesElement.style.display === "none") {
        // Show the full course list and hide the short list
        shortCoursesElement.style.display = "none";
        fullCoursesElement.style.display = "block";
        readMoreBtn.innerText = "Read Less"; // Update button text to 'Read Less'
    } else {
        // Show the short course list and hide the full list
        shortCoursesElement.style.display = "block";
        fullCoursesElement.style.display = "none";
        readMoreBtn.innerText = "Read More"; // Update button text to 'Read More'
    }
}

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
 // Function to generate random college links
 function generateRandomLink() {
    const randomColleges = [
    "http://localhost/Uni_Finder/index2.html"
     
    ];

    // Pick a random college link
    const randomLink = randomColleges[Math.floor(Math.random() * randomColleges.length)];

    // Set the random link in the input field
    document.getElementById("randomLink").value = randomLink;

    // Enable the share button
    document.getElementById("shareLinkBtn").disabled = false;
  }

 // Function to simulate sharing and generate points
function shareLink() {
  const link = document.getElementById("randomLink").value;

  // WhatsApp URL
  const whatsappUrl = `https://api.whatsapp.com/send?text=Check%20this%20out%20${encodeURIComponent(link)}`;

  // Open WhatsApp share link in a new tab
  window.open(whatsappUrl, '_blank');

  // Send request to PHP to update points
  fetch('share_link.php', { method: 'POST' })
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        document.getElementById("sharePoints").innerHTML = `<p>${data.error}</p>`;
      } else {
        // Display points earned and username
        document.getElementById("sharePoints").innerHTML = `<p>You, <strong>${data.username}</strong>, have earned <strong>10 points</strong>! Total points: <strong>${data.points}</strong></p>`;
      }
    })
    .catch(error => console.error('Error:', error));

  // Disable the share button after sharing
  document.getElementById("shareLinkBtn").disabled = true;
}

// Event listeners
document.getElementById("generateLinkBtn").addEventListener("click", generateRandomLink);
document.getElementById("shareLinkBtn").addEventListener("click", shareLink);
</script>
</body>
</html>
