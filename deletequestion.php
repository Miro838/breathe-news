<?php
session_start();

// Redirect to login if the user is not authenticated
if (!isset($_SESSION["username"])) {
    header("Location: admin_login.php");
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db/connection.php";

// Fetch the question ID from the URL
$question_id = null;

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $question_id = intval($_GET['id']); // Ensure ID is an integer

    // Prepare SQL to delete associated options
    $stmt = $conn->prepare("DELETE FROM question_options WHERE question_id = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $stmt->close();

    // Prepare SQL to delete the question
    $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $question_id);
    if ($stmt->execute()) {
        header("Location: create_question.php"); // Redirect to manage_questions.php
        exit();
    } else {
        echo "<script>alert('Error deleting question.');</script>";
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Invalid request. No question ID provided.";
    exit();
}

$conn->close();
?>
