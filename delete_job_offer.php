<?php
// Start the session if it hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in; if not, redirect to the admin login page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db/connection.php'; // Include database connection

// Get job offer ID from URL
$jobOfferId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Check if job offer ID is valid
if ($jobOfferId > 0) {
    // Prepare and execute the deletion query
    $query = "DELETE FROM job_offers WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $jobOfferId);

    if ($stmt->execute()) {
        // Redirect to manage_joboffer.php after successful deletion
        header("Location: manage_joboffer.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }

    $stmt->close();
} else {
    echo "<div class='alert alert-danger'>Invalid job offer ID.</div>";
}

$conn->close();
?>
