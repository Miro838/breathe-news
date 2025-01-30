<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Poll</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/quiz.css">
    <style>
        /* Search Bar Styling */
        #searchInput {
            border-radius: 20px;
            padding: 10px;
            border: 2px solid #4b0082;
            background-color: #ffffff;
            color: black;
        }

        #searchButton {
            border-radius: 20px;
            background-color: #4b0082;
            color: #ffffff;
            border: 2px solid #ffffff;
        }

        #searchButton:hover {
            background-color: #ffffff;
            color: #4b0082;
            border: 2px solid #4b0082;
        }

        /* Quiz Card Styling */
        .quiz-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            margin: 0 10px; /* Space between cards */
            transition: transform 0.3s;
            background-color: #fff;
        }

        .quiz-card:hover {
            transform: scale(1.05);
        }

        .quiz-card-front,
        .quiz-card-back {
            padding: 20px;
        }

        .quiz-card-back {
            display: none;
        }

        .quiz-card:hover .quiz-card-back {
            display: block;
        }

        .quiz-card-back {
            background-color: #f8f9fa;
            border-top: 1px solid #ddd;
        }

        .lock-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .quiz-card:hover .lock-overlay {
            opacity: 1;
        }

        /* How It Works Section Styling */
        .how-it-works {
            padding: 40px 0;
            background-color: #f8f9fa;
            text-align: left; /* Align text to the left */
        }

        .how-it-works h2 {
            margin-bottom: 20px;
            color: black; /* Dark purple color for headings */
        }

        .how-it-works ol {
            margin-bottom: 20px;
            display: none; /* Hide instructions by default */
        }

        .how-it-works.active ol {
            display: block; /* Show instructions when section is active */
        }

        .how-it-works button {
            background-color: #4b0082;
            color: #ffffff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .how-it-works button:hover {
            background-color: #3a006f; /* Darker purple on hover */
        }
        .nav-buttons .btn {
    background-color: #4b0082;
    color: #ffffff;
    border: 2px solid #ffffff;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s;
}

.nav-buttons .btn:hover {
    background-color: #ffffff;
    color: #4b0082;
    border: 2px solid #4b0082;
    transform: scale(1.05);
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
                <li class="nav-item"><a class="nav-link" href="category1.php">Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="laws.php">LAWS</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Quizzes</a></li>
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
            <div class="input-group">
                <form action="quiz_search.php" method="post" class="w-100 d-flex">
                    <input type="text" id="searchInput" name="query" class="form-control" placeholder="Search quizzes by its title..." aria-label="Search articles">
                    <div class="input-group-append">
                        <button id="searchButton" class="btn btn-outline-secondary" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <h2 class="text-center bg-lightgray py-3 mb-5" style="color: black;background-color:#d3c1ff;text-align:center;padding-top:10px;margin-bottom:20px">Quizzes</h2>

    <!-- Quiz Cards -->
    <div id="quizCards" class="d-flex flex-column align-items-center" style="background-color:#d3c1ff;">
        <?php
        include 'db/connection.php';

        // Fetch quizzes from the database
        $sql = "SELECT id, title, description, difficulty FROM quizzes";
        $result = mysqli_query($conn, $sql);
        $quizzes = [];

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $quizzes[] = $row; // Store quizzes in an array
            }
        } else {
            echo '<p>No quizzes found.</p>';
        }

        // Display quiz cards
        foreach ($quizzes as $index => $quiz) {
            $quizId = $quiz['id'];

            // Fetch number of questions for this quiz
            $questionCountSql = "SELECT COUNT(*) as question_count FROM questions WHERE quiz_id = $quizId";
            $questionCountResult = mysqli_query($conn, $questionCountSql);
            $questionCountRow = mysqli_fetch_assoc($questionCountResult);
            $questionCount = $questionCountRow['question_count'];

            echo '<div class="quiz-card my-3" style="width: 300px;">';
            echo '    <div class="quiz-card-front">';
            echo '        <h3>' . htmlspecialchars($quiz['title']) . '</h3>';
            echo '        <p  style="color:black">' . htmlspecialchars($quiz['description']) . '</p>';
            echo '    </div>';
            echo '    <div class="quiz-card-back">';
            echo '        <p  style="color:black">Number of Questions: ' . htmlspecialchars($questionCount) . '</p>';
            echo '        <p  style="color:black">Difficulty: ' . htmlspecialchars($quiz['difficulty']) . '</p>';
            echo '        <a href="startQuiz.php?quiz_id=' . $quizId . '" class="btn btn-primary">Start Quiz</a>';
            echo '    </div>';
            echo '    <div class="lock-overlay">'; // Lock overlay for inactive quizzes
            echo '        <span>Locked</span>';
            echo '    </div>';
            echo '</div>';
        }
        ?>
    </div>

    <!-- Navigation Buttons -->
<div class="nav-buttons mt-3 d-flex justify-content-between">
    <button id="prevButton" class="btn btn-primary" onclick="prevQuiz()"><</button>
    <button id="nextButton" class="btn btn-primary" onclick="nextQuiz()">></button>
</div>


    <!-- How It Works Section -->
    <div class="how-it-works">
        <h2 style="color:black">How It Works</h2>
        <button onclick="toggleInstructions()" style="background-color:#4b0082">Click to See Instructions</button>
        <ol style="background-color:#d3c1ff;">
            <li>Choose a quiz from the options.</li>
            <li>Click on <a href="startQuiz.php">Start Quiz</a> to begin.</li>
            <li>Answer all questions and submit your answers.</li>
        </ol>
    </div>
</div>

<script>
    function toggleInstructions() {
        const howItWorks = document.querySelector('.how-it-works');
        howItWorks.classList.toggle('active');
    }
    let currentIndex = 0;

function showQuiz(index) {
    const cards = document.querySelectorAll('.quiz-card');
    cards.forEach((card, i) => {
        card.style.display = (i === index) ? 'block' : 'none';
    });
}

function nextQuiz() {
    const cards = document.querySelectorAll('.quiz-card');
    currentIndex = (currentIndex + 1) % cards.length; // Loop back to the first quiz
    showQuiz(currentIndex);
}

function prevQuiz() {
    const cards = document.querySelectorAll('.quiz-card');
    currentIndex = (currentIndex - 1 + cards.length) % cards.length; // Loop to the last quiz if at the beginning
    showQuiz(currentIndex);
}

// Function to automatically change quiz every 5 seconds
function autoChangeQuiz() {
    setInterval(() => {
        nextQuiz(); // Automatically change to the next quiz
    }, 6000); // Change every 5 seconds
}

// Initialize
showQuiz(currentIndex);
autoChangeQuiz(); // Start automatic quiz change

</script>

<!-- Footer -->
<div class="quiz-footer">
    <p><?php include 'include/footer1.php'; ?></p>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
