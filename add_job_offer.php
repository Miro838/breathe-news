<?php
// Start the session if it hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in; if not, redirect to the admin login page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db/connection.php'; // Include database connection

// Check if the form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form input
    $title = $_POST['title'];
    $summary = $_POST['summary'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];
    $status = $_POST['status'];

    // Validate inputs (ensure no fields are empty)
    if (!empty($title) && !empty($summary) && !empty($description) && !empty($location) && !empty($salary) && !empty($status)) {
        // Escape inputs to prevent SQL injection
        $title = $conn->real_escape_string($title);
        $summary = $conn->real_escape_string($summary);
        $description = $conn->real_escape_string($description);
        $location = $conn->real_escape_string($location);
        $salary = $conn->real_escape_string($salary);
        $status = $conn->real_escape_string($status);

        // Insert data into the job_offers table
        $query = "INSERT INTO job_offers (title, summary, description, location, salary, status, created_at, updated_at)
                  VALUES ('$title', '$summary', '$description', '$location', '$salary', '$status', NOW(), NOW())";

        // Check if the query was successful
        if ($conn->query($query) === TRUE) {
            // Redirect to the manage job offers page on success
            header("Location: manage_joboffer.php");
            exit();
        } else {
            // Display an error message if the query fails
            echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }

        // Close the database connection
        $conn->close();
    } else {
        // Display a warning if any fields are missing
        echo "<div class='alert alert-warning'>All fields are required.</div>";
    }
}

// Include header or navigation (if needed)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Job Offer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }
        .header-placeholder {
            height: 60px; /* Adjust based on the height of your header */
        }
        .breadcrumb-container {
            margin-left: 20%;
            padding: 0;
            background-color: #f8f9fa;
            width: 80%;
            border-bottom: 1px solid #ddd;
        }
        .breadcrumb {
            margin: 0;
            padding: 10px 20px;
            background-color: transparent;
            display: flex;
            align-items: center;
        }
        .form-container {
            margin-left: 20%;
            width: 78%;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .btn-primary {
            margin-top: 10px;
        }
        .content-wrapper {
            padding: 20px;
        }
    </style>
</head>
<body>
    <?php include 'include/hiringoffer.php'; ?> <!-- Include header -->

    <!-- Breadcrumb directly below the header -->
    <div class="breadcrumb-container">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
            <li class="breadcrumb-item"><a href="job_offers.php">Job Offers</a></li>
            <li class="breadcrumb-item active">Add Job Offer</li>
        </ul>
    </div>

    <div class="form-container">
        <h1 style="text-align:center">Add Job Offer</h1>
        <form method="POST" action="add_job_offer.php">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="summary">Summary</label>
                <input type="text" class="form-control" id="summary" name="summary" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <div class="form-group">
                <label for="salary">Salary</label>
                <input type="text" class="form-control" id="salary" name="salary"  required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Open">Open</option>
                    <option value="Closed">Closed</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Job Offer</button>
        </form>
    </div>

    
</body>
</html>
