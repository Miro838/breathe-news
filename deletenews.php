<?php
include "db/connection.php";

// Ensure the ID is an integer
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);

    // Execute the statement
    if ($stmt->execute()) {
        // Check if the news item was deleted
        if ($stmt->affected_rows > 0) {
            // Optionally delete associated files if necessary
            // Example:
             $filePath = 'path_to_file_based_on_id'; // Retrieve file path based on ID
             if (file_exists($filePath)) {
               unlink($filePath);
            }

            // Redirect after successful deletion
            header('Location: news.php');
            exit();
        } else {
            echo "No news item found with the provided ID.";
        }
    } else {
        echo "Error deleting news item: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
} else {
    echo "Invalid ID.";
}

$conn->close();
?>
