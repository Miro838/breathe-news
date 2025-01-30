<?php
session_start();
include 'db/connection.php';

// Check if the user is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if a specific user profile is being deleted
if (isset($_GET['user_id'])) {
    $delete_user_id = intval($_GET['user_id']);
    
    // Delete the user profile record from the database
    $delete_query = "DELETE FROM user_profiles WHERE user_id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param('i', $delete_user_id);

    if ($delete_stmt->execute()) {
        // Redirect after successful deletion
        header('Location: view_user_profile.php');
        exit();
    } else {
        echo "<script>alert('Error: Unable to delete user profile.');</script>";
    }

    $delete_stmt->close();
} else {
    echo "<script>alert('Invalid request.');</script>";
}

$conn->close();
?>
