<?php
session_start();
include "db/connection.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');



    // Begin a transaction
    $conn->begin_transaction();

    try {
        // Delete user from the 'admin_login' table
        $sql_user = "DELETE FROM admin_login WHERE id = ?";
        $stmt_user = $conn->prepare($sql_user);
        $stmt_user->bind_param("i", $user_id);
        $stmt_user->execute();

        // Delete user profile from the 'user_profiles' table
        $sql_profile = "DELETE FROM user_profiles WHERE user_id = ?";
        $stmt_profile = $conn->prepare($sql_profile);
        $stmt_profile->bind_param("i", $user_id);
        $stmt_profile->execute();

        // Optionally, delete other related data (like comments, ratings, etc.)
        // Example: Delete comments (if applicable)
         $sql_comments = "DELETE FROM ratings WHERE user_id = ?";
         $stmt_comments = $conn->prepare($sql_comments);
        $stmt_comments->bind_param("i", $user_id);
         $stmt_comments->execute();

         $sql_comments = "DELETE FROM comments WHERE user_id = ?";
         $stmt_comments = $conn->prepare($sql_comments);
        $stmt_comments->bind_param("i", $user_id);
         $stmt_comments->execute();
        // Commit the transaction
        $conn->commit();

        // Destroy the session and log the user out
        session_destroy();

        // Redirect to the login page after account deletion
        header("Location: login.php");
        exit();

    } catch (Exception $e) {
        // Rollback transaction if any error occurs
        $conn->rollback();
        die("Error deleting account: " . $e->getMessage());
    }
} else {
    die("User is not logged in.");
}


?>
