<?php
session_start();
include 'db/connection.php';

// Function to fetch data from the database
function fetchData($conn, $table, $order, $limit, $status = null, $extraCondition = null) {
    $condition = $status ? "WHERE status = '$status'" : '';
    if ($extraCondition) {
        $condition .= $condition ? " AND $extraCondition" : "WHERE $extraCondition";
    }
    $sql = "SELECT * FROM $table $condition ORDER BY $order LIMIT $limit";
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}



// Fetch Laws Articles based on category_name = 'laws'
$extraCondition = "category_name = 'laws'";  // Adding condition for category_name
$lawsArticles = fetchData($conn, 'articles', 'publish_date ASC', 12, null, $extraCondition);  // Fetching articles with extra condition
$chunkedArticles = array_chunk($lawsArticles, 3);  // Chunking the laws articles


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laws Articles - Breathe News</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/a.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

<!-- Laws Articles Section -->
<div class="row mb-4" style="background-color:#d3c1ff; margin-top:100px; width:1200px; margin-left:80px">
    <div class="col-md-12">
        <h2>Laws Articles</h2>
        <div id="lawsArticlesCarousel" class="carousel slide" data-ride="carousel" data-interval="3000">
            <div class="carousel-inner">
                <?php if ($lawsArticles): ?>
                    <?php foreach ($chunkedArticles as $index => $articleItems): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : ''; ?>">
                            <div class="row">
                                <?php foreach ($articleItems as $articleItem): ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card">
                                            <img src="uploads/images/<?= htmlspecialchars($articleItem['thumbnail']); ?>" class="card-img-top" alt="<?= htmlspecialchars($articleItem['title']); ?>">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= htmlspecialchars($articleItem['title']); ?></h5>
                                                <p class="card-text" style="color:black"><?= htmlspecialchars($articleItem['description']); ?></p>
                                                <a href="l.php?id=<?= $articleItem['id']; ?>" class="btn btn-primary">Read Full Article</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="carousel-item active">
                        <div class="row">
                            <div class="col-md-12">
                                <p>No laws articles available.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Navigation controls -->
            <div class="d-flex justify-content-between align-items-center" style="position: absolute; width: 100%; top: 50%; transform: translateY(-50%);">
                <a class="carousel-control-prev" href="#lawsArticlesCarousel" role="button" data-slide="prev">
                    <i class="fas fa-chevron-left" aria-hidden="true"></i>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#lawsArticlesCarousel" role="button" data-slide="next">
                    <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'include/footer1.php'; ?>
  

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
