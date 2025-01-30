<?php 
session_start();
include 'db/connection.php';

// Fetch categories for filtering
$categoriesQuery = "SELECT id, category_name FROM category";  // Assuming you have a 'categories' table
$categoriesResult = mysqli_query($conn, $categoriesQuery);

// Check if categories query succeeded
if (!$categoriesResult) {
    die("Categories Query failed: " . mysqli_error($conn));
}

// Fetch articles based on selected category
$categoryFilter = "";
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $category = mysqli_real_escape_string($conn, $_GET['category']);
    $categoryFilter = " WHERE articles.category_name = '$category'";
}

// Query to fetch articles
$articlesQuery = "
    SELECT id, title, description, content, publish_date AS date, category_name, thumbnail
    FROM articles 
    $categoryFilter
    ORDER BY date DESC
";

$articlesResults = mysqli_query($conn, $articlesQuery);

// Check if articles query succeeded
if (!$articlesResults) {
    die("Articles Query failed: " . mysqli_error($conn));
}

// Fetch news based on selected category
$newsFilter = "";
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $newsFilter = " WHERE news.category = '$category'";
}

// Query to fetch news
$newsQuery = "
    SELECT id, title, description, content, date AS date, category AS category_name, thumbnail
    FROM news
    $newsFilter
    ORDER BY date DESC
";

$newsResults = mysqli_query($conn, $newsQuery);

// Check if news query succeeded
if (!$newsResults) {
    die("News Query failed: " . mysqli_error($conn));
}
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

<div class="container mt-5" style="padding-top:60px;">
    <h2 style="text-align:center;background-color:#d3c1ff;height:80px;padding-top:20px;margin-bottom:20px">Content in Category</h2>

    <!-- Category Filter -->
    <form method="GET" action="f.php" class="mb-4">
        <div class="form-group">
            <label for="categorySelect">Filter by Category:</label>
            <select name="category" id="categorySelect" class="form-control">
                <option value="">All Categories</option>
                <?php while ($category = mysqli_fetch_assoc($categoriesResult)): ?>
                    <option value="<?php echo htmlspecialchars($category['category_name']); ?>"
                        <?php echo isset($_GET['category']) && $_GET['category'] === $category['category_name'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['category_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Display Articles -->
    <h3>Articles</h3>
    <div class="row">
        <?php if (mysqli_num_rows($articlesResults) > 0): ?>
            <?php while ($item = mysqli_fetch_assoc($articlesResults)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="uploads/images/<?php echo htmlspecialchars($item['thumbnail']); ?>" class="card-img-top" alt="Thumbnail">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                            <p style="color:black" class="card-text"><?php echo htmlspecialchars($item['description']); ?></p>
                            <p style="color:black"><small>Published on: <?php echo htmlspecialchars($item['date']); ?></small></p>
                            <a href="c.php?id=<?php echo $item['id']; ?>&type=article" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No articles found in this category.</p>
        <?php endif; ?>
    </div>

    <!-- Display News -->
    <h3>News</h3>
    <div class="row">
        <?php if (mysqli_num_rows($newsResults) > 0): ?>
            <?php while ($item = mysqli_fetch_assoc($newsResults)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($item['thumbnail']); ?>" class="card-img-top" alt="Thumbnail">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                            <p style="color:black"class="card-text"><?php echo htmlspecialchars($item['description']); ?></p>
                            <p style="color:black"><small>Published on: <?php echo htmlspecialchars($item['date']); ?></small></p>
                            <a href="b.php?id=<?php echo $item['id']; ?>&type=news" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No news found in this category.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
<?php include 'include/footer1.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
