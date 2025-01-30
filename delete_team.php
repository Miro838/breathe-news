<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

include "db/connection.php";

// Check if the ID is set and is a valid number
if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id = $_GET["id"];

    // Prepare the DELETE statement
    $sql = "DELETE FROM team WHERE id = ?";
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
    echo "<script>alert('Invalid team member ID.');</script>";
}

$conn->close();
?>
