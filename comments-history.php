<?php
// Assuming you have a database connection established using mysqli
$conn = mysqli_connect("localhost", "root", "", "senior 1");

// Check if connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Start the session to track the logged-in user
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to view your comments.";
    exit;
}

// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Initialize a variable to track the comment being edited
$editing_comment_id = null;

// Handle edit comment form submission
if (isset($_POST['edit_comment'])) {
    $comment_id = $_POST['comment_id'];
    $new_comment_text = $_POST['comment_text'];

    // Update the comment in the database
    $update_query = "UPDATE comments SET comment_text = ? WHERE id = ? AND user_id = ?";
    $stmt_update = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt_update, 'sii', $new_comment_text, $comment_id, $user_id);
    mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);

    // After updating, redirect to the same page to hide the form
    header("Location: " . $_SERVER['PHP_SELF']);
    exit; // Ensure no further code is executed
}

// Handle delete comment request
if (isset($_GET['delete_comment'])) {
    $comment_id = $_GET['delete_comment'];

    // Delete the comment from the database
    $delete_query = "DELETE FROM comments WHERE id = ? AND user_id = ?";
    $stmt_delete = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt_delete, 'ii', $comment_id, $user_id);
    mysqli_stmt_execute($stmt_delete);
    mysqli_stmt_close($stmt_delete);

    echo "Comment deleted successfully!";
}

// SQL query to fetch only the comments of the logged-in user
$query_comments = "
    SELECT id, item_id, user_id, news_id, username, comment_text, comment_date, updated_at, section
    FROM comments
    WHERE user_id = ?
    ORDER BY comment_date DESC
";

// Prepare the SQL statement
$stmt_comments = mysqli_prepare($conn, $query_comments);

// Bind the user_id as a parameter and execute the statement
mysqli_stmt_bind_param($stmt_comments, 'i', $user_id);
mysqli_stmt_execute($stmt_comments);

// Get the result
$result_comments = mysqli_stmt_get_result($stmt_comments);

// Fetch all the comments for the logged-in user
$comments = mysqli_fetch_all($result_comments, MYSQLI_ASSOC);

// Close the prepared statement
mysqli_stmt_close($stmt_comments);

// Close the database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Activity - Breathe News</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css"> <!-- Your custom stylesheet -->
    <link rel="stylesheet" href="style/a.css"> <!-- Your custom stylesheet -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 14px;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .header-main {
            background-color: white;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .header-sub {
            margin-top: 10px;
            background-color: #f8f9fa;
            color: #333;
            padding: 8px 20px;
            display: flex;
            justify-content: center;
            border-bottom: 1px solid #ddd;
        }

        .header-sub a {
            margin: 0 8px;
            text-decoration: none;
            color: gray;
            font-size: 0.8rem;
            font-weight: bold;
            transition: color 0.3s ease-in-out;
        }

        .header-sub a:hover {
            color: purple;
        }

        .comment-history {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            width:1300px;
            padding-left:100px;
            
        }

        .comment {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .comment p {
            margin: 5px 0;
        }

        .comment a {
            color: #007bff;
            text-decoration: none;
        }

        .comment a:hover {
            text-decoration: underline;
        }

        .form-container {
            margin-top: 20px;
            padding: 15px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-container input, .form-container textarea {
            width: 100%;
            margin: 10px 0;
        }

        .form-container button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <!-- First Header -->
    <div class="header-main">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand" href="#">BREATHE NEWS</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a class="nav-link" href="home1.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="category1.php">Categories</a></li>
                        <li class="nav-item"><a class="nav-link" href="quiz1.php">Quizzes</a></li>
                        <li class="nav-item"><a class="nav-link" href="polls1.php">Polls</a></li>
                        <li class="nav-item"><a class="nav-link" href="hiringoffers1.php">Hiring Offers</a></li>
                        <li class="nav-item"><a class="nav-link" href="manageaccount.php">Manage Account</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-primary text-white" style="background-color:purple" href="logout.php">Log Out</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <!-- Second Header -->
    <div class="header-sub">
        <nav class="sub-header">
            <a href="manageaccount.php">Manage Account</a>
            <a href="account-activity.php">Account Activity</a>
            <a href="comments-history.php">Comments History</a>
            <a href="ratings-history.php">Ratings History</a>
            <a href="saves-history.php">Saves History</a>
            <a href="quiz-history.php">Quiz Participation History</a>
            <a href="poll-history.php">Poll Participation History</a>
            <a href="hiring-history.php">Hiring Offers History</a>
            <a href="testimonials.php">Testiomails History</a>
        </nav>
    </div>

    <!-- Comments History Section (Displayed below the subheader) -->
    <div class="comment-history">
        <h1 >Your Comment History</h1>

        <?php
        if ($comments) {
            // Loop through and display each comment for the logged-in user
            foreach ($comments as $comment) {
                echo '<div class="comment">';
                echo '<p style="color:black"><strong>' . htmlspecialchars($comment['username']) . '</strong> (' . htmlspecialchars($comment['section']) . ') <small>' . htmlspecialchars($comment['comment_date']) . '</small></p>';
                echo '<p style="color:black">' . nl2br(htmlspecialchars($comment['comment_text'])) . '</p>';
                echo '<p style="color:black"><a href="?edit_comment=' . $comment['id'] . '">Edit</a> | ';
                echo '<a href="?delete_comment=' . $comment['id'] . '">Delete</a></p>';
                echo '</div>';

                // Display edit form if the user wants to edit a comment
                if (isset($_GET['edit_comment']) && $_GET['edit_comment'] == $comment['id']) {
                    echo '<div class="form-container">';
                    echo '<form method="POST" action="">';
                    echo '<h3>Edit Comment</h3>';
                    echo '<textarea name="comment_text">' . htmlspecialchars($comment['comment_text']) . '</textarea>';
                    echo '<input type="hidden" name="comment_id" value="' . $comment['id'] . '">';
                    echo '<button type="submit" name="edit_comment">Update Comment</button>';
                    echo '</form>';
                    echo '</div>';
                }
            }
        } else {
            echo '<p>No comments found.</p>';
        }
        ?>
    </div>

</body>
</html>
