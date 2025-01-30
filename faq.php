<?php
// Database connection
include 'db/connection.php';

// Check database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch FAQs
$faqQuery = "SELECT question, answer, category FROM FAQs ORDER BY category";
$faqResult = $conn->query($faqQuery);
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/cat.css">
    <link rel="stylesheet" href="style/a.css">
    <link rel="stylesheet" href="style/style.css">
    <style>
        .faq-container {
            width: 80%;
            margin: auto;
            padding-top: 80px;
        }
        .faq-item {
            margin-bottom: 20px;
        }
        .faq-question {
            font-weight: bold;
            cursor: pointer;
        }
        .faq-answer {
            display: none;
            padding-top: 5px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.html">Crime Poll</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="main.php">Main Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="category.php">Categories</a></li>
                    <li class="nav-item"><a class="nav-link" href="quiz.php">Quizzes</a></li>
                    <li class="nav-item"><a class="nav-link" href="polls.php">Polls</a></li>
                    <li class="nav-item"><a class="nav-link" href="hiringoffers.php">Hiring Offers</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-primary text-white" style="color:white" href="signUp.php">Sign Up</a></li>
                </ul>
        </div>
    </div>
</nav>
    <div class="faq-container">
        <h1>Frequently Asked Questions</h1>
        
        <?php if ($faqResult->num_rows > 0): ?>
            <?php while ($row = $faqResult->fetch_assoc()): ?>
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleAnswer(this)"><?= $row['question'] ?></div>
                    <div class="faq-answer"><?= $row['answer'] ?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No FAQs available at the moment.</p>
        <?php endif; ?>
    </div>

    <script>
        // Toggle answer visibility
        function toggleAnswer(questionElement) {
            const answerElement = questionElement.nextElementSibling;
            answerElement.style.display = answerElement.style.display === 'block' ? 'none' : 'block';
        }
    </script>
     <!-- Footer -->
 <footer><?PHP include 'include/footer1.php'; ?>
         <p style="text-align:center">&copy; 2024 BREATHE NEWS. All rights reserved.</p>
    </footer>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
