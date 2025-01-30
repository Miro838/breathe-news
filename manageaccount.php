<?php
session_start();
require_once 'db/connection.php'; // Assuming this includes your DB connection setup

// Assuming you have the user's ID stored in the session
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if the user is not logged in
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch user data from the admin_login table
$query = "SELECT * FROM admin_login WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Fetch user profile data
$queryProfile = "SELECT * FROM user_profiles WHERE user_id = ?";
$stmtProfile = $conn->prepare($queryProfile);
$stmtProfile->bind_param('i', $userId);
$stmtProfile->execute();
$profile = $stmtProfile->get_result()->fetch_assoc();

// Handle Profile Picture Update
// Handle Profile Picture Update
if (isset($_POST['update_profile']) && isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
    $targetDir = "uploads/images/"; // Target directory for uploads
    $fileName = basename($_FILES["profile"]["name"]);
    $targetFile = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Validate file extension
    $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($imageFileType, $validExtensions)) {
        if (move_uploaded_file($_FILES["profile"]["tmp_name"], $targetFile)) {
            // Update database with the new profile picture file name
            $stmtUpdate = $conn->prepare("UPDATE user_profiles SET profile = ? WHERE user_id = ?");
            $stmtUpdate->bind_param('si', $fileName, $userId);
            $stmtUpdate->execute();
            // Redirect back to the page to show the updated profile picture
            header("Location: ".$_SERVER['PHP_SELF']);
            exit(); // Always use exit() after header redirect to prevent further script execution
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
    }
}


// Handle Username Update
if (isset($_POST['update_username'])) {
    $newUsername = $_POST['username'];
    $stmtUpdate = $conn->prepare("UPDATE admin_login SET username = ? WHERE id = ?");
    $stmtUpdate->bind_param('si', $newUsername, $userId);
    $stmtUpdate->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
}

// Handle Email Update
if (isset($_POST['update_email'])) {
    $newEmail = $_POST['email'];
    $stmtUpdate = $conn->prepare("UPDATE admin_login SET email = ? WHERE id = ?");
    $stmtUpdate->bind_param('si', $newEmail, $userId);
    $stmtUpdate->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
}

// Handle Bio Update
if (isset($_POST['update_bio'])) {
    $newBio = $_POST['bio'];
    $stmtUpdate = $conn->prepare("UPDATE user_profiles SET bio = ? WHERE user_id = ?");
    $stmtUpdate->bind_param('si', $newBio, $userId);
    $stmtUpdate->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
}

// Handle Password Update with OTP
if (isset($_POST['update_password'])) {
    if ($_POST['otp_code'] == $_SESSION['otp_code']) {
        $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmtUpdate = $conn->prepare("UPDATE admin_login SET password_hash = ? WHERE id = ?");
        $stmtUpdate->bind_param('si', $newPassword, $userId);
        $stmtUpdate->execute();
        header("Location: ".$_SERVER['PHP_SELF']);
    }
}
if (isset($_POST['update_name'])) {
    $newFirstName = $_POST['first_name'];
    $newLastName = $_POST['last_name'];
    $stmtUpdate = $conn->prepare("UPDATE user_profiles SET first_name = ?, last_name = ? WHERE user_id = ?");
    $stmtUpdate->bind_param('ssi', $newFirstName, $newLastName, $userId);
    $stmtUpdate->execute();
    header("Location: " . $_SERVER['PHP_SELF']);
}
function generateComplexOTP($length = 15) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-+=<>?';
    $otp = '';
    $charactersLength = strlen($characters);

    for ($i = 0; $i < $length; $i++) {
        $otp .= $characters[rand(0, $charactersLength - 1)];
    }
    return $otp;
}

// Generate a new complex OTP every time the page is loaded
$_SESSION['otp_code'] = generateComplexOTP();
$_SESSION['otp_expiry'] = time() + 300; // OTP valid for 5 minutes
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
    <link rel="stylesheet" href="style/m.css">
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
            <a href="comments-history.php">Comments History</a>
            <a href="ratings-history.php">Ratings History</a>
            <a href="saves-history.php">Saves History</a>
            <a href="quiz-history.php">Quiz Participation History</a>
            <a href="poll-history.php">Poll Participation History</a>
            <a href="hiring-history.php">Hiring Offers History</a>
            <a href="testimonials.php">Testiomails History</a>
        </nav>
    </div>



    <div class="scrollable-content">
    <div class="container mt-5">
        <div class="d-flex justify-content-between">
            <div>
                <div id="userInfo">
                    <h2>User Information</h2>
                    <p style="color:black"><strong>Profile Picture:</strong></p>
                    <?php if (!empty($profile['profile'])): ?>
                        <img src="uploads/images/<?php echo htmlspecialchars($profile['profile']); ?>" class="img-fluid" 
                        style="width: 1000px; height: 150px; border-radius: 50%; object-fit: cover;">
                    <?php else: ?>
                        <p style="color:black" >No profile picture uploaded.</p>
                    <?php endif; ?>
                    <p style="color:black"><strong>Username:</strong> <p style="color:black" class="container1" style="width:1000px;background-color:#d3c1ff"><span class="container1" style="width:1000px;background-color:#d3c1ff"><?php echo htmlspecialchars($user['username']); ?></span></p>
                    <p style="color:black"><strong>Email:</strong> <p style="color:black" class="container1" style="width:1000px;background-color:#d3c1ff"><span class="container1" style="width:1000px;background-color:#d3c1ff"><?php echo htmlspecialchars($user['email']); ?></span></p>
                    <p style="color:black"><strong>Full Name:</strong><p style="color:black" class="container1" style="width:1000px;background-color:#d3c1ff"> <span class="container1" style="width:1000px;background-color:#d3c1ff"><?php echo htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name']); ?></span></>
                    <p style="color:black"><strong>Bio:</strong> <p style="color:black" class="container1" style="width:1000px;background-color:#d3c1ff"><span class="container1" style="width:1000px;background-color:#d3c1ff"><?php echo htmlspecialchars($profile['bio']); ?></span></p>
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
                        <li><a class="dropdown-item" href="#" onclick="editInfo('name')">Edit Name</a></li>
                        <li><a class="dropdown-item" href="#" onclick="editInfo('profile')">Edit Profile Picture</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Name Section -->
    <div id="editNameSection" class="edit-form-section" style="display:none; width: 100%;padding-left:125px;border-radius: 15px;">
    
        <form method="POST" enctype="multipart/form-data">
           <p style="color:black"> <label for="first_name" style="font-weight:bold">First Name</label></p>
            <p style="color:black"> <input type="text" name="first_name" value="<?php echo htmlspecialchars($profile['first_name']); ?>" required class="container1" 
            style="width:1000px;background-color:#d3c1ff; border-radius:8px;text-align: left;"> </p>
           <p style="color:black"><label for="last_name" style="font-weight:bold">Last Name</label></p> 
           <p style="color:black"><input type="text" name="last_name" value="<?php echo htmlspecialchars($profile['last_name']); ?>"
            required class="container1" style="width:1000px;background-color:#d3c1ff; border-radius:8px;text-align: left;">
            </p> <button type="submit" name="update_name">Save Changes</button>
        </form>
    </div>

    <!-- Profile Picture Section -->
    <div id="editProfilePictureSection" style="display:none;">
        <form method="POST" enctype="multipart/form-data">
            <label for="profile">Edit Profile Picture</label>
            <input type="file" name="profile" accept="image/*">
            <button type="submit" name="update_profile">Save Changes</button>
        </form>
    </div>

    <!-- Username Section -->
    <div id="editUsernameSection" class="edit-form-section" style="display:none; width: 100%;padding-left:125px;border-radius: 15px;">
        <form method="POST">
          <p style="color:black"> <label for="username" style="font-weight:bold">Edit Username</label></p> 
           <p style="color:black">  <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" 
            required class="container1" style="width:1000px;background-color:#d3c1ff; border-radius:8px;text-align: left;">
            </p>  
            <p style="color:black"><button type="submit" name="update_username">Save Changes</button></p>
        </form>
    </div>

    <!-- Email Section -->
    <div id="editEmailSection" class="edit-form-section" style="display:none; width: 100%;padding-left:125px;border-radius: 15px;">
        <form method="POST">
           <p style="color:black"> <label for="email" style="font-weight:bold">Edit Email</label></p>
         <p style="color:black"><input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" 
         required class="container1" style="width:1000px;background-color:#d3c1ff; border-radius:8px;text-align: left;">
          </p>    <p style="color:black"> <button type="submit" name="update_email">Save Changes</button>
        </p></form>
    </div>

    <!-- Bio Section -->
    <div id="editBioSection" class="edit-form-section" style="display:none; width: 100%;padding-left:125px;border-radius: 15px;">
        <form method="POST">
           <p style="color:black"> <label for="bio" style="font-weight:bold">Edit Bio</label>
           </p> <p style="color:black"><textarea name="bio" required class="container1" style="width:1000px;background-color:#d3c1ff; border-radius:8px;text-align: left;">
            <?php echo htmlspecialchars($profile['bio']); ?></textarea>
            </p> <p style="color:black"><button type="submit" name="update_bio">Save Changes</button>
        </p></form>
    </div>

    <!-- Password Section -->
    <div id="editPasswordSection" class="edit-form-section" style="display:none; width: 100%;padding-left:125px;border-radius: 15px;">
        <form method="POST">
          <p style="color:black"><label for="password" style="font-weight:bold">Edit Password</label></p>  
          <p style="color:black"><input type="password" name="password" id="password" required class="container1" style="width:1000px;background-color:#d3c1ff; border-radius:8px;text-align: left;"></p>  
           
        <p style="font-weight:italic; color:black">Your OTP code is: <span class="otp"><?php echo $_SESSION['otp_code']; ?></span></p>
          <p style="color:black">  <label for="otp_code" style="font-weight:bold">Enter OTP Code</label></p>
          <p style="color:black"> <input type="text" name="otp_code" id="otp_code" required placeholder="Enter the OTP" class="container1" style="width:1000px;background-color:#d3c1ff; border-radius:8px;text-align: left;"></p> 
           <p style="color:black"><button type="submit" name="update_password">Save Changes</button></p> 
        </form>
    </div>

</div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      function editInfo(field) {
            // Hide all sections
            document.getElementById('editNameSection').style.display = 'none';
            document.getElementById('editProfilePictureSection').style.display = 'none';
            document.getElementById('editUsernameSection').style.display = 'none';
            document.getElementById('editEmailSection').style.display = 'none';
            document.getElementById('editBioSection').style.display = 'none';
            document.getElementById('editPasswordSection').style.display = 'none';

            // Show the selected section
            if (field === 'name') {
                document.getElementById('editNameSection').style.display = 'block';}
            else if (field === 'profile') {
                document.getElementById('editProfilePictureSection').style.display = 'block';
            } else if (field === 'username') {
                document.getElementById('editUsernameSection').style.display = 'block';
            } else if (field === 'email') {
                document.getElementById('editEmailSection').style.display = 'block';
            } else if (field === 'bio') {
                document.getElementById('editBioSection').style.display = 'block';
            } else if (field === 'password') {
                document.getElementById('editPasswordSection').style.display = 'block';
            }
        }
        
// JavaScript to toggle password visibility
    // JavaScript to toggle password visibility
   // JavaScript to toggle password visibility
   document.getElementById('togglePassword').addEventListener('change', function() {
        var passwordField = document.getElementById('password');
        // Check if the checkbox is checked
        if (this.checked) {
            passwordField.type = 'text';  // Show the password
        } else {
            passwordField.type = 'password';  // Hide the password
        }
    });

</script>

    </script>
</body>
</html>

