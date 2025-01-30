<?php 
session_start(); 

// Include necessary files for database connection and user session management
include('db/connection.php'); // Assuming this file contains the database connection

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: admin_login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user ID

// Fetch Session Activity (for login/logout details)
$session_query = "SELECT * FROM session_activity WHERE user_id = ? ORDER BY login_time DESC";
$session_stmt = $conn->prepare($session_query);
$session_stmt->bind_param('i', $user_id);
$session_stmt->execute();
$session_result = $session_stmt->get_result();

// Close the statement
$session_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Activity - Breathe News</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css"> <!-- Your custom stylesheet -->
    <link rel="stylesheet" href="style/a.css"> <!-- Your custom stylesheet -->
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

    <div class="container">
        <h1>Account Activity</h1>

        <!-- Session Activity -->
        <h2>Session History</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Session ID</th>
                    <th>Login Time</th>
                    <th>Logout Time</th>
                    <th>IP Address</th>
                    <th>Device Info</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($session = $session_result->fetch_assoc()) { 
                    // Determine status based on logout time
                    $status = $session['status'];  // This will already contain the 'active', 'inactive', or 'expired' status
                    if ($session['logout_time'] === NULL) {
                        $status = 'Active';  // Active if logout time is NULL
                    } elseif ($status == 'inactive') {
                        $status = 'Inactive';  // Explicitly show 'Inactive' if that is the status
                    } elseif ($status == 'expired') {
                        $status = 'Expired';  // Explicitly show 'Expired' if that is the status
                    }
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($session['session_id']); ?></td>
                        <td><?php echo $session['login_time']; ?></td>
                        <td><?php echo $session['logout_time'] ? $session['logout_time'] : 'Active'; ?></td>
                        <td><?php echo htmlspecialchars($session['ip_address']); ?></td>
                        <td><?php echo htmlspecialchars($session['device_info']); ?></td>
                        <td><?php echo $status; ?></td> <!-- Display the processed status -->
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>
</body>
</html>
