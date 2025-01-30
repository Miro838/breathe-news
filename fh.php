<?php 
session_start(); // Start the session to check login status
include 'db/connection.php'; 

// Check database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch categories for display
$categoriesQuery = mysqli_query($conn, "SELECT id, category_name, description, thumbnail, video FROM category");

if (!$categoriesQuery) {
    die("Query failed: " . mysqli_error($conn));
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
    <style>
        .category-card {
            max-width: 1100px; /* Set a max-width to reduce overall size */
            padding: 1rem; /* Reduce padding for a more compact layout */
            margin: 0 auto; /* Center the cards */
            background-color: #f9f9f9; /* Optional: adjust background color */
        }
        .category-card img {
            max-height: 200px; /* Adjust image size */
            object-fit: cover; /* Ensure images are cropped to fit the size */
        }
        .category-card-body p {
            font-size: 0.9rem; /* Decrease font size for the description */
        }
        .category-card-body h5 {
            font-size: 1.1rem; /* Adjust font size for the title */
        }
        .job-card {
            display: none;
        }
    </style>
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
                <li class="nav-item"><a class="nav-link" href="polls1.php">Polls</a></li>
                <li class="nav-item"><a class="nav-link" href="hiringoffers1.php">Hiring Offers</a></li>
                <li class="nav-item"><a class="nav-link" href="manageaccount.php">Manage Account</a></li>
                <li class="nav-item"><a class="nav-link btn btn-primary text-white" style="background-color:purple" href="logout.php">Log Out</a></li>
            </ul>
        </div>
    </div>
</nav>

<div id="homePage" class="container mt-5 pt-5">
    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-md-12">
            <form action="search.php" method="post" class="w-100 d-flex">
                <input type="text" id="searchInput" name="query" class="form-control" placeholder="Search articles by category, article name, or crime..." aria-label="Search articles">
                <div class="input-group-append">
                    <button id="searchButton" class="btn btn-outline-secondary" type="submit">Search</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Page Content -->
    <div class="categoryCarousel">
        <h2 class="text-center bg-lightgray py-3 mb-5" style="color: black; background-color:#d3c1ff; padding-top:10px; margin-bottom:20px">Crime Categories</h2>
        <div class="row">
            <?php while ($category = mysqli_fetch_assoc($categoriesQuery)): ?>
                <div class="col-md-4 mb-4 job-card" style="background-color:#d3c1ff;height:500px;" >
                    <div class="card category-card">
                        <div id="carousel<?php echo $category['id']; ?>" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                <?php
                                // Fetch articles for the current category
                                $categoryName = $category['category_name'];
                                $articlesQuery = mysqli_prepare($conn, "SELECT * FROM articles WHERE category_name = ?");
                                mysqli_stmt_bind_param($articlesQuery, 's', $categoryName);
                                mysqli_stmt_execute($articlesQuery);
                                $articles = mysqli_stmt_get_result($articlesQuery);

                                // Check if there are any articles
                                if (mysqli_num_rows($articles) == 0) {
                                    echo '<div class="carousel-item active"><p>No articles available for this category.</p></div>';
                                } else {
                                    $index = 0;
                                    while ($article = mysqli_fetch_assoc($articles)): ?>
                                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                            <img src="uploads/images/<?php echo htmlspecialchars($article['thumbnail']); ?>" class="d-block w-100" alt="Article Image">
                                            <div class="carousel-caption d-none d-md-block">
                                                <h5><?php echo htmlspecialchars($article['title']); ?></h5>
                                                <a href="article.php?id=<?php echo $article['id']; ?>" class="btn btn-primary">Read More</a>
                                            </div>
                                        </div>
                                    <?php $index++; endwhile; ?>
                                <?php } ?>
                            </div>
                            <a class="carousel-control-prev" href="#carousel<?php echo $category['id']; ?>" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carousel<?php echo $category['id']; ?>" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                        
                        <div class="card-body text-center category-card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($category['category_name']); ?></h5>
                            <p  style="color:black"><?php echo htmlspecialchars($category['description']); ?></p>
                            <a href="f.php" class="btn btn-primary mt-3">View</a>
                        </div>
                    </div>
                </div>
                
            <?php endwhile; ?>
            
        </div>
        
    </div>


    <!-- Navigation Buttons (Client-Side Pagination) -->
    <div class="nav-buttons mt-3 d-flex justify-content-between">
        <button onclick="prevCategory()" class="btn btn-primary"><</button>
        <button onclick="nextCategory()" class="btn btn-primary">></button>
    </div>
</div>

<!-- Footer -->
<div class="quiz-footer">
    <p><?php include 'include/footer1.php'; ?></p>
</div>

<scrip>
<script>
// JavaScript code for category pagination with automatic page change every 3 seconds
let currentCategoryIndex = 0; // Keeps track of the current index of visible categories
const categories = Array.from(document.querySelectorAll('.job-card')); // Convert NodeList to Array
const categoriesPerPage = 3; // Items to display per page
const totalCategories = categories.length;

// Function to show the current page of categories
function showCategories() {
    // Calculate the range of categories to show based on currentCategoryIndex and categoriesPerPage
    const start = currentCategoryIndex * categoriesPerPage;
    const end = start + categoriesPerPage;

    // Hide all categories initially
    categories.forEach((category) => {
        category.style.display = 'none';
    });

    // Show the categories in the current page range
    categories.slice(start, end).forEach((category) => {
        category.style.display = 'block';
    });
}

// Function to go to the next page (next set of categories)
function nextCategory() {
    const nextIndex = (currentCategoryIndex + 1) * categoriesPerPage;

    // If there's more content, show next categories
    if (nextIndex < totalCategories) {
        currentCategoryIndex++;
    } else {
        // Loop back to the first set of categories if at the end
        currentCategoryIndex = 0;
    }
    showCategories(); // Show the updated categories
}

// Function to go to the previous page (previous set of categories)
function prevCategory() {
    // Ensure the currentCategoryIndex doesn't go below 0
    if (currentCategoryIndex > 0) {
        currentCategoryIndex--;
    } else {
        // Loop back to the last set of categories if at the start
        currentCategoryIndex = Math.floor(totalCategories / categoriesPerPage) - 1;
    }
    showCategories(); // Show the updated categories
}

// Function to automatically change to the next category every 3 seconds
function autoChangeCategory() {
    setInterval(() => {
        nextCategory(); // Automatically go to next category
    }, 9000); // 3000 milliseconds = 3 seconds
}

// Initial display of categories
showCategories();

// Start auto-changing the category every 3 seconds
autoChangeCategory();



</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
