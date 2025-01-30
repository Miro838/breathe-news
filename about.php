<?php
// Start the session to check login status
session_start();

// Include the database connection
include 'db/connection.php';

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch website info
$websiteInfoQuery = "SELECT title, content FROM Website_Info";
$websiteInfoResult = $conn->query($websiteInfoQuery);

// Check for query error
if (!$websiteInfoResult) {
    die("Error executing query: " . $conn->error);
}

// Fetch team members
$teamQuery = "SELECT name, position, bio FROM Team";
$teamResult = $conn->query($teamQuery);

// Check for query error
if (!$teamResult) {
    die("Error executing query: " . $conn->error);
}

// Fetch milestones
$milestoneQuery = "SELECT milestone, date, details FROM Milestones";
$milestoneResult = $conn->query($milestoneQuery);

// Check for query error
if (!$milestoneResult) {
    die("Error executing query: " . $conn->error);
}

$conn->close();
?>

  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crime Poll</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/a.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
    <style>
        /* General Body Styling */
        /* General Body Styling */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        /* Content Styling */
        .about-us-container {
            width: 90%;
            margin: 100px auto;
            padding: 40px;
            background-color: #ffffff;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            opacity: 0;
            animation: fadeIn 2s forwards;
        }

        /* Animations */
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(50px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideUp {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Section Styling */
        section {
            margin-bottom: 50px;
        }

        h1 {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            animation: slideUp 1s ease-out forwards;
        }

        h2 {
            font-size: 26px;
            color: #444;
            font-weight: 600;
            margin-bottom: 20px;
            animation: slideUp 1s ease-out 0.3s forwards;
        }

        .team-member, .milestone {
            background-color: #f9f9f9;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            opacity: 0;
            animation: slideUp 1s ease-out forwards;
        }

        .team-member h3, .milestone h3 {
            color: #555;
            font-weight: 600;
        }

        .team-member p, .milestone p {
            font-size: 16px;
            color: #777;
        }

        /* Hover effect for team members */
        .team-member:hover {
            background-color: #e7e7e7;
            transform: scale(1.05);
            transition: all 0.3s ease;
        }

        .milestone:hover {
            background-color: #e7e7e7;
            transform: scale(1.05);
            transition: all 0.3s ease;
        }

        /* List Styling */
        ul {
            list-style-type: none;
            padding-left: 0;
        }

        ul li {
            margin-bottom: 20px;
        }
    </style>

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
                <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="category.php">Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="quiz.php">Quizzes</a></li>
                <li class="nav-item"><a class="nav-link" href="polls.php">Polls</a></li>
                <li class="nav-item"><a class="nav-link" href="hiringoffers.php">Hiring Offers</a></li>
                <li class="nav-item"><a class="nav-link btn btn-primary text-white" style="background-color:purple" href="signup.php">Sign Up</a></li>
            </ul>
        </div>
    </div>
</nav>

    <div class="about-us-container">
        <!-- Website Info Section -->
        <section class="website-info">
            <h1>About Us</h1>
            <?php if ($websiteInfoResult->num_rows > 0): ?>
                <?php while ($row = $websiteInfoResult->fetch_assoc()): ?>
                    <h2 style="align:center"><?= htmlspecialchars($row['title']) ?></h2>
                    <p style="color:black"><?= htmlspecialchars($row['content']) ?></p>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No website information available.</p>
            <?php endif; ?>
        </section>

        <!-- Team Section -->
        <section class="team">
            <h2 style="color:black">Meet Our Team</h2>
            <?php if ($teamResult->num_rows > 0): ?>
                <div class="team-members">
                    <?php while ($row = $teamResult->fetch_assoc()): ?>
                        <div class="team-member">
                            <h3 style="color:black"><?= htmlspecialchars($row['name']) ?></h3>
                            <p style="color:black"><strong><?= htmlspecialchars($row['position']) ?></strong></p>
                            <p style="color:black"><?= htmlspecialchars($row['bio']) ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>No team members available.</p>
            <?php endif; ?>
        </section>

        <!-- Milestones Section -->
        <section class="milestones">
            <h2 style="color:black">Our Milestones</h2>
            <?php if ($milestoneResult->num_rows > 0): ?>
                <ul>
                    <?php while ($row = $milestoneResult->fetch_assoc()): ?>
                        <li class="milestone">
                            <h3 style="color:black"><?= htmlspecialchars($row['milestone']) ?></h3>
                            <p style="color:black"><?= htmlspecialchars($row['details']) ?></p>
                            <p style="color:black"><em>Posted on: <?= htmlspecialchars($row['date']) ?></em></p>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No milestones available.</p>
            <?php endif; ?>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        <?php include 'include/footer1.php'; ?>
        <p style="text-align:center">&copy; 2024 BREATHE NEWS. All rights reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
