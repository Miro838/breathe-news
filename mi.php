<?php
include('db/connection.php'); // Include your database connection

session_start();
$userId = $_SESSION['user_id']; // Assuming user is logged in and user ID is stored in session

// Fetch user data from the 'admin_login' table
$stmt = $conn->prepare("SELECT * FROM admin_login WHERE id = ?");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch user profile data from 'user_profiles' table
// Fetch user profile data from 'user_profiles' table
$stmtProfile = $conn->prepare("SELECT first_name, last_name, profile,bio FROM user_profiles WHERE user_id = ?");
$stmtProfile->bind_param('i', $userId);
$stmtProfile->execute();
$resultProfile = $stmtProfile->get_result();
$profile = $resultProfile->fetch_assoc();

// Combine first and last name
$fullName = $profile['first_name'] . ' ' . $profile['last_name'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission for updates
    if (isset($_POST['update_username'])) {
        $newUsername = $_POST['username'];
        $stmtUpdate = $conn->prepare("UPDATE admin_login SET username = ? WHERE id = ?");
        $stmtUpdate->bind_param('si', $newUsername, $userId);
        $stmtUpdate->execute();

        // Refresh the page to show updated username
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }

    if (isset($_POST['update_email'])) {
        $newEmail = $_POST['email'];
        $stmtUpdate = $conn->prepare("UPDATE admin_login SET email = ? WHERE id = ?");
        $stmtUpdate->bind_param('si', $newEmail, $userId);
        $stmtUpdate->execute();

        // Refresh the page to show updated email
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }

    if (isset($_POST['update_bio'])) {
        $newBio = $_POST['bio'];
        $stmtUpdate = $conn->prepare("UPDATE user_profiles SET bio = ? WHERE user_id = ?");
        $stmtUpdate->bind_param('si', $newBio, $userId);
        $stmtUpdate->execute();

        // Refresh the page to show updated bio
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
    if (isset($_POST['update_fullname'])) {
        $newFirstName = $_POST['first_name'];
        $newLastName = $_POST['last_name'];
        $stmtUpdate = $conn->prepare("UPDATE user_profiles SET first_name = ?, last_name = ? WHERE user_id = ?");
        $stmtUpdate->bind_param('ssi', $newFirstName, $newLastName, $userId);
        $stmtUpdate->execute();
    
        // Refresh the page to show updated full name
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
     
    // Handle profile picture upload
    if (isset($_POST['update_profile'])) {
        if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "uploads/images/";
            $fileName = basename($_FILES["profile"]["name"]);
            $targetFile = $targetDir . $fileName;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
            // Validate the file type
            $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($imageFileType, $validExtensions)) {
                // Move the uploaded file
                if (move_uploaded_file($_FILES["profile"]["tmp_name"], $targetFile)) {
                    // Update the database with the new file path
                    $stmtUpdate = $conn->prepare("UPDATE user_profiles SET profile = ? WHERE user_id = ?");
                    $stmtUpdate->bind_param('si', $fileName, $userId);
                    if ($stmtUpdate->execute()) {
                        // Success: Refresh the page
                        header("Location: " . $_SERVER['PHP_SELF']);
                        exit();
                    } else {
                        echo "Error updating database: " . $stmtUpdate->error;
                    }
                } else {
                    echo "Error uploading file. Please try again.";
                }
            } else {
                echo "Invalid file type. Please upload a JPG, JPEG, PNG, or GIF file.";
            }
        }
    }
    
    if (!isset($_SESSION['otp_code'])) {
        // Generate a new OTP (code with letters, numbers, and special characters)
        $_SESSION['otp_code'] = bin2hex(random_bytes(8)); // Length of the OTP code (16 characters)
    }
    
    // Handle password change logic after OTP verification
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['update_password'])) {
            $enteredOtp = $_POST['otp_code']; // The OTP entered by the user
            $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
            // Verify the entered OTP with the stored one
            if ($enteredOtp === $_SESSION['otp_code']) {
                // OTP is valid, proceed with updating the password
                $stmtUpdate = $conn->prepare("UPDATE admin_login SET password_hash = ? WHERE id = ?");
                $stmtUpdate->bind_param('si', $newPassword, $userId);
                $stmtUpdate->execute();
    
                // Clear OTP session after successful password change
                unset($_SESSION['otp_code']);
    
                // Refresh the page or redirect
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                // OTP is invalid
                echo "Invalid OTP code. Please try again.";
            }
        }
    }
    // Check if 'otp_code' is set before accessing it
if (isset($_POST['otp_code'])) {
    $otp_code = $_POST['otp_code'];

    // Continue with the OTP verification logic here
} else {
    // Handle the case where OTP is not set (you could show an error message or just skip)
    echo "OTP code is required.";
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if any form field is being posted
    var_dump($_POST);  // This will print all form data
    die();  // Stops script execution, so you can check the output
}

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/a.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 14px;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        /* First Header */
        .header-main {
            background-color: white;
        }
        .navbar-brand {
            font-weight: bold;
        }

        /* Second Header */
        .header-sub {
            margin-top: 10px;
            background-color: #f8f9fa;
            color: #333;
            padding: 8px 20px;
            display: flex;
            justify-content: center;
            border-bottom: 1px solid #ddd;
        }

        .header-sub a {
            margin: 0 8px;
            text-decoration: none;
            color: gray;
            font-size: 0.8rem;
            font-weight: bold;
            transition: color 0.3s ease-in-out;
        }

        .header-sub a:hover {
            color: purple;
        }

        /* Content Area */
        .content {
            flex-grow: 1;
            padding: 10px 20px;
            overflow-y: auto;
        }

        /* Ensure the edit section is scrollable if it's too long */
        #editSection {
            max-height: 60vh; /* Limit the height of the edit form */
            overflow-y: auto;
        }

        /* Make sure the page content is scrollable */
        .scrollable-content {
            max-height: 100vh;
            overflow-y: auto;
        }
        /* Update the dropdown positioning */
.dropdown {
    position: absolute;
    top: 30%; /* Adjust this value to vertically center it with the profile image */
    right: 20px; /* Move the dropdown to the right */
    transform: translateY(-30%); /* Center the dropdown vertically */
    z-index: 10; /* Ensure it appears above other content */
}

        .profile-info {
            margin-top: 20px;
        }

        .profile-info p {
            font-size: 1rem;
            color: #333;
        }
        .container1 {
            width: 80%;  /* Set width as percentage or fixed value */
            margin: 0 auto; /* Center the container */
            padding: 20px; /* Add padding inside the container */
            background-color: #f9f9f9; /* Light background color */
            border-radius: 8px; /* Optional rounded corners */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Optional box shadow */
        }
    </style>
</head>
<body>
    <!-- First Header -->
    <div class="header-main">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand" href="#">BREATHE NEWS</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="home1.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="category1.php">Categories</a></li>
                        <li class="nav-item"><a class="nav-link" href="quiz1.php">Quizzes</a></li>
                        <li class="nav-item"><a class="nav-link" href="polls1.php">Polls</a></li>
                        <li class="nav-item"><a class="nav-link" href="hiringoffers1.php">Hiring Offers</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Manage Account</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-primary text-white" style="background-color:purple" href="logout.php">Log Out</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <!-- Second Header -->
    <div class="header-sub">
        <nav class="sub-header">
            <a href="#">Manage Account</a>
            <a href="account-activity.php">Account Activity</a>
            <a href="comments-history.html">Comments History</a>
            <a href="ratings-history.html">Ratings History</a>
            <a href="saves-history.html">Saves History</a>
            <a href="quiz-history.html">Quiz Participation History</a>
            <a href="poll-history.html">Poll Participation History</a>
            <a href="hiring-history.html">Hiring Offers History</a>
        </nav>
    </div>



    <div class="scrollable-content">
        <div class="container mt-5">
            <div class="d-flex justify-content-between">
                <div>
                    <h2>User Profile</h2>
                    <div class="profile-info">
                    <img src="uploads/images/<?php echo htmlspecialchars($profile['profile']); ?>" 
     alt="Profile Picture" class="img-fluid" 
     style="width: 1000px; height: 150px; border-radius: 50%; object-fit: cover;">
<br> <br>
                       Username: <p id="username" class="container1" style="width:1000px;background-color:#d3c1ff"><?php echo htmlspecialchars($user['username']); ?></p><br><br>
                       Email: <p id="email" class="container1" style="width:1000px;background-color:#d3c1ff"><?php echo htmlspecialchars($user['email']); ?></p><br><br>
                       fullname: <p id="fullname" class="container1" style="width:1000px;background-color:#d3c1ff"><?php echo htmlspecialchars($fullName); ?></p><br><br>
                       Bio: <p id="bio" class="container1" style="width:1000px;background-color:#d3c1ff"><?php echo htmlspecialchars($profile['bio']); ?></p><br><br>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle" type="button" id="settingsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical" style="font-size: 1.5rem;"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="settingsDropdown">
                        <li><a class="dropdown-item" href="#" onclick="editInfo('username')">Edit Username</a></li>
                        <li><a class="dropdown-item" href="#" onclick="editInfo('email')">Edit Email</a></li>
                        <li><a class="dropdown-item" href="#" onclick="editInfo('bio')">Edit Bio</a></li>
                        <li><a class="dropdown-item" href="#" onclick="editInfo('password')">Edit Password</a></li>
                        <li><a class="dropdown-item" href="#" onclick="editInfo('fullname')">Edit NAme</a></li>
                         <li><a class="dropdown-item" href="#" onclick="editInfo('profile')">Edit Profile Picture</a></li>
                    </ul>
                </div>
            </div>


            <!-- Edit Form -->
            <div id="editSection" class="mt-4" style="display:none;">
    <h3>Edit User Info</h3>
    <!-- Add enctype attribute for file upload -->
    <form id="editForm" method="POST" enctype="multipart/form-data">

        <!-- Edit Profile Picture -->
        <div class="mb-3" id="editProfilePictureSection" style="display:none;">
            <label for="profile" class="form-label">Edit Profile Picture</label>
            <input type="file" class="form-control" name="profile" accept="image/*">
            <button type="submit" name="update_profile" class="btn btn-primary">Save Changes</button>
        </div>

                    <!-- Edit Username -->
                    <div class="mb-3" id="editUsernameSection" style="display:none;">
                        <label for="username" class="form-label">Edit Username</label>
                        <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" placeholder="Enter new username">
                        <button type="submit" name="update_username" class="btn btn-primary">Save Changes</button>
                    </div>

                    <!-- Edit Email -->
                    <div class="mb-3" id="editEmailSection" style="display:none;">
                        <label for="email" class="form-label">Edit Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Enter new email">
                        <button type="submit" name="update_email" class="btn btn-primary">Save Changes</button>
                    </div>

                    <div class="mb-3" id="editfullnameSection" style="display:none;">
    <label for="first_name" class="form-label">Edit First Name</label>
    <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($profile['first_name']); ?>">
    
    <label for="last_name" class="form-label">Edit Last Name</label>
    <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($profile['last_name']); ?>">
    
    <button type="submit" name="update_fullname" class="btn btn-primary">Save Changes</button>
</div>


                    <!-- Edit Bio -->
                    <div class="mb-3" id="editBioSection" style="display:none;">
                        <label for="bio" class="form-label">Edit Bio</label>
                        <textarea class="form-control" name="bio" rows="3"><?php echo htmlspecialchars($profile['bio']); ?></textarea>
                        <button type="submit" name="update_bio" class="btn btn-primary">Save Changes</button>
                    </div>

                    <!-- Edit Password Section -->
<div class="mb-3" id="editPasswordSection" style="display:none;">
    <label for="password" class="form-label">Edit Password</label>
    <input type="password" class="form-control" name="password" placeholder="Enter new password" required>
    <input type="checkbox" id="togglePassword"> Show Password
    <!-- Display OTP Code to the user -->
    <div>
        <label for="otp_code" class="form-label">Enter OTP Code (generated for password change)</label>
        <input type="text" class="form-control" name="otp_code" placeholder="Enter OTP code" required>
        <small>Your OTP code is: <strong><?php echo $_SESSION['otp_code']; ?></strong></small>
    </div>
    
    <button type="submit" name="update_password" class="btn btn-primary">Save Changes</button>
</div>

 <!-- Form to send OTP -->
<form method="POST">
    <button type="submit" name="request_otp">Send OTP</button>
</form>

<!-- OTP Input Form (Only show if OTP has been sent) -->
<?php
if (isset($_POST['request_otp'])) {
    // Generate OTP and send it to the user (not shown in this example)
    echo '<form method="POST">
            <label for="otp_code">Enter OTP:</label>
            <input type="text" name="otp_code" required>
            <button type="submit" name="verify_otp">Verify OTP</button>
          </form>';
}
?>

<?php
// OTP verification logic
if (isset($_POST['verify_otp']) && isset($_POST['otp_code'])) {
    $otp_code = $_POST['otp_code'];

    // Add OTP verification code logic here (e.g., compare to the one sent)
    echo "OTP code entered: " . htmlspecialchars($otp_code);
}
?>


                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      function editInfo(field) {
    // Hide all sections before showing the relevant one
    document.getElementById('editSection').style.display = 'block';
    document.getElementById('editUsernameSection').style.display = 'none';
    document.getElementById('editEmailSection').style.display = 'none';
    document.getElementById('editBioSection').style.display = 'none';
    document.getElementById('editPasswordSection').style.display = 'none';
    document.getElementById('editfullnameSection').style.display = 'none';
    document.getElementById('editProfilePictureSection').style.display = 'none'; // Hide profile pic section by default

    // Show the relevant edit section
    if (field === 'username') {
        document.getElementById('editUsernameSection').style.display = 'block';
    } else if (field === 'email') {
        document.getElementById('editEmailSection').style.display = 'block';
    } else if (field === 'bio') {
        document.getElementById('editBioSection').style.display = 'block';
    } else if (field === 'password') {
        document.getElementById('editPasswordSection').style.display = 'block';
    } else if (field === 'fullname') {
        document.getElementById('editfullnameSection').style.display = 'block';
    } else if (field === 'profile') {
        document.getElementById('editProfilePictureSection').style.display = 'block'; // Show profile pic form
    }
}
// JavaScript to toggle password visibility
document.getElementById('togglePassword').addEventListener('change', function() {
        var passwordField = document.getElementById('password');
        if (this.checked) {
            passwordField.type = 'text'; // Show password
        } else {
            passwordField.type = 'password'; // Hide password
        }
    });
    </script>
</body>
</html>

