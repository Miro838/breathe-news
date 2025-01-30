<?php
session_start();
include 'db/connection.php';

// Check if user is logged in as an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

// Initialize variables and error messages
$username = $email = $password = $role = $status = "";
$errors = array(
    'username' => '',
    'email' => '',
    'password' => '',
    'role' => '',
    'status' => ''
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate username
    $username = trim($_POST['username']);
    if (empty($username)) {
        $errors['username'] = "Please enter a username.";
    }

    // Validate email
    $email = trim($_POST['email']);
    if (empty($email)) {
        $errors['email'] = "Please enter an email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email address.";
    }

    // Validate password
    $password = trim($_POST['password']);
    if (empty($password)) {
        $errors['password'] = "Please enter a password.";
    }

    // Validate role
    $role = trim($_POST['role']);
    if (empty($role)) {
        $errors['role'] = "Please select a role.";
    }

    // Validate status
    $status = trim($_POST['status']);
    if (empty($status)) {
        $errors['status'] = "Please select a status.";
    }

    // Check if there are no errors before inserting into database
    if (!array_filter($errors)) {
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $registration_date = date('Y-m-d H:i:s'); // Current date and time

        // Prepare SQL statement
        $query = "INSERT INTO admin_login (username, email, password_hash, registration_date, role, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }

        // Bind parameters
        $stmt->bind_param('ssssss', $username, $email, $password_hash, $registration_date, $role, $status);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to view users after successful addition
            header("Location: view_user.php");
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .container {
            margin-left: 20%;
            width: 60%;
        }
        .breadcrumb {
            margin-bottom: 20px;
        }
        .form-group {
            width: 100%;
        }
        .invalid-feedback {
            display: block;
        }
    </style>
</head>
<body>
    <?php include 'include/admin_user.php'; ?>

    <div class="container" style="margin-left:20%; width:80%">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="admin_dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="view_users.php">View Users</a></li>
            <li class="breadcrumb-item active">Add User</li>
        </ul>

        <h1>Add New User</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control <?php echo (!empty($errors['username'])) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                <span class="invalid-feedback"><?php echo $errors['username']; ?></span>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control <?php echo (!empty($errors['email'])) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <span class="invalid-feedback"><?php echo $errors['email']; ?></span>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control <?php echo (!empty($errors['password'])) ? 'is-invalid' : ''; ?>" id="password" name="password">
                <span class="invalid-feedback"><?php echo $errors['password']; ?></span>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select class="form-control <?php echo (!empty($errors['role'])) ? 'is-invalid' : ''; ?>" id="role" name="role">
                    <option value="">Select Role</option>
                    <option value="user" <?php echo ($role == 'user') ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo ($role == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
                <span class="invalid-feedback"><?php echo $errors['role']; ?></span>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control <?php echo (!empty($errors['status'])) ? 'is-invalid' : ''; ?>" id="status" name="status">
                    <option value="">Select Status</option>
                    <option value="active" <?php echo ($status == 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($status == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                    <option value="banned" <?php echo ($status == 'banned') ? 'selected' : ''; ?>>Banned</option>
                </select>
                <span class="invalid-feedback"><?php echo $errors['status']; ?></span>
            </div>

            <button type="submit" class="btn btn-primary">Add User</button>
        </form>
    </div>

 
</body>
</html>
