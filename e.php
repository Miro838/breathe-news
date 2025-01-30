<?php
session_start();
include 'db/connection.php'; // Include your DB connection file

// Get the show ID from the query string or default to 0
$show_id = isset($_GET['show_id']) ? intval($_GET['show_id']) : 0;

// Fetch the details for the specific show
$showQuery = "SELECT * FROM top_shows WHERE id = ?";
$showStmt = $conn->prepare($showQuery);
$showStmt->bind_param('i', $show_id);
$showStmt->execute();
$showResult = $showStmt->get_result();
$article = $showResult->fetch_assoc();

if (!$article) {
    die("Show not found.");
}

// Fetch comments related to this show
$section = 'top_shows'; // Define the section for comments
$comments_query = "SELECT * FROM comments WHERE item_id = ? AND section = ? ORDER BY comment_date DESC";
$comments_stmt = $conn->prepare($comments_query);
$comments_stmt->bind_param('is', $show_id, $section);
$comments_stmt->execute();
$comments_result = $comments_stmt->get_result();
$comments = $comments_result->fetch_all(MYSQLI_ASSOC);

// Handle adding a new comment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_comment'])) {
    $comment_text = $_POST['comment_text'];
    $user_id = $_POST['user_id'];
    $add_comment_query = "INSERT INTO comments (item_id, user_id, username, comment_text, comment_date, section) VALUES (?, ?, ?, ?, NOW(), ?)";
    $add_comment_stmt = $conn->prepare($add_comment_query);
    $add_comment_stmt->bind_param('iisss', $show_id, $user_id, $_SESSION['username'], $comment_text, $section);
    $add_comment_stmt->execute();

    // Redirect to refresh the page
    header("Location: ?show_id=$show_id");
    exit();
}

// Handle editing an existing comment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_comment'])) {
    $comment_id = $_POST['comment_id'];
    $comment_text = $_POST['comment_text'];
    $edit_comment_query = "UPDATE comments SET comment_text = ?, updated_at = NOW() WHERE id = ? AND user_id = ?";
    $edit_comment_stmt = $conn->prepare($edit_comment_query);
    $edit_comment_stmt->bind_param('sii', $comment_text, $comment_id, $_SESSION['user_id']);
    $edit_comment_stmt->execute();

    // Redirect to refresh the page
    header("Location: ?show_id=$show_id");
    exit();
}

// Handle deleting a comment
if (isset($_GET['delete_comment'])) {
    $comment_id = $_GET['delete_comment'];
    $delete_comment_query = "DELETE FROM comments WHERE id = ? AND user_id = ?";
    $delete_comment_stmt = $conn->prepare($delete_comment_query);
    $delete_comment_stmt->bind_param('ii', $comment_id, $_SESSION['user_id']);
    $delete_comment_stmt->execute();

    // Redirect to refresh the page
    header("Location: ?show_id=$show_id");
    exit();
}



// Fetch the average rating for this show
// Fetch rating
$content_type = 'top_shows';
$ratingQuery = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE content_id = ? AND content_type = ?";
$rating_stmt = $conn->prepare($ratingQuery);
$rating_stmt->bind_param("is", $article_id, $content_type); // Bind both variables correctly
$rating_stmt->execute();
$ratingResult = $rating_stmt->get_result();
$rating = $ratingResult->fetch_assoc()['avg_rating'] ?? 0;


// Ensure rating is numeric for display
$rating_display = is_numeric($rating) ? round($rating, 1) : 0;

// Fetch related shows from the same category
$related_query = "SELECT id, title, description, thumbnail FROM top_shows WHERE category_id = ? AND id != ? LIMIT 5";
$related_stmt = $conn->prepare($related_query);
$related_stmt->bind_param('ii', $article['category_id'], $show_id);
$related_stmt->execute();
$related_result = $related_stmt->get_result();
$related_shows = $related_result->fetch_all(MYSQLI_ASSOC);

// Check if the show is saved by the user
$user_id = $_SESSION['user_id'];
$is_saved = false;
$check_saved_query = "SELECT * FROM saved_shows WHERE user_id = ? AND show_id = ?";
$check_saved_stmt = $conn->prepare($check_saved_query);
$check_saved_stmt->bind_param('ii', $user_id, $show_id);
$check_saved_stmt->execute();
$saved_result = $check_saved_stmt->get_result();
if ($saved_result->num_rows > 0) {
    $is_saved = true;
}

// Handle saving the show
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_shows'])) {
    $save_show_query = "INSERT INTO saved_shows (user_id, show_id) VALUES (?, ?)";
    $save_show_stmt = $conn->prepare($save_show_query);
    $save_show_stmt->bind_param('ii', $user_id, $show_id);
    $save_show_stmt->execute();

    // Redirect to refresh the page
    header("Location: ?show_id=$show_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?></title>
    <link rel="stylesheet" href="style/styles.css">
    <link rel="stylesheet" href="style/b.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/a.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style/b.css">

   

</head>
<body>
    <!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="home1.php">BREATHE NEWS</a>
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

<div class="news-container" style="margin-top:70px">
    <!-- Main Content -->
    <div class="main-content">
        <header class="news-header">
      
            <h1><?php echo htmlspecialchars($article['title']); ?></h1>
        </header>

        <div class="news-meta" >
            <span>published on: <?php echo htmlspecialchars($article['publish_date']); ?></span>
            <span style="margin-right:350px;">category: <?php echo htmlspecialchars($article['category_name']); ?></span>
        </div>

        <img src="uploads/thumbnails/<?php echo htmlspecialchars($article['thumbnail']); ?>" alt="Main Image">
        <div class="tags-rating">

            <div class="rating" id="user-rating">
                <div class="star-rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star<?php echo $i <= $rating_display ? ' selected' : ''; ?>">★</span>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
        <div class="divider"></div>
        <div class="inline-images">
            <video width="640" height="360" controls>
                <source src="uploads/videos/<?php echo htmlspecialchars($article['video']); ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
             
        </div>
       <p style="color:black"><?php echo htmlspecialchars($article['description']); ?></p>
       <p style="color:black"><strong>Why is it popular?</strong></p>
    <p style="color:black"><?php echo htmlspecialchars($article['why_is_popular']); ?></p>
    <p style="color:black"><strong>Why do viewers love it?</strong></p>
    <p style="color:black"><?php echo htmlspecialchars($article['why_viewers_love']); ?></p>
        <div class="divider"></div>

        <!-- Star Rating Section -->
        <div class="rating-section">
            <h3>Rate this article:</h3>
            <div class="star-rating" data-content-id="<?php echo htmlspecialchars($article_id); ?>">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="star" data-value="<?php echo $i; ?>">★</span>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <!-- Sidebar for Related Shows -->
    <aside class="sidebar">
        <h2>Related Shows</h2>
        <?php foreach ($related_shows as $related): ?>
            <div class="related-article">
                <img src="uploads/thumbnails/<?php echo htmlspecialchars($related['thumbnail']); ?>" alt="<?php echo htmlspecialchars($related['title']); ?>">
                <div>
                    <h4><a href="e.php?show_id=<?php echo htmlspecialchars($related['id']); ?>"><?php echo htmlspecialchars($related['title']); ?></a></h4>
                    <p style="color:black"><?php echo nl2br(htmlspecialchars($related['description'])); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </aside>

    <!-- Comments Section -->
    <div class="comments-section">
    <h2>Comments</h2>
    <form action="e.php?show_id=<?php echo htmlspecialchars($show_id); ?>" method="post">
        <textarea name="comment_text" required></textarea>
        <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($show_id); ?>">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
        <button type="submit" name="add_comment">Add Comment</button>
    </form>

    <?php foreach ($comments as $comment): ?>
        <div class="comment">
            <p class="meta" style="color:black">
                <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                on <?php echo htmlspecialchars($comment['comment_date']); ?>
                <?php if (!empty($comment['updated_at'])): ?>
                    (edited on <?php echo htmlspecialchars($comment['updated_at']); ?>)
                <?php endif; ?>
            </p>
            <p style="color:black"><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
            <?php if ($comment['user_id'] == $_SESSION['user_id']): ?>
                <div class="actions">
                    <button onclick="toggleCommentOptions('<?php echo htmlspecialchars($comment['id']); ?>')">⋮</button>
                </div>
                <div id="comment-options-<?php echo htmlspecialchars($comment['id']); ?>" class="comment-options">
                    <button onclick="toggleCommentForm('<?php echo htmlspecialchars($comment['id']); ?>')">Update</button>
                    <a href="e.php?show_id=<?php echo htmlspecialchars($show_id); ?>&delete_comment=<?php echo htmlspecialchars($comment['id']); ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </div>

                <div id="edit-form-<?php echo htmlspecialchars($comment['id']); ?>" style="display: none;">
                    <form action="e.php?show_id=<?php echo htmlspecialchars($show_id); ?>" method="post">
                        <textarea name="comment_text"><?php echo htmlspecialchars($comment['comment_text']); ?></textarea>
                        <input type="hidden" name="comment_id" value="<?php echo htmlspecialchars($comment['id']); ?>">
                        <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($show_id); ?>">
                        <button type="submit" name="edit_comment">Update Comment</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>


    <!-- Save Show Section -->
    <div class="save-show" style="margin-top: 20px;">
    <form action="e.php?show_id=<?php echo htmlspecialchars($show_id); ?>" method="post">
        <button type="submit" name="save_shows" class="save-button">
            <?php echo $is_saved ? 'Saved' : 'Save Show'; ?>
        </button>
    </form>
</div>
<br> <a href="home1.php" ><button type="button" value="back" style="background-color:purple;color:white">BACK</button></a>
<!-- Share Modal -->
<button class="share-button" onclick="shareContent()">Share</button>

<script>
// Assuming the star rating element is set up correctly
document.addEventListener('DOMContentLoaded', () => {
    const stars = document.querySelectorAll('.rating-section .star-rating .star');
    stars.forEach(star => {
        star.addEventListener('click', () => {
            const contentId = star.parentElement.dataset.contentId;
            const ratingValue = star.dataset.value;

            fetch('get_rating.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    content_id: contentId,
                    rating: ratingValue,
                    user_id: <?php echo json_encode($_SESSION['user_id'] ?? null); ?>
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Thank you for your rating!');
                    // Update star display for current user
                    stars.forEach((s, index) => {
                        s.classList.toggle('selected', index < ratingValue);
                    });
                    // Update displayed average rating below the thumbnail
                    const avgRatingDisplay = document.getElementById('average-rating');
                    if (avgRatingDisplay) {
                        avgRatingDisplay.textContent = `Average Rating: ${data.new_avg_rating}`;
                    }
                } else {
                    alert(data.message || 'An error occurred. Please try again later.');
                }
            })
            .catch(error => console.log('Error:', error));
        });
    });
});

    function toggleCommentOptions(commentId) {
            var options = document.getElementById('comment-options-' + commentId);
            options.style.display = options.style.display === 'none' ? 'block' : 'none';
        }

        function toggleCommentForm(commentId) {
            var form = document.getElementById('edit-form-' + commentId);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        
    function shareContent() {
        const shareData = {
            title: '<?php echo htmlspecialchars($article['title']); ?>',
            text: 'Check out this article!',
            url: window.location.href
        };

        if (navigator.share) {
            navigator.share(shareData)
                .then(() => console.log('Thanks for sharing!'))
                .catch((error) => console.log('Something went wrong.', error));
        } else {
            alert('Web Share API is not supported in your browser.');
        }
    }
</script>
<footer><?PHP include 'include/footer1.php'; ?>
         <p style="text-align:center">&copy; 2024 BREATHE NEWS. All rights reserved.</p>
    </footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

