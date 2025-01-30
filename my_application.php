<?php
session_start();
include 'db/connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Retrieve user's applications from the database (latest status)
$sql = "SELECT job_offers.title, applications.id, applications.status, applications.applied_at 
        FROM applications 
        JOIN job_offers ON applications.job_offer_id = job_offers.id 
        WHERE applications.user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die("Error preparing SQL statement: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$applicationsResult = mysqli_stmt_get_result($stmt);

// Display applications
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/a.css">
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
                <li class="nav-item"><a class="nav-link" href="hiringoffers.php">Hiring Offers</a></li>
                <li class="nav-item"><a class="nav-link" href="manageaccount.php">Manage Account</a></li>
                <li class="nav-item"><a class="nav-link btn btn-primary text-white" style="background-color:purple" href="logout.php">Log Out</a></li>
            </ul>
        </div>
    </div>
</nav>
    

    <div class="container mt-5 pt-5">
        <h1>My Applications</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <?php if (mysqli_num_rows($applicationsResult) > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Status</th>
                        <th>Applied On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($applicationsResult)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td>
                                <?php 
                                if ($row['status'] === 'approved') {
                                    echo "<span class='text-success'>Approved</span>";
                                } elseif ($row['status'] === 'rejected') {
                                    echo "<span class='text-danger'>Rejected</span>";
                                } else {
                                    echo "<span class='text-warning'>Pending</span>";
                                }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['applied_at']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have not applied for any jobs yet.</p>
        <?php endif; ?>

    </div>

    <footer>
        <?php include 'include/footer1.php'; ?>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</bod>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
