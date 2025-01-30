<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}
include "db/connection.php";

// Check if the ID is set
if (isset($_GET["id"]) && !empty($_GET["id"])) {
    $id = trim($_GET["id"]);

    // Prepare delete statement
    $sql = "DELETE FROM faqs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: manage_faq.php"); // Redirect after successful deletion
        exit();
    } else {
        echo "<script>alert('Error: " . htmlspecialchars($stmt->error) . "');</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid request.');</script>";
}

$conn->close();
?>
