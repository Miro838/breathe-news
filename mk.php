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
if (isset($_POST['update_profile']) && isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
    $targetDir = "uploads/images/";
    $fileName = basename($_FILES["profile"]["name"]);
    $targetFile = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Validate file extension
    $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($imageFileType, $validExtensions)) {
        if (move_uploaded_file($_FILES["profile"]["tmp_name"], $targetFile)) {
            $stmtUpdate = $conn->prepare("UPDATE user_profiles SET profile = ? WHERE user_id = ?");
            $stmtUpdate->bind_param('si', $fileName, $userId);
            $stmtUpdate->execute();
        }
    }
    header("Location: ".$_SERVER['PHP_SELF']);
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Profile</title>
</head>
<body>
    <h1>Manage Profile</h1>
    
    <body>
    <h1>Manage Profile</h1>

    <!-- Display User Information -->
    <div id="userInfo">
        <h2>User Information</h2>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name']); ?></p>
        <p><strong>Bio:</strong> <?php echo htmlspecialchars($profile['bio']); ?></p>
        <p><strong>Profile Picture:</strong></p>
        <?php if (!empty($profile['profile'])): ?>
            <img src="uploads/images/<?php echo htmlspecialchars($profile['profile']); ?>" alt="Profile Picture" width="100">
        <?php else: ?>
            <p>No profile picture uploaded.</p>
        <?php endif; ?>
    </div>

    <!-- Edit Buttons -->
    <button onclick="editInfo('profile')">Edit Profile Picture</button>
    <button onclick="editInfo('username')">Edit Username</button>
    <button onclick="editInfo('email')">Edit Email</button>
    <button onclick="editInfo('bio')">Edit Bio</button>
    <button onclick="editInfo('password')">Edit Password</button>
    <button onclick="editInfo('name')">Edit Name</button>
</body>

<div id="editNameSection" style="display:none;">
        <form method="POST">
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" value="<?php echo htmlspecialchars($profile['first_name']); ?>" required>
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" value="<?php echo htmlspecialchars($profile['last_name']); ?>" required>
            <button type="submit" name="update_name">Save Changes</button>
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
    <div id="editUsernameSection" style="display:none;">
        <form method="POST">
            <label for="username">Edit Username</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            <button type="submit" name="update_username">Save Changes</button>
        </form>
    </div>

    <!-- Email Section -->
    <div id="editEmailSection" style="display:none;">
        <form method="POST">
            <label for="email">Edit Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <button type="submit" name="update_email">Save Changes</button>
        </form>
    </div>

    <!-- Bio Section -->
    <div id="editBioSection" style="display:none;">
        <form method="POST">
            <label for="bio">Edit Bio</label>
            <textarea name="bio" required><?php echo htmlspecialchars($profile['bio']); ?></textarea>
            <button type="submit" name="update_bio">Save Changes</button>
        </form>
    </div>

    <!-- Password Section -->
    <div id="editPasswordSection" style="display:none;">
        <form method="POST">
            
            <label for="password">Edit Password</label>
            <input type="password" name="password" required>
            <label for="otp_code">Enter OTP Code</label>
            <input type="text" name="otp_code" required>
            <small>Your OTP code is: <strong><?php echo $_SESSION['otp_code']; ?></strong></small>
            <button type="submit" name="update_password">Save Changes</button>
        </form>
    </div>

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
    </script>
</body>
</html>
