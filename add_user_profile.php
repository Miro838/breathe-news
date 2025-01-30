<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}
include 'db/connection.php';

// Initialize variables and error messages
$username = $first_name = $last_name = $bio = "";
$profile_picture = 'default.jpg'; // Default profile picture
$errors = array(
    'username' => '',
    'first_name' => '',
    'last_name' => '',
    'bio' => '',
    'profile_picture' => ''
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate username
    $username = trim($_POST['username']);
    if (empty($username)) {
        $errors['username'] = "Please enter the username.";
    } else {
        // Check if username exists in admin_login table
        $check_user_query = "SELECT * FROM admin_login WHERE username = ?";
        $check_user_stmt = $conn->prepare($check_user_query);

        if ($check_user_stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }

        $check_user_stmt->bind_param('s', $username); // Use 's' for string
        $check_user_stmt->execute();
        $check_user_result = $check_user_stmt->get_result();

        if ($check_user_result->num_rows === 0) {
            $errors['username'] = "The username does not exist.";
        }

        $check_user_stmt->close(); // Close statement after use
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
    } elseif ($_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) {
        $errors['profile_picture'] = "Error uploading file.";
    }

    // Check if there are no errors before inserting into the database
    if (!array_filter($errors)) {
        // Prepare SQL statement
        $query = "INSERT INTO user_profiles (user_id, first_name, last_name, bio) VALUES (?, ?, ?, ?, ?)";
        
        // Assuming user_id is derived from the admin_login table; fetching the user_id based on the username
        $user_id_query = "SELECT id FROM admin_login WHERE username = ?";
        $user_id_stmt = $conn->prepare($user_id_query);
        $user_id_stmt->bind_param('s', $username);
        $user_id_stmt->execute();
        $user_id_stmt->bind_result($user_id);
        $user_id_stmt->fetch();
        $user_id_stmt->close();

        // Prepare statement for user profile insertion
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }

        // Bind parameters
        $stmt->bind_param('sssss', $user_id, $first_name, $last_name, $profile_picture, $bio);

        if ($stmt->execute()) {
            // Redirect to view_user_profile.php after successful addition
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

<!-- HTML FORM FOR ADD USER PROFILE -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        /* Add any custom styles here */
    </style>
</head>
<body>
<?php include 'include/admin_user.php'; ?>

<div class="container" style="margin-left:20%; width:80%">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="view_user_profile.php">View Profiles</a></li>
        <li class="breadcrumb-item active">Add Profile</li>
    </ul>

    <h1>Add New User Profile</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control <?php echo (!empty($errors['username'])) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
            <span class="invalid-feedback"><?php echo $errors['username']; ?></span>
        </div>

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
        </div>

        <button type="submit" class="btn btn-primary">Add Profile</button>
    </form>
</div>

</body>
</html>
