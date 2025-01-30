<?php
session_start(); // Start the session
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}
include 'db/connection.php'; // Include your database connection file

// Fetch total job offers
$jobOffersQuery = "SELECT COUNT(*) AS total_job_offers FROM job_offers";
$jobOffersResult = $conn->query($jobOffersQuery);
$jobOffersCount = $jobOffersResult->fetch_assoc()['total_job_offers'];

// Fetch total applications
$applicationsQuery = "SELECT COUNT(*) AS total_applications FROM applications";
$applicationsResult = $conn->query($applicationsQuery);
$applicationsCount = $applicationsResult->fetch_assoc()['total_applications'];

// Fetch statistics for charts
$statsQuery = "SELECT job_offer_id, total_applications, total_approved, total_rejected FROM job_offer_stats";
$statsResult = $conn->query($statsQuery);
$jobOfferStats = [];
while ($row = $statsResult->fetch_assoc()) {
    $jobOfferStats[] = $row;
}

// Fetch job offers by status
$jobOffersStatusQuery = "SELECT status, COUNT(*) AS count FROM job_offers GROUP BY status";
$jobOffersStatusResult = $conn->query($jobOffersStatusQuery);
$jobOffersStatus = [];
while ($row = $jobOffersStatusResult->fetch_assoc()) {
    $jobOffersStatus[] = $row;
}

// Fetch applications by status
$applicationsStatusQuery = "SELECT status, COUNT(*) AS count FROM applications GROUP BY status";
$applicationsStatusResult = $conn->query($applicationsStatusQuery);
$applicationsStatus = [];
while ($row = $applicationsStatusResult->fetch_assoc()) {
    $applicationsStatus[] = $row;
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
            font-size: 0.875rem;
        }
        .card-title {
            font-size: 1rem;
        }
        .chart-container {
            margin-bottom: 20px;
            max-width: 800px; /* Increase max-width to make container wider */
        }
        .chart-container canvas {
            height: 400px !important; /* Increase chart height */
            width: 100% !important;   /* Ensure the width is 100% of its container */
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
        h5{text-align: center;}
    </style>
</head>
<body>
    <?php include 'include/hiringoffer.php'; ?>
    <div class="container">
        <h1 class="my-4 text-center">Admin Dashboard</h1>
        <div class="content-center">
            <div class="row mb-3">
                <!-- Overview Cards -->
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center"style="width: 200px;height:70px">Total Job Offers</h5>
                            <p class="card-text text-center"><?php echo number_format($jobOffersCount); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center"style="width: 200px;height:70px">Total Applications</h5>
                            <p class="card-text text-center"><?php echo number_format($applicationsCount); ?></p>
                        </div>
                    </div>
                </div>
                <!-- Links to manage job offers and view applications -->
                <div class="col-md-4 mb-3 text-center">
                    <a href="manage_job_offers.php" class="btn btn-primary btn-block">Manage Job Offers</a>
                    <a href="view_applications.php" class="btn btn-primary btn-block">View Applications</a>
                </div>
            </div>

            <!-- Charts -->
            <h2 class="my-4 text-center">Job Offer Statistics</h2>
            <div class="chart-container">
                <canvas id="jobOfferChart"></canvas>
            </div>

            <h2 class="my-4 text-center">Job Offers by Status</h2>
            <div class="chart-container">
                <canvas id="jobOffersStatusChart"></canvas>
            </div>

            <h2 class="my-4 text-center">Applications by Status</h2>
            <div class="chart-container">
                <canvas id="applicationsStatusChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Job Offer Statistics Chart
        const ctx1 = document.getElementById('jobOfferChart').getContext('2d');
        const jobOfferStats = <?php echo json_encode($jobOfferStats); ?>;
        const labels1 = jobOfferStats.map(stat => 'Job Offer ' + stat.job_offer_id);
        const totalApplications = jobOfferStats.map(stat => stat.total_applications);
        const totalApproved = jobOfferStats.map(stat => stat.total_approved);
        const totalRejected = jobOfferStats.map(stat => stat.total_rejected);

        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: labels1,
                datasets: [
                    {
                        label: 'Total Applications',
                        data: totalApplications,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Total Approved',
                        data: totalApproved,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Total Rejected',
                        data: totalRejected,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Job Offers by Status Chart
        const ctx2 = document.getElementById('jobOffersStatusChart').getContext('2d');
        const jobOffersStatus = <?php echo json_encode($jobOffersStatus); ?>;
        const labels2 = jobOffersStatus.map(stat => stat.status);
        const counts2 = jobOffersStatus.map(stat => stat.count);

        new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: labels2,
                datasets: [{
                    data: counts2,
                    backgroundColor: ['#36a2eb', '#ff6384', '#ffcd56', '#4bc0c0'] // Added more colors for diverse statuses
                }]
            }
        });

        // Applications by Status Chart
        const ctx3 = document.getElementById('applicationsStatusChart').getContext('2d');
        const applicationsStatus = <?php echo json_encode($applicationsStatus); ?>;
        const labels3 = applicationsStatus.map(stat => stat.status);
        const counts3 = applicationsStatus.map(stat => stat.count);

        new Chart(ctx3, {
            type: 'pie',
            data: {
                labels: labels3,
                datasets: [{
                    data: counts3,
                    backgroundColor: ['#ffcd56', '#4bc0c0', '#ff9f40', '#36a2eb'] // Added more colors for diverse statuses
                }]
            }
        });
    </script>
   
</body>
</html>
