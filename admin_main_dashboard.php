<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

include "db/connection.php"; // Database connection

// Fetch Total Users
$totalUsersQuery = "SELECT COUNT(*) as total_users FROM admin_login";
$totalUsersResult = mysqli_query($conn, $totalUsersQuery);
$totalUsers = mysqli_fetch_assoc($totalUsersResult)['total_users'];

// Fetch Total Articles
$totalArticlesQuery = "SELECT COUNT(*) as total_articles FROM news";
$totalArticlesResult = mysqli_query($conn, $totalArticlesQuery);
$totalArticles = mysqli_fetch_assoc($totalArticlesResult)['total_articles'];

// Fetch Total Comments
$totalCommentsQuery = "SELECT COUNT(*) as total_comments FROM comments";
$totalCommentsResult = mysqli_query($conn, $totalCommentsQuery);
$totalComments = mysqli_fetch_assoc($totalCommentsResult)['total_comments'];

// Fetch Total Categories
$totalCategoriesQuery = "SELECT COUNT(*) as total_categories FROM category";
$totalCategoriesResult = mysqli_query($conn, $totalCategoriesQuery);
$totalCategories = mysqli_fetch_assoc($totalCategoriesResult)['total_categories'];



// Fetch Articles by Category
$categoryStatsQuery = "SELECT c.category_name, COUNT(n.id) as count FROM category c LEFT JOIN news n ON c.id = n.category_id GROUP BY c.category_name";
$categoryStatsResult = mysqli_query($conn, $categoryStatsQuery);

$categoryStats = [];
while ($row = mysqli_fetch_assoc($categoryStatsResult)) {
    $categoryStats[$row['category_name']] = $row['count'];
}

// Prepare data for Chart.js

$categoryStatsJson = json_encode($categoryStats);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
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
            margin-left: 250px; /* Adjust this as necessary */
            padding: 20px;
        }
        .dashboard-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; /* Adjust gap between cards */
        }
        .card {
            flex: 1 1 calc(50% - 20px); /* Adjust to fit below the search bar */
            max-width: calc(50% - 20px); /* Ensure the cards fit side by side */
        }
        canvas {
            max-width: 100%; /* Ensure the charts are responsive */
            height: auto; /* Maintain aspect ratio */
        }
        .charts-wrapper {
            display: flex; /* Display charts in a row */
            justify-content: space-between; /* Space between charts */
            gap: 20px; /* Space between the chart containers */
        }
    </style>
</head>
<body>
    <div class="header">
        <?php include "include/header.php"; ?>
    </div>

    <div class="content">
        <header class="mb-4">
            <h1 class="h2">Admin Dashboard</h1>
        </header>

        <div class="dashboard-wrapper">
            <!-- Key Metrics Cards -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text"><?php echo $totalUsers; ?></p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Comments</h5>
                    <p class="card-text"><?php echo $totalComments; ?></p>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="charts-wrapper mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total News</h5>
                    <canvas id="totalArticlesChart"></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Categories</h5>
                    <canvas id="totalCategoriesChart"></canvas>
                </div>
            </div>
        </div>

        

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Articles by Category</h5>
                    <canvas id="categoryStatsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

   

    <script>
       
        const categoryStatsData = <?php echo $categoryStatsJson; ?>;

        // Total Articles Chart
        const totalArticlesCtx = document.getElementById('totalArticlesChart').getContext('2d');
        new Chart(totalArticlesCtx, {
            type: 'bar',
            data: {
                labels: ['Total News'],
                datasets: [{
                    label: 'News Count',
                    data: [<?php echo $totalArticles; ?>],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Count'
                        }
                    }
                }
            }
        });

        // Total Categories Chart
        const totalCategoriesCtx = document.getElementById('totalCategoriesChart').getContext('2d');
        new Chart(totalCategoriesCtx, {
            type: 'pie',
            data: {
                labels: ['Total Categories'],
                datasets: [{
                    label: 'Categories Count',
                    data: [<?php echo $totalCategories; ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
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
                        text: 'Total Categories'
                    }
                }
            }
        });


        // Category Stats Chart
        const categoryStatsCtx = document.getElementById('categoryStatsChart').getContext('2d');
        new Chart(categoryStatsCtx, {
            type: 'pie',
            data: {
                labels: Object.keys(categoryStatsData),
                datasets: [{
                    label: 'Articles by Category',
                    data: Object.values(categoryStatsData),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
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
                        text: 'Articles by Category'
                    }
                }
            }
        });
    </script>
</body>
</html>
