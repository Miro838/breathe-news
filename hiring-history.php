<?php
// Start session to track the logged-in user
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // If user is not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Database connection
include 'db/connection.php';

// Get the logged-in user's ID from the session
$userId = $_SESSION['user_id']; 

// Query to fetch user applications with job details
$query = "
    SELECT 
        a.id AS application_id,
        a.job_offer_id,
        a.resume,
        a.cover_letter,
        a.status,
        a.applied_at,
        j.title,
        j.location,
        j.salary
    FROM applications a
    JOIN job_offers j ON a.job_offer_id = j.id
    WHERE a.user_id = ?
";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Start output buffering to capture the generated HTML
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Activity - Breathe News</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css"> 
    <link rel="stylesheet" href="style/a.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 14px;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .header-main {
            background-color: white;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .header-sub {
            margin-top: 10px;
            background-color: #f8f9fa;
            color: #333;
            padding: 8px 20px;
            display: flex;
            justify-content: center;
            border-bottom: 1px solid #ddd;
        }

        .header-sub a {
            margin: 0 8px;
            text-decoration: none;
            color: gray;
            font-size: 0.8rem;
            font-weight: bold;
            transition: color 0.3s ease-in-out;
        }

        .header-sub a:hover {
            color: purple;
        }

       

        h1 {
            text-align: center;
            color: #333;
        }

        .applications {
            margin-top: 20px;
        }

        .application {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            background-color: #fafafa;
        }

        .application h2 {
            margin: 0 0 10px;
            color: #444;
        }

        .application p {
            margin: 5px 0;
            color: #555;
        }

        .status {
            font-weight: bold;
        }

        .status.pending {
            color: orange;
        }

        .status.approved {
            color: green;
        }

        .status.rejected {
            color: red;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            font-size: 14px;
        }

        table th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.2s ease-in-out;
        }

        table td {
            color: #555;
        }

        .no-data {
            text-align: center;
            color: gray;
            font-size: 16px;
            font-weight: bold;
            padding: 10px;
        }
    </style>
</head>
<body>

    <!-- First Header -->
    <div class="header-main">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand" href="#">BREATHE NEWS</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="home1.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="category1.php">Categories</a></li>
                        <li class="nav-item"><a class="nav-link" href="quiz1.php">Quizzes</a></li>
                        <li class="nav-item"><a class="nav-link" href="polls1.php">Polls</a></li>
                        <li class="nav-item"><a class="nav-link" href="hiringoffers1.php">Hiring Offers</a></li>
                        <li class="nav-item"><a class="nav-link" href="manageaccount.php">Manage Account</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-primary text-white" style="background-color:purple" href="logout.php">Log Out</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <!-- Second Header -->
    <div class="header-sub">
        <nav class="sub-header">
            <a href="manageaccount.php">Manage Account</a>
            <a href="account-activity.php">Account Activity</a>
            <a href="comments-history.php">Comments History</a>
            <a href="ratings-history.php">Ratings History</a>
            <a href="saves-history.php">Saves History</a>
            <a href="quiz-history.php">Quiz Participation History</a>
            <a href="poll-history.php">Poll Participation History</a>
            <a href="hiring-history.php">Hiring Offers History</a>
            <a href="testimonials.php">Testiomails History</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="container">
        <h1>My Job Applications</h1>
        <div id="applications-list">
            <?php
            // Generate HTML for each application
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="application">';
                    echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
                    echo '<p><strong>Location:</strong> ' . htmlspecialchars($row['location']) . '</p>';
                    echo '<p><strong>Salary:</strong> $' . htmlspecialchars($row['salary']) . '</p>';
                    echo '<p><strong>Applied On:</strong> ' . date("d-m-Y", strtotime($row['applied_at'])) . '</p>';
                    echo '<p><strong>Status:</strong> <span class="status ' . strtolower($row['status']) . '">' . htmlspecialchars($row['status']) . '</span></p>';
                    echo '<p><strong>Resume:</strong> ' . htmlspecialchars($row['resume']) . '</p>';
                    echo '<p><strong>Cover Letter:</strong> ' . htmlspecialchars($row['cover_letter']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<div class="no-data">No applications found.</div>';
            }

            // Close connection
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            ?>
        </div>
    </div>

</body>
</html>
