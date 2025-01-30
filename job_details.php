<?php
include 'db/connection.php';
session_start(); // Start the session to check login status

// Get the job offer ID from either GET or POST
$jobOfferId = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : null);

if (!$jobOfferId) {
    echo '<p style="color: red; text-align: center;">Invalid job offer ID.</p>';
    exit;
}

// Check if the database connection is successful
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Fetch job offer details from the database
$sql = "SELECT id, title, summary, description, location, salary, status, created_at, updated_at 
        FROM job_offers 
        WHERE id = ? AND status = 'open'";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    // Output error message if the SQL statement preparation fails
    die('MySQL prepare error: ' . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $jobOfferId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$jobOffer = mysqli_fetch_assoc($result);

// Check if the job offer exists
if (!$jobOffer) {
    echo '<p style="color: red; text-align: center;">Job offer not found or no longer available.</p>';
    exit;
}

// Fetch job offer statistics (total applications, approved, rejected)
$statsSql = "SELECT total_applications, total_approved, total_rejected 
             FROM job_offer_stats 
             WHERE job_offer_id = ?";
$statsStmt = mysqli_prepare($conn, $statsSql);

if (!$statsStmt) {
    // Output error message if the SQL statement preparation fails
    die('MySQL prepare error: ' . mysqli_error($conn));
}

mysqli_stmt_bind_param($statsStmt, "i", $jobOfferId);
mysqli_stmt_execute($statsStmt);
$statsResult = mysqli_stmt_get_result($statsStmt);
$jobStats = mysqli_fetch_assoc($statsResult);

// Check if the user has already applied for this job
if (isset($_SESSION['user_id'])) {
    $userAppliedSql = "SELECT id FROM applications WHERE user_id = ? AND job_offer_id = ?";
    $userAppliedStmt = mysqli_prepare($conn, $userAppliedSql);

    if (!$userAppliedStmt) {
        // Output error message if the SQL statement preparation fails
        die('MySQL prepare error: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($userAppliedStmt, "ii", $_SESSION['user_id'], $jobOfferId);
    mysqli_stmt_execute($userAppliedStmt);
    $userAppliedResult = mysqli_stmt_get_result($userAppliedStmt);
    $userApplied = mysqli_fetch_assoc($userAppliedResult);
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details - <?php echo htmlspecialchars($jobOffer['title']); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/a.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <style>
        /* Job Details Page Styling */
        .job-details {
            background-color: #d3c1ff;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .job-details h1 {
            font-weight: bold;
            color: purple;
        }

        .job-details p {
            color: #333333;
            font-size: 16px;
        }

        .job-stats, .job-summary {
            margin-top: 20px;
            color: purple;
        }

        .job-stats h5, .job-summary h5 {
            color: purple;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .btn-primary {
            background-color: #4b0082;
            border-color: #4b0082;
        }

        .btn-primary:hover {
            background-color: #3a0068;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
            <div class="container">
                <a class="navbar-brand" href="#">BREATHE NEWS</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="home1.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="category1.php">Categories</a></li>
                        <li class="nav-item"><a class="nav-link" href="laws.php">LAWS</a></li>
                        <li class="nav-item"><a class="nav-link" href="quiz1.php">Quizzes</a></li>
                        <li class="nav-item"><a class="nav-link" href="polls1.php">Polls</a></li>
                        <li class="nav-item"><a class="nav-link" href="hiringoffers1.php">Hiring Offers</a></li>
                        <li class="nav-item"><a class="nav-link" href="manageaccount.php">Manage Account</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-primary text-white" style="background-color:purple" href="logout.php">Log Out</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="container mt-5 pt-5">
        <div class="job-details">
            <h1><?php echo htmlspecialchars($jobOffer['title']); ?></h1>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($jobOffer['location']); ?></p>
            <p><strong>Salary:</strong> <?php echo htmlspecialchars($jobOffer['salary']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($jobOffer['status'])); ?></p>
            <p><strong>Created At:</strong> <?php echo htmlspecialchars(date('F j, Y', strtotime($jobOffer['created_at']))); ?></p>
            <p><strong>Last Updated:</strong> <?php echo htmlspecialchars(date('F j, Y', strtotime($jobOffer['updated_at']))); ?></p>

            <div class="job-summary">
                <h5>Summary:</h5>
                <p><?php echo nl2br(htmlspecialchars(substr($jobOffer['description'], 0, 70000)) . '...'); ?></p>
            </div>

            <?php if ($jobStats): ?>
            <div class="job-stats">
                <h5>Job Offer Statistics:</h5>
                <p>Total Applications: <?php echo htmlspecialchars($jobStats['total_applications']); ?></p>
                <p>Total Approved: <?php echo htmlspecialchars($jobStats['total_approved']); ?></p>
                <p>Total Rejected: <?php echo htmlspecialchars($jobStats['total_rejected']); ?></p>
            </div>
            <?php endif; ?>

            <!-- Apply Button -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($userApplied): ?>
                    <p class="mt-4" style="color: green;">You have already applied for this job.</p>
                <?php else: ?>
                    <a href="apply_job.php?id=<?php echo $jobOfferId; ?>" class="btn btn-primary mt-4">Apply for this Job</a>
                <?php endif; ?>
            <?php else: ?>
                <p class="mt-4"><a href="apply_job.php" class="btn btn-warning">Apply for Job</a></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="quiz-footer">
        <p><?php include 'include/footer1.php'; ?></p>
    </div>
</body>
</html>
