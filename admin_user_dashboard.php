<?php
include 'db/connection.php';
session_start();

// Check if user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

// Get search term from GET request
$search_term = $_GET['search_term'] ?? '';

// Initialize variables for statistics
$total_users = $active_users = $inactive_users = $banned_users = 0;

// Fetch user statistics
try {
    $total_users_query = "SELECT COUNT(*) AS total FROM admin_login";
    $result = $conn->query($total_users_query);
    $total_users = $result->fetch_assoc()['total'];

    $active_users_query = "SELECT COUNT(*) AS active FROM admin_login WHERE status = 'active'";
    $result = $conn->query($active_users_query);
    $active_users = $result->fetch_assoc()['active'];

    $inactive_users_query = "SELECT COUNT(*) AS inactive FROM admin_login WHERE status = 'inactive'";
    $result = $conn->query($inactive_users_query);
    $inactive_users = $result->fetch_assoc()['inactive'];

    $banned_users_query = "SELECT COUNT(*) AS banned FROM admin_login WHERE status = 'banned'";
    $result = $conn->query($banned_users_query);
    $banned_users = $result->fetch_assoc()['banned'];
} catch (Exception $e) {
    echo "Error fetching user statistics: " . $e->getMessage();
    exit();
}

// Fetch recent activities


// Fetch user status data for chart
try {
    $statuses_query = "SELECT status, COUNT(*) AS count FROM admin_login GROUP BY status";
    $statuses_result = $conn->query($statuses_query);
    
    $statuses_data = [];
    while ($row = $statuses_result->fetch_assoc()) {
        $statuses_data[] = $row;
    }
} catch (Exception $e) {
    echo "Error fetching user status data: " . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .content {
            display: flex;
            flex-direction: column;
            width: calc(100% - 250px);
            margin-left: 250px;
            padding: 20px;
        }
        .dashboard-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .card {
            flex: 1 1 calc(50% - 20px);
            max-width: calc(100% - 50px);
        }
        .card-body p {
            margin: 0;
        }
        
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <?php include 'include/admin_user.php';?>
    </div>

    <!-- Main Content -->
    <div class="content">
        <header class="row mb-14">
            <div class="col-md-12">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                    <h1 class="h2">Admin Dashboard</h1>
                </div>
            </div>
        </header>

        <!-- Dashboard Grid -->
        <div class="dashboard-wrapper">
            <!-- User Statistics -->
            <div class="card">
                <div class="card-body">
                    <h3>User Statistics</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Total Users</h5>
                            <p><?php echo htmlspecialchars($total_users); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h5>Active Users</h5>
                            <p><?php echo htmlspecialchars($active_users); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h5>Inactive Users</h5>
                            <p><?php echo htmlspecialchars($inactive_users); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h5>Banned Users</h5>
                            <p><?php echo htmlspecialchars($banned_users); ?></p>
                        </div>
                    </div>
                    <canvas id="statusChart" width="400" height="300"></canvas>
                </div>
            </div>

            <!-- Recent Activities -->
           
   
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data for the status chart
            const statuses = <?php echo json_encode($statuses_data); ?>;
            const statusLabels = statuses.map(status => status.status);
            const statusCounts = statuses.map(status => status.count);

            // Chart.js setup
            const ctx = document.getElementById('statusChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        label: 'User Status Distribution',
                        data: statusCounts,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                        borderColor: '#fff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
