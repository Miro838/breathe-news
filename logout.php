<?php
session_start();

// Ensure you have a database connection
include('db/connection.php');  // Assuming you have a separate file for DB connection

// Check if a user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];  // Get the user ID from the session
    $session_id = session_id();       // Get the current session ID
    $login_time = $_SESSION['login_time'];  // Assuming you saved the login time in session
    $logout_time = date('Y-m-d H:i:s');    // Current timestamp for logout
    $ip_address = $_SERVER['REMOTE_ADDR'];  // User's IP address
    $device_info = $_SERVER['HTTP_USER_AGENT'];  // Device and browser info
    $activity_type = 'logout';        // Activity type
    $status = 'inactive';             // Session status set to inactive

    // Update user status to 'inactive' in the users table
    $update_user_status = "UPDATE users SET status = 'inactive' WHERE id = ?";
    if ($stmt = $conn->prepare($update_user_status)) {
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $stmt->close();
    }

    // Log the logout activity in the session_activity table
    $update_activity_query = "UPDATE session_activity 
                              SET logout_time = ?, status = 'inactive' 
                              WHERE user_id = ? AND session_id = ? AND logout_time IS NULL";
    if ($stmt = $conn->prepare($update_activity_query)) {
        $stmt->bind_param('sss', $logout_time, $user_id, $session_id);
        $stmt->execute();
        $stmt->close();
    }

    // Destroy the session to log the user out
    session_unset();
    session_destroy();
}

// Redirect to the sign-up page after logging out
header("Location: main.php");
exit;
?>
