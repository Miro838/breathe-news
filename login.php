<?php
session_start();
include "db/connection.php"; // Ensure this file contains the correct database connection code

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL query to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM admin_login WHERE username = ?");
    
    // Check if the statement preparation was successful
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind parameters to the prepared statement
    $stmt->bind_param("s", $username);
    $stmt->execute();

    // Get the result of the query
    $result = $stmt->get_result();

    // Check if a matching record was found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Check user status
        if ($row['status'] === 'banned') {
            echo "<script>alert('Your account is banned. Please contact support or create a new account.');</script>";
        } elseif ($row['status'] === 'inactive') {
            echo "<script>alert('Your account is inactive. Please create a new account.');</script>";
        } else {
            // Verify password
            if (password_verify($password, $row['password_hash'])) {
                // Set session variables
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = $row['role'];

                // Capture login details for session_activity tracking
                $session_id = session_id(); // Use PHP session ID
                $ip_address = $_SERVER['REMOTE_ADDR']; // Get user IP address
                $device_info = $_SERVER['HTTP_USER_AGENT']; // Get user device info

                // Insert login activity into session_activity table
                $stmt_activity = $conn->prepare("INSERT INTO session_activity (user_id, session_id, login_time, ip_address, device_info, status) 
                                                VALUES (?, ?, NOW(), ?, ?, 'active')");
                $stmt_activity->bind_param("isss", $_SESSION['user_id'], $session_id, $ip_address, $device_info);
                $stmt_activity->execute();

                // Redirect based on user role
                if ($row['role'] === 'admin') {
                    header('Location: admin_main_dashboard.php'); // Redirect admin to dashboard
                } else {
                    header('Location: home1.php'); // Redirect user to home page
                }
                exit();
            } else {
                // Invalid password, show an alert
                echo "<script>alert('Invalid credentials; please try again.');</script>";
            }
        }
    } else {
        // Invalid username, show an alert
        echo "<script>alert('Invalid credentials; please try again.');</script>";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <style>
        /* Your CSS styles here */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
            background-color: #f4f4f4;
        }
        .left-section {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .right-section {
            width: 50%;
            background-color: purple;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 36px;
            font-weight: bold;
            clip-path: circle(100% at 0% 100%);
        }
        .container {
            background-color: #ffffff;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 320px;
        }
        .form-group {
            margin-bottom: 15px;
            position: relative; /* Add relative positioning */
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            background-color: #e0e0e0;
            color: #333;
            outline: none;
        }
        .form-group .toggle-password {
            position: absolute;
            right: 12px; /* Adjust to your liking */
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #333;
        }
        .form-group input[type="submit"] {
            background-color: purple;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        .form-group input[type="submit"]:hover {
            background-color: purple;
        }
        .login-link {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }
        .login-link a {
            color: purple;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .show-password {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="right-section">
    Welcome To Dark Side 
    </div>
    <div class="left-section">
        <div class="container">
            <form action="" method="post">
                <div class="form-group">
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="Password" maxlength="15" required>
                    <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" value="Sign in">
                </div>
            </form>
            <div class="login-link">
                <p>Don't have an account? <a href="signUp.php">Sign up here</a></p>
            </div>
        </div>
    </div>

    <script>
         function togglePassword(inputId) {
            var input = document.getElementById(inputId);
            var toggleIcon = document.querySelector(`span[onclick="togglePassword('${inputId}')"]`);

            if (input.type === "password") {
                input.type = "text";
                toggleIcon.textContent = "🙈"; // Change icon to indicate password is visible
            } else {
                input.type = "password";
                toggleIcon.textContent = "👁️"; // Change icon back to indicate password is hidden
            }
        }
    </script>
</body>
</html>
