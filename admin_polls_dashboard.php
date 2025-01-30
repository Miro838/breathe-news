<?php
session_start(); // Ensure session is started

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}
include 'db/connection.php'; // Include database connection

// Fetch total polls
$pollsQuery = "SELECT COUNT(*) AS total_polls FROM polls";
$pollsResult = $conn->query($pollsQuery);
if (!$pollsResult) {
    die('Error: ' . $conn->error);
}
$totalPolls = $pollsResult->fetch_assoc()['total_polls'];

// Fetch total options
$optionsQuery = "SELECT COUNT(*) AS total_options FROM poll_options";
$optionsResult = $conn->query($optionsQuery);
if (!$optionsResult) {
    die('Error: ' . $conn->error);
}
$totalOptions = $optionsResult->fetch_assoc()['total_options'];

// Fetch statistics for charts
$pollStatsQuery = "SELECT poll_id, COUNT(*) AS total_votes FROM poll_votes GROUP BY poll_id";
$pollStatsResult = $conn->query($pollStatsQuery);
if (!$pollStatsResult) {
    die('Error: ' . $conn->error);
}
$pollStats = [];
while ($row = $pollStatsResult->fetch_assoc()) {
    $pollStats[] = $row;
}

// Fetch polls by status
$pollsStatusQuery = "SELECT status, COUNT(*) AS count FROM polls GROUP BY status";
$pollsStatusResult = $conn->query($pollsStatusQuery);
if (!$pollsStatusResult) {
    die('Error: ' . $conn->error);
}
$pollsStatus = [];
while ($row = $pollsStatusResult->fetch_assoc()) {
    $pollsStatus[] = $row;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        
        .card {
            font-size: 1rem; /* Increase card font size */
            padding: 1rem; /* Increase padding for better spacing */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add a shadow for a lifted effect */
        }
        .card-title {
            font-size: 1.25rem; /* Increase the size of the card title */
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .card-text {
            font-size: 1.75rem; /* Increase the size of the card text */
            font-weight: bold;
        }
        .chart-container {
            margin-bottom: 20px;
            max-width: 800px;
        }
        .chart-container canvas {
            height: 400px !important;
            width: 100% !important;
        }
        .btn {
            font-size: 0.875rem;
            padding: 0.5rem;
        }
        .content-center {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1, h2 { text-align: center; }
        /* Custom styles for larger card width */
        .card-container {
            width: 100%;
            max-width: 900px; /* Set a larger maximum width for the card container */
            margin: 0 auto; /* Center the card container horizontally */
        }
    </style>
</head>
<body>
    <?php include 'include/polls.php'; ?>
    <div class="container">
        <h1 class="my-4">Poll Dashboard</h1>
        <div class="content-center">
            <div class="row mb-3">
                <!-- Overview Cards -->
                <div class="col-md-6 mb-3 card-container">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Total Polls</h5>
                            <p class="card-text text-center"><?php echo number_format($totalPolls); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3 card-container">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Total Options</h5>
                            <p class="card-text text-center"><?php echo number_format($totalOptions); ?></p>
                        </div>
                    </div>
                </div>
                <!-- Links to manage polls -->
                <div class="col-md-12 mb-3 text-center">
                    <!-- Add buttons or other content here if needed -->
                </div>
            </div>

            <!-- Charts -->
            <h2 class="my-4">Poll Statistics</h2>
            <div class="chart-container">
                <canvas id="pollStatsChart"></canvas>
            </div>

            <h2 class="my-4">Polls by Status</h2>
            <div class="chart-container">
                <canvas id="pollsStatusChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Poll Statistics Chart
        const ctx1 = document.getElementById('pollStatsChart').getContext('2d');
        const pollStats = <?php echo json_encode($pollStats); ?>;
        const labels1 = pollStats.map(stat => 'Poll ' + stat.poll_id);
        const totalVotes = pollStats.map(stat => stat.total_votes);

        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: labels1,
                datasets: [{
                    label: 'Total Votes',
                    data: totalVotes,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
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

        // Polls by Status Chart
        const ctx2 = document.getElementById('pollsStatusChart').getContext('2d');
        const pollsStatus = <?php echo json_encode($pollsStatus); ?>;
        const labels2 = pollsStatus.map(stat => stat.status);
        const counts2 = pollsStatus.map(stat => stat.count);

        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: labels2,
                datasets: [{
                    data: counts2,
                    backgroundColor: ['#36a2eb', '#ff6384', '#ffcd56', '#4bc0c0']
                }]
            }
        });
    </script>
   
</body>
</html>
