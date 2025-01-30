
<?php
session_start();
include 'db/connection.php'; // Include your DB connection file

$news_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch article from the database
$news_query = "SELECT * FROM news WHERE id = ?";
$news_stmt = $conn->prepare($news_query);
$news_stmt->bind_param('i', $news_id);
$news_stmt->execute();
$news_result = $news_stmt->get_result();
$news = $news_result->fetch_assoc();

// Check if the news article exists
if (!$news) {
    echo "Article not found.";
    exit();
}

// Fetch author details
$author_query = "SELECT 
    authors.id AS author_id,  
    admin_login.username AS name, 
    authors.email, 
    authors.bio 
FROM 
    authors 
JOIN 
    admin_login 
ON 
    authors.user_id = admin_login.id 
WHERE 
    authors.id = ?;";;
$author_stmt = $conn->prepare($author_query);
$author_stmt->bind_param('i', $news['author_id']);
$author_stmt->execute();
$author_result = $author_stmt->get_result();
$author = $author_result->fetch_assoc();

// Fetch comments
$comments_query = "SELECT * FROM comments WHERE news_id = ? ORDER BY comment_date DESC";
$comments_stmt = mysqli_prepare($conn, $comments_query);
if ($comments_stmt === false) {
    die('Prepare failed: ' . htmlspecialchars(mysqli_error($conn)));
}
mysqli_stmt_bind_param($comments_stmt, 'i', $news_id);
mysqli_stmt_execute($comments_stmt);
$comments_result = mysqli_stmt_get_result($comments_stmt);
$comments = mysqli_fetch_all($comments_result, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_comment'])) {
        $comment_text = mysqli_real_escape_string($conn, $_POST['comment_text']);
        $user_id = intval($_POST['user_id']);

        $add_comment_query = "INSERT INTO comments (news_id, user_id, username, comment_text, comment_date) VALUES (?, ?, ?, ?, NOW())";
        $add_comment_stmt = mysqli_prepare($conn, $add_comment_query);
        if ($add_comment_stmt === false) {
            die('Prepare failed: ' . htmlspecialchars(mysqli_error($conn)));
        }
        mysqli_stmt_bind_param($add_comment_stmt, 'iiss', $news_id, $user_id, $_SESSION['username'], $comment_text);
        mysqli_stmt_execute($add_comment_stmt);

        header("Location: b.php?id=$news_id");
        exit();
    }

    if (isset($_POST['edit_comment'])) {
        $comment_id = intval($_POST['comment_id']);
        $comment_text = mysqli_real_escape_string($conn, $_POST['comment_text']);

        $edit_comment_query = "UPDATE comments SET comment_text = ?, updated_at = NOW() WHERE id = ? AND user_id = ?";
        $edit_comment_stmt = mysqli_prepare($conn, $edit_comment_query);
        if ($edit_comment_stmt === false) {
            die('Prepare failed: ' . htmlspecialchars(mysqli_error($conn)));
        }
        mysqli_stmt_bind_param($edit_comment_stmt, 'sii', $comment_text, $comment_id, $_SESSION['user_id']);
        mysqli_stmt_execute($edit_comment_stmt);

        header("Location: b.php?id=$news_id");
        exit();
    }
    // Handle deleting a comment
if (isset($_GET['delete_comment'])) {
    $comment_id = $_GET['delete_comment'];

    $delete_comment_query = "DELETE FROM comments WHERE id = ? AND user_id = ?";
    $delete_comment_stmt = $conn->prepare($delete_comment_query);
    $delete_comment_stmt->bind_param('ii', $comment_id, $_SESSION['user_id']);
    $delete_comment_stmt->execute();

    header("Location: ?id=$news_id");
    exit();
}
}

// Fetch tags
$tagsQuery = "SELECT tag FROM news_tags WHERE news_id = ?";
$tags_stmt = $conn->prepare($tagsQuery);
$tags_stmt->bind_param("i", $news_id);
$tags_stmt->execute();
$tagsResult = $tags_stmt->get_result();
$tags = [];
while ($row = $tagsResult->fetch_assoc()) {
    $tags[] = $row['tag'];
}

// Fetch rating
$ratingQuery = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE content_id = ? AND content_type = 'Breaking Crime News'";
$rating_stmt = $conn->prepare($ratingQuery);
$rating_stmt->bind_param("i", $news_id);
$rating_stmt->execute();
$ratingResult = $rating_stmt->get_result();
$rating = $ratingResult->fetch_assoc()['avg_rating'] ?? 0;

// Ensure rating is numeric for display
$rating_display = is_numeric($rating) ? round($rating, 1) : 0;

// Fetch related news articles
$related_query = "SELECT id, title, introduction,description, thumbnail,final_thoughts FROM news WHERE category = ? AND id != ? LIMIT 5";
$related_stmt = $conn->prepare($related_query);
$related_stmt->bind_param('si', $news['category'], $news_id);
$related_stmt->execute();
$related_result = $related_stmt->get_result();
$related_news = $related_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($news['title']); ?></title>
    <link rel="stylesheet" href="style/styles.css">
   <link rel="stylesheet" href="style/b.css">
   <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/a.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style/b.css">


</style>
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
    
<div class="news-container" style="margin-top: 70px;">
    <!-- Main Content -->
    <div class="main-content">
        <header class="news-header">
        
            <h1><?php echo htmlspecialchars($news['title']); ?></h1>
        </header>

        <div class="news-meta">
            <span style="margin-right:150px;">by: <?php echo htmlspecialchars($author['name']); ?></span>
            <span style="margin-right:150px;">published on: <?php echo htmlspecialchars($news['date']); ?></span>
            <span style="margin-right:150px;">of category: <?php echo htmlspecialchars($news['category']); ?></span>
        </div>

        <img src="<?php echo htmlspecialchars($news['thumbnail']); ?>" alt="Main Image">

        <div class="tags-rating">
            <div class="tags"><?php echo '<strong>Tags:</strong> ' . htmlspecialchars(implode(', ', $tags)); ?></div>
            <div class="rating">
                <div class="star-rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                      <span class="star<?php echo $i <= $rating_display ? ' selected' : ''; ?>">★</span>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <div class="inline-images">
            <img src="<?php echo htmlspecialchars($news['inline_image_1']); ?>" alt="Inline Image 1">
            <p style="color:black"><?php echo nl2br(htmlspecialchars($news['introduction'])); ?></p>
        </div>

        <div class="divider" style="border-top: 1px solid #eaeaea; margin: 20px 0;"></div>

        <div class="content-layout">
            <div class="news-content">
                <p style="color:black"><?php echo nl2br(htmlspecialchars($news['content'])); ?></p>
            </div>
            <img src="<?php echo htmlspecialchars($news['inline_image_2']); ?>" alt="Inline Image 2" style="width:500px;">
        </div>

        <div class="divider" style="border-top: 1px solid #eaeaea; margin: 20px 0;"></div>
       
        <div class="inline-images">
            <img src="<?php echo htmlspecialchars($news['inline_image_3']); ?>" alt="Inline Image 3">
            <p style="color:black"><?php echo nl2br(htmlspecialchars($news['final_thoughts'])); ?></p>
        </div>
        

        <div class="divider" style="border-top: 1px solid #eaeaea; margin: 20px 0;"></div>
        <!-- Star Rating Section -->
        <div class="rating-section">
            <h3>Rate this article:</h3>
            <div class="star-rating" data-content-id="<?php echo htmlspecialchars($news_id); ?>">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="star" data-value="<?php echo $i; ?>">★</span>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <!-- Sidebar for Related News -->
    <aside class="sidebar">
        <h2>Related News</h2>
        <?php foreach ($related_news as $related): ?>
            <div class="related-article">
                <img src="<?php echo htmlspecialchars($related['thumbnail']); ?>" alt="<?php echo htmlspecialchars($related['title']); ?>">
                <div>
                    <h4><a href="b.php?id=<?php echo htmlspecialchars($related['id']); ?>"><?php echo htmlspecialchars($related['title']); ?></a></h4>
                    <p style="color:black"><?php echo nl2br(htmlspecialchars($related['description'])); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </aside>
    
    <!-- Comments Section -->
    <div class="comments-section">
        <h2>Comments</h2>
        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="post" action="b.php?id=<?php echo htmlspecialchars($news_id); ?>">
                <textarea name="comment_text" placeholder="Add a comment..." required></textarea>
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <button type="submit" name="add_comment">Post Comment</button>
            </form>
        <?php endif; ?>

        <?php foreach ($comments as $comment): ?>
            <div class="comment">
                <p class="meta" style="color:black">
                    <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                    on <?php echo htmlspecialchars($comment['comment_date']); ?>
                    <?php if (!empty($comment['updated_at'])): ?>
                        (edited on <?php echo htmlspecialchars($comment['updated_at']); ?>)
                    <?php endif; ?>
                </p>
                <p style="color:black"><?php echo htmlspecialchars($comment['comment_text']); ?></p>
                <?php if ($comment['user_id'] == $_SESSION['user_id']): ?>
                    <div class="actions">
                        <button onclick="toggleCommentOptions('<?php echo htmlspecialchars($comment['id']); ?>')">⋮</button>
                    </div>
                    <div id="comment-options-<?php echo htmlspecialchars($comment['id']); ?>" class="comment-options">
                            <button onclick="toggleCommentForm('<?php echo htmlspecialchars($comment['id']); ?>')">Update</button>
                            <a href="b.php?id=<?php echo htmlspecialchars($news_id); ?>&delete_comment=<?php echo htmlspecialchars($comment['id']); ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </div>

                    <div id="edit-form-<?php echo htmlspecialchars($comment['id']); ?>" style="display: none;">
                        <form action="b.php?id=<?php echo htmlspecialchars($news_id); ?>" method="post">
                            <textarea name="comment_text"><?php echo htmlspecialchars($comment['comment_text']); ?></textarea>
                            <input type="hidden" name="comment_id" value="<?php echo htmlspecialchars($comment['id']); ?>">
                            <button type="submit" name="edit_comment">Update Comment</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

       <br> <a href="home1.php" ><button type="button" value="back" style="background-color:purple;color:white">BACK</button></a>

        <!-- Share Modal -->
        <button class="share-button" onclick="shareContent()">Share</button>

<!-- Script for Star Rating -->
<script>
   // JavaScript for handling star rating
   document.addEventListener('DOMContentLoaded', () => {
    const stars = document.querySelectorAll('.rating-section .star-rating .star');
    stars.forEach(star => {
        star.addEventListener('click', () => {
            const contentId = star.parentElement.dataset.contentId;
            const ratingValue = star.dataset.value;

            fetch('rate.php', {
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
                    // Update display to reflect new rating
                    stars.forEach((s, index) => {
                        s.classList.toggle('selected', index < ratingValue);
                    });
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
            title: '<?php echo htmlspecialchars($news['title']); ?>',
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