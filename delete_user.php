<?php
include 'db/connection.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}
$id = $_GET['id'];

// Prepare and execute delete query
$query = "DELETE FROM admin_login WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();

// Check if the deletion was successful
if ($stmt->affected_rows > 0) {
    // Redirect to view_users.php
    header("Location: view_user.php");
    exit();
} else {
    // Redirect to an error page or back to view_users.php with an error message
    header("Location: view_user.php?error=delete_failed");
    exit();
}

// Close statement and connection

$conn->close();
?>
