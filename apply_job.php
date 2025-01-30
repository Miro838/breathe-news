<?php
session_start();
include 'db/connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Retrieve job offer ID either from POST or GET
$jobOfferId = isset($_POST['id']) ? intval($_POST['id']) : (isset($_GET['id']) ? intval($_GET['id']) : 0);
if ($jobOfferId === 0) {
    die("Job offer ID not provided.");
}

// Check database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the user has already applied for this job
$checkSql = "SELECT id FROM applications WHERE user_id = ? AND job_offer_id = ?";
$checkStmt = mysqli_prepare($conn, $checkSql);
if (!$checkStmt) {
    die("Error preparing check statement: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($checkStmt, "ii", $userId, $jobOfferId);
mysqli_stmt_execute($checkStmt);
$checkResult = mysqli_stmt_get_result($checkStmt);

if (mysqli_num_rows($checkResult) > 0) {
    
    header("Location: job_details.php?id=$jobOfferId");
    exit;
}

// Process form submission if POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if a file is uploaded
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $targetDir = "uploads/resumes/";
        $resumeFileName = basename($_FILES['resume']['name']);
        $targetFilePath = $targetDir . $resumeFileName;

        // Ensure the target directory exists, create it if necessary
        if (!is_dir($targetDir) && !mkdir($targetDir, 0777, true)) {
            die("Failed to create directory: $targetDir");
        }

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($_FILES['resume']['tmp_name'], $targetFilePath)) {
            die("Failed to upload resume.");
        }

        // Sanitize and prepare form data for insertion
        $coverLetter = mysqli_real_escape_string($conn, $_POST['cover_letter']);
        $question1 = mysqli_real_escape_string($conn, $_POST['question1']);
        $question2 = mysqli_real_escape_string($conn, $_POST['question2']);
        $status = 'pending';
        $appliedAt = date('Y-m-d H:i:s');

        // Insert application into the database
        $insertSql = "INSERT INTO applications (user_id, job_offer_id, resume, cover_letter, question1, question2, status, applied_at)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertSql);
        if (!$stmt) {
            die("Error preparing insert statement: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, "iissssss", $userId, $jobOfferId, $targetFilePath, $coverLetter, $question1, $question2, $status, $appliedAt);

        if (mysqli_stmt_execute($stmt)) {
            // Update job_offer_stats table for total applications
            $updateStatsSql = "INSERT INTO job_offer_stats (job_offer_id, total_applications) VALUES (?, 1)
                               ON DUPLICATE KEY UPDATE total_applications = total_applications + 1";
            $statsStmt = mysqli_prepare($conn, $updateStatsSql);
            if (!$statsStmt) {
                die("Error preparing stats update statement: " . mysqli_error($conn));
            }

            mysqli_stmt_bind_param($statsStmt, "i", $jobOfferId);
            mysqli_stmt_execute($statsStmt);
            
            // Set session message to show success and redirect to 'My Applications' page
            $_SESSION['message'] = "Your application has been submitted successfully.";
            header("Location: my_application.php");
            exit;
        } else {
            die("Error inserting application: " . mysqli_error($conn));
        }
    } else {
        die("Please upload a valid resume.");
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Job</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/a.css">
    <style>
        .application-form { background-color: #f8f9fa; border-radius: 15px; padding: 20px; margin-top: 50px; }
        .application-form h1 { color: #4b0082; }
        .application-form label { font-weight: bold; }
        .message { margin-top: 20px; padding: 10px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
   <!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">BREATHE NEWS</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="home1.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="laws.php">LAWS</a></li>
                <li class="nav-item"><a class="nav-link" href="quiz1.php">Quizzes</a></li>
                <li class="nav-item"><a class="nav-link" href="polls.php">Polls</a></li>
                <li class="nav-item"><a class="nav-link" href="hiringoffers1.php">Hiring Offers</a></li>
                <li class="nav-item"><a class="nav-link" href="manageaccount.php">Manage Account</a></li>
                <li class="nav-item"><a class="nav-link btn btn-primary text-white" style="background-color:purple" href="logout.php">Log Out</a></li>
            </ul>
        </div>
    </div>
</nav>

    <div class="container mt-5 pt-5">
        <div class="application-form">
            <h1>Apply for Job</h1>

            <!-- Display success or error message -->
            <?php
            if (isset($_SESSION['message'])) {
                echo '<div class="message">' . $_SESSION['message'] . '</div>';
                unset($_SESSION['message']);
            }
            ?>

            <form action="apply_job.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($jobOfferId); ?>">

                <div class="form-group">
                    <label for="resume">Resume (PDF, DOC):</label>
                    <input type="file" id="resume" name="resume" class="form-control-file" accept=".pdf, .doc, .docx" required>
                </div>

                <div class="form-group">
                    <label for="cover_letter">Cover Letter:</label>
                    <textarea id="cover_letter" name="cover_letter" class="form-control" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="question1">Why do you want this job?</label>
                    <textarea id="question1" name="question1" class="form-control" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="question2">Describe your relevant experience:</label>
                    <textarea id="question2" name="question2" class="form-control" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit Application</button>
            </form>
        </div>
    </div>

    <footer>
        <?php include 'include/footer1.php'; ?>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
