<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}
include "db/connection.php";

// Check if the ID is set in the URL
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = trim($_GET["id"]);

    // Prepare a delete statement
    $sql = "DELETE FROM milestones WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: manage_about.php"); // Redirect after successful deletion
        exit();
    } else {
        echo "<script>alert('Error: " . htmlspecialchars($stmt->error) . "');</script>";
    }

    $stmt->close();
} else {
    header("Location: manage_about.php");
    exit();
}

$conn->close();
?>
