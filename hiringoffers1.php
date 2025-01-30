<?php
include 'db/connection.php';
session_start(); // Start the session to check login status

// Fetch job offers from the database
$sql = "SELECT id, title, summary, location, salary FROM job_offers WHERE status='open'";
$result = mysqli_query($conn, $sql);

// Store job offers in an array
$jobOffers = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $jobOffers[] = $row;
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hiring Offers</title>
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

        /* Job Card Styling */
        .job-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            background-color: #d3c1ff;
            transition: transform 0.3s;
            margin-bottom: 20px;
            width: 100%;
            display: none; /* Initially hide all cards */
        }

        .job-card h5 {
            font-weight: bold;
            color: #4b0082;
        }

        .job-card h6 {
            font-size: 16px;
            color: #333333;
        }

        .job-card p {
            color: #666666;
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
            transform: scale(1.0);
        }

        /* Ensure the job cards container remains centered */
        .job-cards-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Centering the title */
        h2 {
            color: black;
            background-color: #d3c1ff;
            padding-top: 10px;
            margin-bottom: 20px;
            text-align: center;
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
                <li class="nav-item"><a class="nav-link" href="quiz1.php">Quizzes</a></li>
                <li class="nav-item"><a class="nav-link" href="polls1.php">Polls</a></li>
                <li class="nav-item"><a class="nav-link active" href="#">Hiring Offers</a></li>
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
                <form action="search.php" method="post" class="w-100 d-flex">
                    <input type="text" id="searchInput" name="query" class="form-control" placeholder="Search job offers..." aria-label="Search job offers">
                    <div class="input-group-append">
                        <button id="searchButton" class="btn btn-outline-secondary" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Title -->
    <h2 style="height:65px">Job Opportunities</h2>

    <!-- Job Cards Container -->
    <div class="job-cards-container">
        <?php
        if (!empty($jobOffers)) {
            foreach ($jobOffers as $offer) {
                echo '<div class="job-card">';
                echo '<h5>' . htmlspecialchars($offer['title']) . '</h5>';
                echo '<h6>Location: ' . htmlspecialchars($offer['location']) . '</h6>';
                echo '<p>Salary: ' . htmlspecialchars($offer['salary']) . '</p>';
                echo '<p>' . htmlspecialchars($offer['summary']) . '</p>';
                echo '<a href="job_details.php?id=' . $offer['id'] . '" class="btn btn-primary">View Details & Apply</a>';
                echo '</div>'; // Close job card
            }
        } else {
            echo '<p style="text-align:center; color:black; font-size:25px">No hiring offers available at the moment.</p>';
        }
        ?>
    </div>

    <!-- Navigation Buttons -->
    <div class="nav-buttons mt-3 d-flex justify-content-between">
        <button onclick="prevOffer()" class="btn btn-primary" style="color:white"><</button>
        <button onclick="nextOffer()" class="btn btn-primary">></button>
    </div>

    <!-- Footer -->
    <div class="quiz-footer">
        <p><?php include 'include/footer1.php'; ?></p>
    </div>
</div>

<script>
    let currentOfferIndex = 0;
const offers = document.querySelectorAll('.job-card');

// Function to show the current job offer
function showOffer(index) {
    // Hide all job offers
    offers.forEach((offer) => {
        offer.style.display = 'none';
    });

    // Show the current job offer
    if (offers[index]) {
        offers[index].style.display = 'block';
    }
}

// Function to go to the next job offer
function nextOffer() {
    if (currentOfferIndex < offers.length - 1) {
        currentOfferIndex++;
        showOffer(currentOfferIndex);
    } else {
        // Optionally, loop back to the first offer if at the end
        currentOfferIndex = 0;
        showOffer(currentOfferIndex);
    }
}

// Function to go to the previous job offer
function prevOffer() {
    if (currentOfferIndex > 0) {
        currentOfferIndex--;
        showOffer(currentOfferIndex);
    } else {
        // Optionally, loop back to the last offer if at the start
        currentOfferIndex = offers.length - 1;
        showOffer(currentOfferIndex);
    }
}

// Function to automatically change job offers every 11 seconds
function autoChangeOffer() {
    setInterval(() => {
        nextOffer(); // Correct function for changing offers
    }, 6000); // Change every 11 seconds
}

// Initialize
showOffer(currentOfferIndex);
autoChangeOffer();

   </script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
