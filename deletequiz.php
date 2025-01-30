<?php
// Include database connection file
include 'db/connection.php'; // Adjust the path as needed

// Start the session
session_start();

// Check if user is an admin or has the appropriate permissions
// (You may want to add your own admin check here)

// Check if the quiz ID is provided in the URL and is numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $quiz_id = intval($_GET['id']); // Convert the ID to an integer

    // Fetch the quiz details to get any related file paths
    $sql = "SELECT title FROM quizzes WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $quiz_id); // Bind the quiz ID to the SQL query
        $stmt->execute();
        $stmt->bind_result($title);
        $stmt->fetch();
        $stmt->close();

        // Check if the quiz was found
        if ($title) {
            // Delete the quiz from the database
            $sql = "DELETE FROM quizzes WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("i", $quiz_id); // Bind the quiz ID to the deletion query
                if ($stmt->execute()) {
                    $stmt->close();
                    
                    // Redirect or provide a success message
                    header("Location: create_quiz.php?msg=Quiz deleted successfully");
                    exit();
                } else {
                    // Error deleting quiz
                    echo "Error deleting quiz: " . $conn->error;
                }
            } else {
                // Error preparing statement for deletion
                echo "Error preparing statement for deletion: " . $conn->error;
            }
        } else {
            // Quiz not found
            echo "Quiz not found.";
        }
    } else {
        // Error preparing statement for fetching quiz
        echo "Error preparing statement for fetching quiz: " . $conn->error;
    }
} else {
    // Invalid or missing quiz ID
    echo "Invalid or missing quiz ID.";
    echo '<br><a href="create_quiz.php">Back to Manage Quizzes</a>'; // Provide a link to go back to the manage quizzes page
}

// Close the database connection
$conn->close();
?>
