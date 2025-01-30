<?php

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



// Fetch Latest News for the Carousel
$latestNews = fetchData($conn, 'news', 'date ASC', 5, 'published');

// Fetch Trending News
$trendingNews = fetchData($conn, 'articles', 'publish_date ASC', 6);
$chunkedNews = array_chunk($trendingNews, 3);

// Fetch Exclusive Videos
$exclusiveVideos = fetchData($conn, 'articles', 'publish_date ASC', 6, null, 'video IS NOT NULL AND video != ""');

// Fetch Top Shows
$topShows = fetchData($conn, 'top_shows', 'publish_date ASC', 6, 'active');


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Poll</title>
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
            <li class="nav-item"><a class="nav-link" href="main.php">Main</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="category.php">Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="quiz.php">Quizzes</a></li>
                <li class="nav-item"><a class="nav-link" href="polls.php">Polls</a></li>
                <li class="nav-item"><a class="nav-link" href="hiringoffers.php">Hiring Offers</a></li>
                <li class="nav-item"><a class="nav-link btn btn-primary text-white" style="background-color:purple" href="signup.php">Sign Up</a></li>
               
            </ul>
        </div>
    </div>
</nav>

<!-- Home Page Content -->
<div id="homePage" class="container mt-5 pt-5">
    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-md-12">
            <form action="search.php" method="post" class="w-100 d-flex">
                <input type="text" id="searchInput" name="query" class="form-control" placeholder="Search articles by article name, or crime..." aria-label="Search articles">
                <div class="input-group-append">
                    <button id="searchButton" class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Breaking Crime News Section -->
    <div class="row mb-4" style="background-color:#d3c1ff; ">
        <div class="col-md-12">
            <h2>Breaking Crime News</h2>
            <div id="newsCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <?php if ($latestNews): ?>
                        <?php foreach ($latestNews as $index => $newsItem): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : ''; ?>">
                                <img src="<?= htmlspecialchars($newsItem['thumbnail']); ?>" class="d-block w-100" alt="<?= htmlspecialchars($newsItem['title']); ?>" style="width:65%;height:600px">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5><?= htmlspecialchars($newsItem['title']); ?></h5>
                                    <p class="text mt-2" style="color:white"><?= htmlspecialchars($newsItem['description']); ?></p>
                                    <a href="signup.php?id=<?= $newsItem['id']; ?>" class="btn btn-primary">Sign Up to Read More</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="carousel-item active">
                            <img src="https://via.placeholder.com/1100x500" class="d-block w-100" alt="No News">
                            <div class="carousel-caption d-none d-md-block">
                                <h5>No News Available</h5>
                                <p>No breaking news articles to display at this time.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <a class="carousel-control-prev" href="#newsCarousel" role="button" data-slide="prev">
                 <i class="fas fa-chevron-left" aria-hidden="true"></i>
                 <span class="sr-only">Previous</span>
                </a>

                <a class="carousel-control-next" href="#newsCarousel" role="button" data-slide="next">
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
                <span class="sr-only">Next</span>
                </a>

            </div>
        </div>
    </div>

    

    <!-- Trending News Section -->
<div class="row mb-4" style="background-color:#d3c1ff">
    <div class="col-md-12">
        <h2>Trending News</h2>
        <div id="trendingNewsCarousel" class="carousel slide" data-ride="carousel" data-interval="3000">
            <div class="carousel-inner">
                <?php if ($trendingNews): ?>
                    <?php foreach ($chunkedNews as $index => $newsItems): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : ''; ?>">
                            <div class="row">
                                <?php foreach ($newsItems as $newsItem): ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card">
                                            <img src="uploads/images/<?= htmlspecialchars($newsItem['thumbnail']); ?>" class="card-img-top" alt="<?= htmlspecialchars($newsItem['title']); ?>">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= htmlspecialchars($newsItem['title']); ?></h5>
                                                <p class="card-text" style="color:black"><?= htmlspecialchars($newsItem['description']); ?></p>
                                                <a href="signup.php?id=<?= $newsItem['id']; ?>" class="btn btn-primary">Sign Up to Read More</a>
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
                                <p>No trending news available.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Navigation container -->
            <div class="d-flex justify-content-between align-items-center" style="position: absolute; width: 100%; top: 50%; transform: translateY(-50%);">
                <!-- Left Arrow for Previous -->
                <div class="d-flex justify-content-between align-items-center" style="position: absolute; width: 100%; top: 50%; transform: translateY(-50%);">
    <a class="carousel-control-prev" href="#trendingNewsCarousel" role="button" data-slide="prev">
        <i class="fas fa-chevron-left" aria-hidden="true"></i>
        <span class="sr-only">Previous</span>
    </a>

    <a class="carousel-control-next" href="#trendingNewsCarousel" role="button" data-slide="next">
        <i class="fas fa-chevron-right" aria-hidden="true"></i>
        <span class="sr-only">Next</span>
    </a>
</div>

            </div>
        </div>
    </div>
</div>

   
   <!-- Exclusive Videos Section -->
<div class="row mb-4" style="background-color:#d3c1ff">
    <div class="col-md-12">
        <h2>Exclusive Videos</h2>
        <div id="exclusiveVideosCarousel" class="carousel slide" data-ride="carousel" data-interval="3000">
            <div class="carousel-inner">
                <?php if ($exclusiveVideos): ?>
                    <?php 
                    // Split the videos into chunks of 3
                    $videoChunks = array_chunk($exclusiveVideos, 3);
                    foreach ($videoChunks as $chunkIndex => $videos): ?>
                        <div class="carousel-item <?= $chunkIndex === 0 ? 'active' : ''; ?>">
                            <div class="row">
                                <?php foreach ($videos as $video): ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card">
                                            <video width="100%" controls>
                                                <source src="uploads/videos/<?= htmlspecialchars($video['video']); ?>" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                            <div class="card-body">
                                                <h5 class="card-title"><?= htmlspecialchars($video['video_title']); ?></h5>
                                                <p class="card-text"  style="color:black"><?= htmlspecialchars($video['video_description']); ?></p>
                                                <a href="signup.php?id=<?= $video['id']; ?>" class="btn btn-primary">Watch Video</a>
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
                                <p>No exclusive videos available.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
       <!-- Navigation container -->
       <div class="d-flex justify-content-between align-items-center" style="position: absolute; width: 100%; top: 50%; transform: translateY(-50%);">
                <!-- Left Arrow for Previous -->
                <!-- Left Arrow for Previous -->
<a class="carousel-control-prev" href="#topShowsCarousel" role="button" data-slide="prev">
    <i class="fas fa-chevron-left" aria-hidden="true"></i>
    <span class="sr-only">Previous</span>
</a>

<!-- Right Arrow for Next -->
<a class="carousel-control-next" href="#topShowsCarousel" role="button" data-slide="next">
    <i class="fas fa-chevron-right" aria-hidden="true"></i>
    <span class="sr-only">Next</span>
</a>

            </div>
        </div>
    </div>
</div>
    <!-- Top Shows Section -->
    <div class="row mb-4" style="background-color:#d3c1ff">
    <div class="col-md-12">
        <h2>Top Shows</h2>
        <div id="topShowsCarousel" class="carousel slide" data-ride="carousel" data-interval="3000">
            <div class="carousel-inner">
                <?php if ($topShows): ?>
                    <?php foreach (array_chunk($topShows, 3) as $index => $showsChunk): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : ''; ?>">
                            <div class="row">
                                <?php foreach ($showsChunk as $show): ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card">
                                            <img src="uploads/thumbnails/<?= htmlspecialchars($show['thumbnail'] ?: 'default-thumbnail.jpg'); ?>" class="card-img-top" alt="<?= htmlspecialchars($show['title']); ?>">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= htmlspecialchars($show['title']); ?></h5>
                                                <p class="card-text"  style="color:black"><?= htmlspecialchars($show['description']); ?></p>
                                                <a href="signup.php?show_id=<?= $show['id']; ?>" class="btn btn-primary">Sign Up to View Show</a>
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
                                <p>No top shows available at this time.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Navigation container -->
            <div class="d-flex justify-content-between align-items-center" style="position: absolute; width: 100%; top: 50%; transform: translateY(-50%);">
                <!-- Left Arrow for Previous -->
                <!-- Left Arrow for Previous -->
<a class="carousel-control-prev" href="#topShowsCarousel" role="button" data-slide="prev">
    <i class="fas fa-chevron-left" aria-hidden="true"></i>
    <span class="sr-only">Previous</span>
</a>

<!-- Right Arrow for Next -->
<a class="carousel-control-next" href="#topShowsCarousel" role="button" data-slide="next">
    <i class="fas fa-chevron-right" aria-hidden="true"></i>
    <span class="sr-only">Next</span>
</a>

            </div>
        </div>
    </div>
</div>


<footer><?PHP include 'include/footer1.php'; ?>
         <p style="text-align:center">&copy; 2024 BREATHE NEWS. All rights reserved.</p>
    </footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
