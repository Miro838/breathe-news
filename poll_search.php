<?php
include 'db/connection.php';
session_start(); // Start session to check if the user is logged in

// Get the search query from the form and sanitize it
$query = isset($_POST['query']) ? mysqli_real_escape_string($conn, $_POST['query']) : '';

// Build the SQL query based on the search term
$sql = "SELECT id, question, status, created_at, updated_at FROM polls WHERE 
            question LIKE '%$query%' OR
            status LIKE '%$query%'";

// Execute the query
$result = mysqli_query($conn, $sql);

// Fetch polls into an array
$polls = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $polls[] = $row;
    }
} else {
    echo '<p class="alert alert-info mt-3">No polls found matching your search.</p>';
}

// Determine the "Back to Poll Page" link based on login status
if (isset($_SESSION['user_id'])) {
    // If logged in, redirect to polls1.php
    $pollPageLink = 'polls1.php';
} else {
    // If not logged in, redirect to polls.php
    $pollPageLink = 'polls.php';
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Poll Search Results</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Poll Search Results</h1>
        
        <?php
        // Display the polls if available
        if (!empty($polls)) {
            foreach ($polls as $poll) {
                $pollId = $poll['id'];

                echo '<div class="card mb-4">'; // Adjusted margin to increase space between polls
                echo '    <div class="card-body">';
                echo '        <h5 class="card-title">' . htmlspecialchars($poll['question']) . '</h5>';
                echo '        <p class="card-text">Status: ' . htmlspecialchars($poll['status']) . '</p>';
                echo '        <p class="card-text">Created At: ' . htmlspecialchars($poll['created_at']) . '</p>';
                echo '        <p class="card-text">Updated At: ' . htmlspecialchars($poll['updated_at']) . '</p>';
                echo '    </div>';
                echo '</div>';
            }
        }
        ?>

        <!-- Space between poll content and the Back button -->
        <div class="mt-4">
            <a href="<?= $pollPageLink ?>" class="btn btn-secondary">Back to Poll Page</a>
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
            margin-top: 30px; /* Increased margin to create space between poll and button */
        }
    </style>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
