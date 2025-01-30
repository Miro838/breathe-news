<?php
// Include database connection file
include 'db/connection.php'; // Adjust the path as needed

// Start the session
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}
// Check if user is an admin or has the appropriate permissions
// (You may want to add your own admin check here)

// Check if the show ID is provided in the URL and is numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $show_id = intval($_GET['id']); // Convert the ID to an integer

    // Fetch the show details to get the file paths
    $sql = "SELECT title, thumbnail, video FROM top_shows WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $show_id); // Bind the show ID to the SQL query
        $stmt->execute();
        $stmt->bind_result($title, $thumbnail, $video);
        $stmt->fetch();
        $stmt->close();

        // Check if the show was found
        if ($title) {
            // Delete the show from the database
            $sql = "DELETE FROM top_shows WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("i", $show_id); // Bind the show ID to the deletion query
                if ($stmt->execute()) {
                    $stmt->close();

                    // Check if files exist and delete them
                    $thumbnail_path = 'uploads/images/' . $thumbnail;
                    $video_path = 'uploads/videos/' . $video;

                    if ($thumbnail && file_exists($thumbnail_path)) {
                        if (!unlink($thumbnail_path)) {
                            echo "Failed to delete thumbnail file.";
                        }
                    }

                    if ($video && file_exists($video_path)) {
                        if (!unlink($video_path)) {
                            echo "Failed to delete video file.";
                        }
                    }

                    // Redirect or provide a success message
                    header("Location: manage_shows.php?msg=Show deleted successfully");
                    exit();
                } else {
                    // Error deleting show
                    echo "Error deleting show: " . $conn->error;
                }
            } else {
                // Error preparing statement for deletion
                echo "Error preparing statement for deletion: " . $conn->error;
            }
        } else {
            // Show not found
            echo "Show not found.";
        }
    } else {
        // Error preparing statement for fetching show
        echo "Error preparing statement for fetching show: " . $conn->error;
    }
} else {
    // Invalid or missing show ID
    echo "Invalid or missing show ID.";
    echo '<br><a href="manage_shows.php">Back to Manage Shows</a>'; // Provide a link to go back to the manage shows page
}

// Close the database connection
$conn->close();
?>
