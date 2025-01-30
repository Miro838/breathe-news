<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Poll</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="style/a.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
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

        /* Poll Card Styling */
        .poll-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            background-color: #d3c1ff;
            transition: transform 0.3s;
            display: none; /* Hide all polls by default */
        }

        .poll-card.active {
            display: block; /* Show the active poll */
        }

        /* Navigation Buttons */
        .nav-buttons .btn-primary {
            background-color: #4b0082;
            color: #ffffff;
            border: 2px solid #ffffff;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .nav-buttons .btn-primary:hover {
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
            <li class="nav-item"><a class="nav-link" href="main.php">Main Home</a></li>
                <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="category.php">Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="quiz.php">Quizzes</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Polls</a></li>
                <li class="nav-item"><a class="nav-link" href="hiringoffers.php">Hiring Offers</a></li>
                <li class="nav-item"><a class="nav-link btn btn-primary text-white" style="background-color:purple" href="signup.php">SIgn Up</a></li>
                
            </ul>
        </div>
    </div>
</nav>

<div id="homePage" class="container mt-5 pt-5">
    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="input-group">
                <form action="poll_search.php" method="post" class="w-100 d-flex">
                    <input type="text" id="searchInput" name="query" class="form-control" placeholder="Search polls by question..." aria-label="Search polls">
                    <div class="input-group-append">
                        <button id="searchButton" class="btn btn-outline-secondary" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <h2 class="text-center" style="color: black; background-color:#d3c1ff; padding-top:10px; margin-bottom:20px">Polls</h2>
    
    <!-- Poll Cards -->
    <div class="polls">
        <?php
        include 'db/connection.php';

        // Fetch polls from the database
        $sql = "SELECT id, question FROM polls WHERE status = 'active'";
        $result = mysqli_query($conn, $sql);
        $polls = [];

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $pollId = $row['id'];

                // Fetch options for this poll
                $optionsSql = "SELECT id, option_text, votes FROM poll_options WHERE poll_id = $pollId";
                $optionsResult = mysqli_query($conn, $optionsSql);
                
                $options = [];
                while ($option = mysqli_fetch_assoc($optionsResult)) {
                    $options[] = $option;
                }

                $polls[] = ['question' => $row['question'], 'options' => $options, 'id' => $pollId];
            }
        } else {
            echo '<p>No active polls found.</p>';
        }
        ?>

        <?php foreach ($polls as $index => $poll): ?>
            <div class="poll-card <?= $index === 0 ? 'active' : '' ?>" id="poll-<?= $poll['id'] ?>">
                <h3><?= htmlspecialchars($poll['question']) ?></h3>
                
                <!-- Display poll options -->
                <ul>
                    <?php foreach ($poll['options'] as $option): ?>
                        <li><?= htmlspecialchars($option['option_text']) ?></li>
                    <?php endforeach; ?>
                </ul>

                <!-- Message to sign up -->
                <div>
                    <p>You must sign up to vote.</p>
                    <a href="signup.php" class="btn btn-primary mt-3">Sign Up to Vote</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Navigation Buttons -->
     <!-- Navigation Buttons -->
<div class="nav-buttons mt-3 d-flex justify-content-between">
    <button onclick="prevPoll()" class="btn btn-primary"><</button>
    <button onclick="nextPoll()" class="btn btn-primary">></button>
</div>


<script>
    let currentPollIndex = 0;
const polls = document.querySelectorAll('.poll-card');

// Function to show the current poll
function showPoll(index) {
    polls.forEach((poll, i) => {
        poll.classList.toggle('active', i === index);
    });
}

// Function to go to the next poll
function nextPoll() {
    if (currentPollIndex < polls.length - 1) {
        currentPollIndex++;
        showPoll(currentPollIndex);
    } else {
        // Optionally, loop back to the first poll if at the end
        currentPollIndex = 0;
        showPoll(currentPollIndex);
    }
}

// Function to go to the previous poll
function prevPoll() {
    if (currentPollIndex > 0) {
        currentPollIndex--;
        showPoll(currentPollIndex);
    } else {
        // Optionally, loop back to the last poll if at the start
        currentPollIndex = polls.length - 1;
        showPoll(currentPollIndex);
    }
}

// Function to automatically change polls every 11 seconds
function autoChangePoll() {
    setInterval(() => {
        nextPoll(); // Change to the next poll automatically
    }, 6000); // Change every 11 seconds
}

// Initialize
showPoll(currentPollIndex);
autoChangePoll();

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
