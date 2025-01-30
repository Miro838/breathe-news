<?php
// Include database connection file
include 'db/connection.php'; // Adjust the path as needed
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}
// Start the session
session_start();

// Check if the article ID is provided in the URL and is numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $article_id = intval($_GET['id']); // Convert the ID to an integer

    // Delete the article from the database
    $sql = "DELETE FROM articles WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $article_id); // Bind the article ID to the deletion query
        if ($stmt->execute()) {
            $stmt->close();
            // Redirect to articles page with a success message
            header("Location: articles.php?msg=Article deleted successfully");
            exit();
        } else {
            // Error deleting article
            echo "Error deleting article: " . $conn->error;
        }
    } else {
        // Error preparing statement for deletion
        echo "Error preparing statement for deletion: " . $conn->error;
    }
} else {
    // Invalid or missing article ID
    echo "Invalid or missing article ID.";
    echo '<br><a href="articles.php">Back to Articles</a>'; // Provide a link to go back to the articles page
}

// Close the database connection
$conn->close();
?>
