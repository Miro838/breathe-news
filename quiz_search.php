<?php
include 'db/connection.php';
session_start(); // Start session to check if the user is logged in

// Get the search query from the form and sanitize it
$query = isset($_POST['query']) ? mysqli_real_escape_string($conn, $_POST['query']) : '';

// Build the SQL query based on the search term
$sql = "SELECT id, title, description, difficulty FROM quizzes WHERE 
            title LIKE '%$query%' OR
            description LIKE '%$query%' OR
            difficulty LIKE '%$query%'";

// Execute the query
$result = mysqli_query($conn, $sql);

// Fetch quizzes into an array
$quizzes = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $quizzes[] = $row;
    }
} else {
    echo '<p class="alert alert-info mt-3">No quizzes found matching your search.</p>';
}

// Determine the quiz page link based on login status
$quizPageLink = isset($_SESSION['user_id']) ? 'quiz1.php' : 'quiz.php';

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Search Results</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Quiz Search Results</h1>

        <?php
        // Display the quizzes if available
        if (!empty($quizzes)) {
            foreach ($quizzes as $quiz) {
                $quizId = $quiz['id'];

                // Fetch the number of questions for this quiz
                $questionCountSql = "SELECT COUNT(*) as question_count FROM questions WHERE quiz_id = $quizId";
                $questionCountResult = mysqli_query($conn, $questionCountSql);
                $questionCountRow = mysqli_fetch_assoc($questionCountResult);
                $questionCount = $questionCountRow['question_count'];

                echo '<div class="card mb-4">'; // Adjusted margin to increase space between quizzes
                echo '    <div class="card-body">';
                echo '        <h5 class="card-title">' . htmlspecialchars($quiz['title']) . '</h5>';
                echo '        <p class="card-text">' . htmlspecialchars($quiz['description']) . '</p>';
                echo '        <p class="card-text">Number of Questions: ' . htmlspecialchars($questionCount) . '</p>';
                echo '        <p class="card-text">Difficulty: ' . htmlspecialchars($quiz['difficulty']) . '</p>';
                echo '    </div>';
                echo '</div>';
            }
        }
        ?>

        <!-- Space between quiz content and the Back button -->
        <div class="mt-4">
            <a href="<?= $quizPageLink ?>" class="btn btn-secondary">Back to Quiz Page</a>
        </div>
    </div>

    <!-- Style for the page -->
    <style>
        .alert {
            margin-bottom: 20px;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .card {
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .card-body {
            background-color: #f8f9fa;
        }

        .mt-4 {
            margin-top: 30px; /* Increased margin to create space between quiz and button */
        }
    </style>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
