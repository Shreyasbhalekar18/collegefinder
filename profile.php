<?php
// profile.php

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

if (!isset($_SESSION['userId'])) {
    header("Location: login.html"); // Redirect to login if user is not logged in
    exit();
}

$userId = $_SESSION['userId'];

// Fetch user details
$sql = "SELECT username,email,phonenumber,address,cet_marks,tenth_marks,twelfth_marks FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($username, $email, $phonenumber, $address, $cetMarks, $tenthMarks, $twelfthMarks);
$stmt->fetch();
$stmt->close();

// Determine profile picture path
$profilePictureDir = "uploads/profile_pictures/";
$profilePicturePath = $profilePictureDir . "profile_" . $userId . ".jpg";

if (!file_exists($profilePicturePath)) {
    $profilePicturePath = "uploads/default_profile.png"; // Path to a default profile picture
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['new_profile_picture'])) {
    $targetFile = $profilePictureDir . "profile_" . $userId . ".jpg";
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($_FILES["new_profile_picture"]["name"], PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["new_profile_picture"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["new_profile_picture"]["size"] > 500000) {
        echo "Sorry, your file is too large should be under 500 KB.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "jpeg") {
        echo "Sorry, only JPG and JPEG files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["new_profile_picture"]["tmp_name"], $targetFile)) {
            // Refresh the page to show the new profile picture
            header("Location: profile.php");
            exit();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
// Directory for storing certificates
$certificateDir = "uploads/certificates/";

// Handling certificate upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['new_certificate'])) {
    $targetDir = $certificateDir;
    $fileName = basename($_FILES['new_certificate']['name']);
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $newFileName = "certificate_" . $userId . "_" . time() . "." . $fileExtension;
    $targetFile = $targetDir . $newFileName;

    // Check if file is a PDF
    if ($fileExtension != "pdf") {
        echo "Sorry, only PDF files are allowed.";
    } else {
        if (move_uploaded_file($_FILES["new_certificate"]["tmp_name"], $targetFile)) {
            
            // Redirect to profile page to display the new certificate
            header("Location: profile.php");
            exit();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Fetch certificates for the user
$certificates = glob($certificateDir . "certificate_" . $userId . "_*.pdf");

// Check if a certificate deletion is requested
if (isset($_GET['delete_certificate'])) {
    $filePathToDelete = urldecode($_GET['delete_certificate']);
    $realFilePath = realpath($filePathToDelete);
    // Delete the file from the server
    if ($realFilePath && strpos($realFilePath, realpath($certificateDir)) === 0 && file_exists($realFilePath)) {
        unlink($realFilePath);
    }

    // Redirect to avoid re-execution on refresh
    header("Location: profile.php");
    exit();
}

// Fetch the wishlist from the users table
$query = "SELECT wishlist FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$wishlist = !empty($row['wishlist']) ? json_decode($row['wishlist'], true) : []; // Decode JSON wishlist


$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Scheherazade+New&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Goudy+Bookletter+1911&family=Roboto+Slab:wght@800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #f4f4f4;
            background-color: #121212;
            margin: 0;
            padding: 0;
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
        .container {
            max-width: 100%;
            margin: 4rem auto;
            padding: 4rem;
            background-color: #1e1e1e;
            border-radius: 8px;
        }
        .profile-header {
            display: flex;
            align-items: center;
            justify-content: left;
            margin-bottom: 2rem;
        }
        .profile-pic {
            border-radius: 50%;
            border: 4px solid rgb(20, 23, 199);
            height: 200px;
            width: 200px;
            object-fit: cover;
            margin-right: 1rem;
        }
        .profile-info h2 {
            margin: 0;
            font-size: 1.5rem;
        }
        .change-profile-btn,.upload-certificate-btn,button {
            background-color: #333;
            color: #f4f4f4;
            border: none;
            padding: 0.5rem 1rem;
            margin-top: 1rem;
            border-radius: 4px;
            cursor: pointer;
        }
        .change-profile-btn:hover,.upload-certificate-btn:hover,button{
            background-color: #555;
        }
        .hidden {
            display: none;
        }
        .certificates-list {
    margin-top: 20px;
}

.certificate-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    border-radius: 8px;
    background-color: #f4f4f4;
    margin-bottom: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease;
}

.certificate-item:hover {
    background-color: #e0e0e0;
}

.certificate-link {
    color: #333;
    text-decoration: none;
    font-weight: bold;
    font-family: 'Arial', sans-serif;
}

.certificate-link:hover {
    text-decoration: underline;
    color: #007bff;
}

.delete-certificate {
    color: #ff5c5c;
    font-size: 20px;
    text-decoration: none;
    margin-left: 10px;
    transition: transform 0.3s ease;
}


        .upload-section, .upload-form,.profile-details {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #333333;
            border-radius: 8px;
            background-color: #252525;
        }
        .upload-form label, .profile-details p {
            color: #b3b3b3;
        }
        .marks-input {
            width: 100%;
            padding: 0.5rem;
            margin: 0.5rem 0;
            background-color: #1f1f1f;
            border: 1px solid #333333;
            border-radius: 4px;
            color: #e0e0e0;
        }
        .certificate-item {
    position: relative;
    padding-right: 25px;
}

.delete-certificate {
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    color: #ff4d4d;
    text-decoration: none;
    font-size: 1.2rem;
    font-weight: bold;
    padding: 0 5px;
}

.delete-certificate:hover {
    color: #ff1a1a;
}
/* Wishlist Section */
.wishlist-section {
    max-width: 1200px; /* Increased max-width for broader layout */
    margin: 4rem auto;
    padding: 2rem;
    background-color: #1e1e1e;
    border-radius: 8px;
    color: #ffffff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* Adds some depth to the section */
}

/* Wishlist Container */
.wishlist-container {
    display: flex;
    flex-wrap: wrap; /* Enables wrapping for better use of space */
    gap: 20px;
    justify-content: space-between;
}

/* Card Design for Wishlist Items */
.wishlist-item {
    background-color: #2c2c2c;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
    width: 100%;
    max-width: 320px; /* Sets a limit to the card size */
    padding: 20px;
    text-align: center;
}

.wishlist-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); /* Adds a hover effect for depth */
}

/* Card Title */
.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 15px;
}

/* Card Text */
.card-body p {
    color: #b3b3b3;
    margin: 5px 0;
    font-size: 0.9rem;
}

/* Remove Button */
.btn-outline-danger {
    border: 2px solid #e74c3c;
    color: #e74c3c;
    background: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    margin-top: 10px;
    transition: all 0.2s;
}

.btn-outline-danger:hover {
    background-color: #e74c3c;
    color: white;
    border-color: #e74c3c;
}

/* Empty Wishlist Alert */
.alert-info {
    background-color: #444444;
    border-color: #888888;
    padding: 20px;
    border-radius: 8px;
    font-size: 1.1rem;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .wishlist-item {
        width: 100%; /* Takes full width on smaller screens */
    }
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
    
    <div class="container">
        <div class="profile-header">
            <img src="<?php echo htmlspecialchars($profilePicturePath); ?>" alt="Profile Picture" class="profile-pic">
            <div class="profile-info">
                <h2 style="position:relative; top:-15px;"><?php echo htmlspecialchars($username); ?></h2>
                <form method="post" enctype="multipart/form-data">
                    <label class="change-profile-btn" for="new_profile_picture">Change Profile</label>
                    <input type="file" name="new_profile_picture" id="new_profile_picture" class="hidden" accept="image/*" onchange="this.form.submit()">
                </form>
                <a style="font-size:bold; color:red; position:relative; top:15px;" href="logout.php"><h3>Logout</h3></a>
            </div>
        </div>
      
        <div class="certificates-list">
    <h3>Uploaded Certificates:</h3>
    <?php foreach ($certificates as $certificate) : ?>
        <div class="certificate-item">
            <a href="<?php echo $certificate; ?>" target="_blank" class="certificate-link">
                <?php echo basename($certificate); ?>
            </a>
            <a href="?delete_certificate=<?php echo urlencode($certificate); ?>" class="delete-certificate">&#x2716;</a>
        </div>
    <?php endforeach; ?>
         </div>
        <form method="post" enctype="multipart/form-data">
            <button class="upload-certificate-btn" type="button" onclick="document.getElementById('new_certificate').click()">Upload New Certificate</button>
            <input type="file" name="new_certificate" id="new_certificate" class="hidden" accept="application/pdf" onchange="this.form.submit()">
        </form>
        <div class="profile-details">
            <h1>Profile Details:</h1>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($phonenumber); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
            <p><strong>CET Marks:</strong> <span id="cet-marks"><?php echo htmlspecialchars($cetMarks); ?>%</span></p>
            <p><strong>10th Boards Marks:</strong> <span id="tenth-marks"><?php echo htmlspecialchars($tenthMarks); ?>%</span></p>
            <p><strong>12th Boards Marks:</strong> <span id="twelfth-marks"><?php echo htmlspecialchars($twelfthMarks); ?>%</span></p>
        </div>
        <div class="upload-form">
            <h1>Update Marks:</h1>
            <form id="marksForm" action="update_marks.php" method="POST">
                <label for="cetMarks">CET Marks:</label>
                <input type="text" id="cetMarks" name="cetMarks" placeholder="Enter CET Marks" class="marks-input" required>
                
                <label for="tenthMarks">10th Marks:</label>
                <input type="text" id="tenthMarks" name="tenthMarks" placeholder="Enter 10th Marks" class="marks-input" required>
                
                <label for="twelfthMarks">12th Marks:</label>
                <input type="text" id="twelfthMarks" name="twelfthMarks" placeholder="Enter 12th Marks" class="marks-input" required>
                
                <button type="submit" class="button" action="update_marks.php">Update Marks</button>
                
            </form>
            
        </div>
        </div>
 <!-- Wishlist Section in profile.php -->
 <div class="wishlist-section container mt-5">
        <h3 class="text-center mb-4 text-light">Your Wishlist</h3>

        <?php if (!empty($wishlist)) { ?>
            <div class="wishlist-container row justify-content-center">
                <?php foreach ($wishlist as $item) { ?>
                    <div class="col-md-6 col-lg-4 mb-4" >
                        <div class="wishlist-item card shadow-sm" >
                            <div class="card-body text-center">
                                <h5 class="card-title mb-3 text-light">
                                    <?php echo htmlspecialchars($item['college_name']); ?>
                                </h5>
                                <form method="POST" action="remove_wishlist.php" class="d-inline">
                                    <input type="hidden" name="college_name" value="<?php echo htmlspecialchars($item['college_name']); ?>">
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="alert alert-info text-center text-light">
                <strong>Your wishlist is empty.</strong>
            </div>
        <?php } ?>
    </div>
    
</body>
</html>
