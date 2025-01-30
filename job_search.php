<?php
include 'db/connection.php';
session_start(); // Start session to check if the user is logged in

// Get the search query from the form and sanitize it
$query = isset($_POST['query']) ? mysqli_real_escape_string($conn, $_POST['query']) : '';

// Build the SQL query based on the search term
$sql = "SELECT id, title, summary, description, location, salary, status, created_at, updated_at FROM job_offers WHERE 
            title LIKE '%$query%' OR
            summary LIKE '%$query%'";

// Execute the query
$result = mysqli_query($conn, $sql);

// Fetch jobs into an array
$jobs = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $jobs[] = $row;
    }
} else {
    echo '<p class="alert alert-info mt-3">No job offers found matching your search.</p>';
}

// Determine the "Back to Job Page" link based on login status
if (isset($_SESSION['user_id'])) {
    // If logged in, redirect to jobs1.php
    $jobPageLink = 'hiringoffers1.php';
} else {
    // If not logged in, redirect to jobs.php
    $jobPageLink = 'hiringoffers.php';
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Job Search Results</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Job Search Results</h1>
        
        <?php
        // Display the jobs if available
        if (!empty($jobs)) {
            foreach ($jobs as $job) {
                $jobId = $job['id'];

                echo '<div class="card mb-4">'; // Adjusted margin to increase space between jobs
                echo '    <div class="card-body">';
                echo '        <h5 class="card-title">' . htmlspecialchars($job['title']) . '</h5>';
                echo '        <p class="card-text"><strong>Summary:</strong> ' . htmlspecialchars($job['summary']) . '</p>';
                echo '        <p class="card-text"><strong>Description:</strong> ' . htmlspecialchars($job['description']) . '</p>';
                echo '        <p class="card-text"><strong>Location:</strong> ' . htmlspecialchars($job['location']) . '</p>';
                echo '    </div>';
                echo '</div>';
            }
        }
        ?>

        <!-- Space between job content and the Back button -->
        <div class="mt-4">
            <a href="<?= $jobPageLink ?>" class="btn btn-secondary">Back to Job Page</a>
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
            margin-top: 30px; /* Increased margin to create space between job and button */
        }
    </style>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
