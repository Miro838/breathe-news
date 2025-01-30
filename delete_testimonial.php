<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}
include "db/connection.php";

// Check if ID is set in the query string
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = trim($_GET["id"]);

    // Prepare the delete statement
    $sql = "DELETE FROM testimonials WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: manage_about.php"); // Redirect after successful deletion
        exit();
    } else {
        echo "<script>alert('Error: " . htmlspecialchars($stmt->error) . "'); window.location.href='manage_testimonials.php';</script>";
    }

    $stmt->close();
} else {
    // ID is not valid, redirect to manage page
    header("Location: manage_testimonials.php");
    exit();
}

$conn->close();
?>
