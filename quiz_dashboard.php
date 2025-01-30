<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

include 'db/connection.php';

// Fetch total quizzes
$totalQuizzesQuery = "SELECT COUNT(*) AS total FROM quizzes";
$totalQuizzesResult = $conn->query($totalQuizzesQuery);
$totalQuizzes = $totalQuizzesResult->fetch_assoc()['total'];

// Fetch active quizzes
$activeQuizzesQuery = "SELECT COUNT(*) AS active FROM quizzes WHERE status='active'";
$activeQuizzesResult = $conn->query($activeQuizzesQuery);
$activeQuizzes = $activeQuizzesResult->fetch_assoc()['active'];

// Fetch pending reviews
$pendingReviewsQuery = "SELECT COUNT(*) AS pending FROM quizzes WHERE status='pending_review'";
$pendingReviewsResult = $conn->query($pendingReviewsQuery);
$pendingReviews = $pendingReviewsResult->fetch_assoc()['pending'];

// Fetch quiz attempts
$quizAttemptsQuery = "SELECT COUNT(*) AS attempts FROM quiz_attempts";
$quizAttemptsResult = $conn->query($quizAttemptsQuery);
$quizAttempts = $quizAttemptsResult->fetch_assoc()['attempts'];

// Fetch users who played quizzes
$usersPlayedQuery = "SELECT COUNT(DISTINCT user_id) AS users FROM quiz_attempts";
$usersPlayedResult = $conn->query($usersPlayedQuery);
$usersPlayed = $usersPlayedResult->fetch_assoc()['users'];

// Example data for top quizzes
$topQuizzesQuery = "SELECT title, COUNT(*) AS attempts FROM quiz_attempts JOIN quizzes ON quiz_attempts.quiz_id = quizzes.id GROUP BY quiz_id ORDER BY attempts DESC LIMIT 5";
$topQuizzesResult = $conn->query($topQuizzesQuery);
$topQuizzes = [];
while ($row = $topQuizzesResult->fetch_assoc()) {
    $topQuizzes[] = $row;
}

// Close the connection
$conn->close();

// Encode the data as JSON for use in the frontend
$quizData = [
    "total_quizzes" => $totalQuizzes,
    "active_quizzes" => $activeQuizzes,
    "pending_reviews" => $pendingReviews,
    "quiz_attempts" => $quizAttempts,
    "users_played" => $usersPlayed,
    "top_quizzes" => $topQuizzes
];
$quizJson = json_encode($quizData);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Custom styles for the dashboard */
        .dashboard-container {
            padding: 10px;
            width: 80%; /* Adjusted width */
            margin: 20px auto; /* Centered and added margin to move below search bar */
        }
        .dashboard-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 8px; /* Reduced padding */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            font-size: 14px; /* Smaller font size */
        }
        .dashboard-item h3 {
            margin: 0;
            font-size: 16px; /* Smaller heading size */
        }
        .dashboard-item p {
            font-size: 18px; /* Smaller text size */
            margin: 0;
            font-weight: bold;
        }
        .quick-actions, .management-dashboard {
            margin-top: 15px;
        }
        .quick-actions button, .management-dashboard button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 8px 12px; /* Smaller button padding */
            margin-right: 8px;
            cursor: pointer;
            font-size: 14px; /* Smaller button text size */
        }
        .quick-actions button:hover, .management-dashboard button:hover {
            background-color: #0056b3;
        }
        .management-dashboard {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 8px; /* Reduced padding */
            width: 100%;
            max-width: 350px; /* Reduced width */
            margin-left: 0; /* Align to left */
        }
        .management-dashboard h2 {
            margin-top: 0;
            font-size: 18px; /* Smaller heading size */
        }
        .search-bar {
            margin: 20px 0; /* Margin for search bar */
        }
        .chart-container {
            margin: 20px 0; /* Space between the charts and dashboard items */
        }
    </style>
</head>
<body>
<?php include "include/quiz_header.php"; ?>
    <div class="container">
        <div class="dashboard-container">
            <header class="row">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                        <h1 class="h2">Quiz Dashboard</h1>
                    </div>
                </div>
            </header>
           
            <main role="main" class="row" style="text-align:left">
                <div class="col-md-12">
                    <div class="row">
                        <!-- Dashboard Items -->
                        <div class="col-md-4 mb-3" style="text-align:left">
                            <div class="dashboard-item">
                                <h3>Total Quizzes</h3>
                                <p id="total-quizzes"></p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="dashboard-item">
                                <h3>Active Quizzes</h3>
                                <p id="active-quizzes"></p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="dashboard-item">
                                <h3>Pending Reviews</h3>
                                <p id="pending-reviews"></p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="dashboard-item">
                                <h3>Quiz Attempts</h3>
                                <p id="quiz-attempts"></p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="dashboard-item">
                                <h3>Users Played</h3>
                                <p id="users-played"></p>
                            </div>
                        </div>
                    </div>

                    <div class="quick-actions">
                        <button onclick="location.href='add_quiz.php'">Create New Quiz</button>
                        <button onclick="location.href='create_quiz.php'">Manage Quizzes</button>
                    </div>

                    <div class="management-dashboard">
                        <h2>Top 5 Quizzes</h2>
                        <ul>
                            <?php foreach ($topQuizzes as $quiz): ?>
                                <li><?php echo htmlspecialchars($quiz['title']); ?> - Attempts: <?php echo $quiz['attempts']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Chart Containers -->
                    <div class="chart-container">
                        <canvas id="quizAttemptsChart"></canvas>
                    </div>
                    <div class="chart-container">
                        <canvas id="quizStatusChart"></canvas>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Parse and display the dashboard data
        const quizData = <?php echo $quizJson; ?>;
        document.getElementById('total-quizzes').textContent = quizData.total_quizzes;
        document.getElementById('active-quizzes').textContent = quizData.active_quizzes;
        document.getElementById('pending-reviews').textContent = quizData.pending_reviews;
        document.getElementById('quiz-attempts').textContent = quizData.quiz_attempts;
        document.getElementById('users-played').textContent = quizData.users_played;

        // Quiz Attempts Chart (Bar Chart)
        const ctxAttempts = document.getElementById('quizAttemptsChart').getContext('2d');
        const quizAttemptsChart = new Chart(ctxAttempts, {
            type: 'bar',
            data: {
                labels: ['Quiz Attempts', 'Users Played'],
                datasets: [{
                    label: '# of Attempts',
                    data: [quizData.quiz_attempts, quizData.users_played],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 99, 132, 0.5)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Quiz Status Chart (Pie Chart)
        const ctxStatus = document.getElementById('quizStatusChart').getContext('2d');
        const quizStatusChart = new Chart(ctxStatus, {
            type: 'pie',
            data: {
                labels: ['Active', 'Pending Review'],
                datasets: [{
                    label: 'Quiz Status',
                    data: [quizData.active_quizzes, quizData.pending_reviews],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Quiz Status Overview'
                    }
                }
            }
        });
    </script>
</body>
</html>
