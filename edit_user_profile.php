<?php
session_start();
include 'db/connection.php';

// Initialize variables and error messages
$user_id = $first_name = $last_name = $bio = "";
$profile_picture = 'default.jpg'; // Default profile picture
$errors = array(
    'user_id' => '',
    'first_name' => '',
    'last_name' => '',
    'bio' => '',
    'profile_picture' => ''
);

// Check if user is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

// Fetch user_id from the URL or form submission
if (isset($_GET['user_id']) || isset($_POST['user_id'])) {
    $user_id = isset($_POST['user_id']) ? trim($_POST['user_id']) : trim($_GET['user_id']);

    // Fetch existing user profile data
    if (!empty($user_id)) {
        $query = "SELECT first_name, last_name, bio, profile FROM user_profiles WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->bind_result($first_name, $last_name, $bio, $profile_picture);
        $stmt->fetch();
        $stmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate user_id
    $user_id = trim($_POST['user_id']);
    if (empty($user_id)) {
        $errors['user_id'] = "Please enter the user ID.";
    } else {
        // Check if user_id exists in user_profiles table
        $check_user_query = "SELECT * FROM user_profiles WHERE user_id = ?";
        $check_user_stmt = $conn->prepare($check_user_query);
        $check_user_stmt->bind_param('i', $user_id);
        $check_user_stmt->execute();
        $check_user_result = $check_user_stmt->get_result();

        if ($check_user_result->num_rows === 0) {
            $errors['user_id'] = "The user ID does not exist.";
        }
    }

    // Validate first name
    $first_name = trim($_POST['first_name']);
    if (empty($first_name)) {
        $errors['first_name'] = "Please enter the first name.";
    }

    // Validate last name
    $last_name = trim($_POST['last_name']);
    if (empty($last_name)) {
        $errors['last_name'] = "Please enter the last name.";
    }

    // Validate bio
    $bio = trim($_POST['bio']);
    if (empty($bio)) {
        $errors['bio'] = "Please enter the bio.";
    }

    // Validate and upload profile picture
    if ($_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $profile_picture = basename($_FILES['profile_picture']['name']);
        $target_path = "uploads/images/" . $profile_picture;

        if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_path)) {
            $errors['profile_picture'] = "Failed to upload profile picture.";
            $profile_picture = 'default.jpg'; // Revert to default if upload fails
        }
    }

    // Check if there are no errors before updating the database
    if (!array_filter($errors)) {
        // Prepare SQL statement
        $query = "UPDATE user_profiles SET first_name = ?, last_name = ?, bio = ?, profile = ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }

        // Bind parameters
        $stmt->bind_param('ssssi', $first_name, $last_name, $bio, $profile_picture, $user_id);

        if ($stmt->execute()) {
            // Redirect to view_user_profile.php after successful update
            header("Location: view_user_profile.php");
            exit();
        } else {
            echo "<script>alert('Error: " . htmlspecialchars($stmt->error) . "');</script>";
        }

        // Close the statement
        $stmt->close();
    }

    // Close the connection
    $conn->close();
}
?>

<!-- HTML FORM FOR EDIT USER PROFILE -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
      
    </style>
</head>
<body>
<?php include 'include/admin_user.php'; ?>

<div class="container" style="margin-left:20%; width:80%">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="view_user_profile.php">View Profiles</a></li>
            <li class="breadcrumb-item active">Edit Profile</li>
        </ul>

        <h1>Edit User Profile</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">

            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control <?php echo (!empty($errors['first_name'])) ? 'is-invalid' : ''; ?>" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>">
                <span class="invalid-feedback"><?php echo $errors['first_name']; ?></span>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control <?php echo (!empty($errors['last_name'])) ? 'is-invalid' : ''; ?>" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>">
                <span class="invalid-feedback"><?php echo $errors['last_name']; ?></span>
            </div>

            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea class="form-control <?php echo (!empty($errors['bio'])) ? 'is-invalid' : ''; ?>" id="bio" name="bio"><?php echo htmlspecialchars($bio); ?></textarea>
                <span class="invalid-feedback"><?php echo $errors['bio']; ?></span>
            </div>

            <div class="form-group">
                <label for="profile_picture">Profile Picture</label>
                <input type="file" class="form-control <?php echo (!empty($errors['profile_picture'])) ? 'is-invalid' : ''; ?>" id="profile_picture" name="profile_picture">
                <span class="invalid-feedback"><?php echo $errors['profile_picture']; ?></span>
                <?php if ($profile_picture != 'default.jpg'): ?>
                    <img src="uploads/images/<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" style="width:100px; height:auto; margin-top:10px;">
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

    
</body>
</html>
