<?php
// Include database connection
include 'db/connection.php';

// Start session
session_start();
$user_id = $_SESSION['user_id'] ?? null; // Assuming user is logged in
if (!$user_id) {
    die("User not logged in.");
}

// Fetch logged-in user's vote history with all options and vote date
$userVoteQuery = "
    SELECT 
        u.username, 
        p.question, 
        po.option_text AS poll_option, 
        pv.option_id AS user_voted_option, 
        pv.voted_at,
        po.id AS option_id
    FROM polls p
    JOIN poll_options po ON po.poll_id = p.id
    LEFT JOIN poll_votes pv ON pv.option_id = po.id AND pv.user_id = ?
    JOIN admin_login u ON u.id = ?
    ORDER BY p.id, po.id
";
$userStmt = $conn->prepare($userVoteQuery);
$userStmt->bind_param("ii", $user_id, $user_id);
$userStmt->execute();
$userResult = $userStmt->get_result();
$userVotes = [];
while ($row = $userResult->fetch_assoc()) {
    $question = $row['question'];
    if (!isset($userVotes[$question])) {
        $userVotes[$question] = [
            'username' => $row['username'],
            'voted_at' => $row['voted_at'],
            'user_voted_option' => $row['user_voted_option'],
            'options' => [],
        ];
    }
    $userVotes[$question]['options'][] = [
        'option_id' => $row['option_id'],
        'poll_option' => $row['poll_option'],
        'is_voted' => $row['user_voted_option'] == $row['option_id'],
    ];
}

// Fetch all polls and their options with global vote percentages
$globalVoteQuery = "
    SELECT 
        p.id AS poll_id, 
        p.question, 
        po.id AS option_id, 
        po.option_text, 
        po.votes, 
        (SELECT SUM(po2.votes) FROM poll_options po2 WHERE po2.poll_id = p.id) AS total_votes
    FROM polls p
    JOIN poll_options po ON po.poll_id = p.id
    ORDER BY p.id, po.id
";
$globalResult = $conn->query($globalVoteQuery);
$pollData = [];
while ($row = $globalResult->fetch_assoc()) {
    $row['percentage'] = $row['total_votes'] > 0 
        ? round(($row['votes'] / $row['total_votes']) * 100, 2) 
        : 0;
    $pollData[$row['poll_id']]['question'] = $row['question'];
    $pollData[$row['poll_id']]['options'][] = $row;
}

// Close connection
$userStmt->close();
$conn->close();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Activity - Breathe News</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css"> 
    <link rel="stylesheet" href="style/a.css"> 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .container {
            width: 80%;
            margin: 0 auto;
         
        }
        h1, h2 {
            text-align: left;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .highlight {
            font-weight: bold;
            background-color: #d4edda;
        }

      

        .chart-container {
            margin-top: 30px;
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


    <div class="container">
        <h1>Poll History and Global Results</h1>
        
        <h2>Your Voting History</h2>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Poll Question</th>
                    <th>Options</th>
                    <th>Vote Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($userVotes)): ?>
                    <tr>
                        <td colspan="4">No voting history found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($userVotes as $question => $details): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($details['username']); ?></td>
                            <td><?php echo htmlspecialchars($question); ?></td>
                            <td>
                                <ul>
                                    <?php foreach ($details['options'] as $option): ?>
                                        <li class="<?php echo $option['is_voted'] ? 'highlight' : ''; ?>">
                                            <?php echo htmlspecialchars($option['poll_option']); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                            <td><?php echo htmlspecialchars($details['voted_at'] ?? 'N/A'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>Global Poll Results</h2>
        <?php foreach ($pollData as $poll_id => $poll): ?>
            <h3><?php echo htmlspecialchars($poll['question']); ?></h3>
            <div class="chart-container">
                <canvas id="poll_<?php echo $poll_id; ?>"></canvas>
                <script>
                    var ctx = document.getElementById('poll_<?php echo $poll_id; ?>').getContext('2d');
                    var chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: <?php echo json_encode(array_column($poll['options'], 'option_text')); ?>,
                            datasets: [{
                                label: 'Votes (%)',
                                data: <?php echo json_encode(array_column($poll['options'], 'percentage')); ?>,
                                backgroundColor: function() {
                                    var colors = [];
                                    <?php foreach ($poll['options'] as $option): ?>
                                        colors.push('rgba(<?php echo rand(0, 255); ?>, <?php echo rand(0, 255); ?>, <?php echo rand(0, 255); ?>, 0.6)');
                                    <?php endforeach; ?>
                                    return colors;
                                },
                                borderColor: function() {
                                    var borderColors = [];
                                    <?php foreach ($poll['options'] as $option): ?>
                                        borderColors.push('rgba(<?php echo rand(0, 255); ?>, <?php echo rand(0, 255); ?>, <?php echo rand(0, 255); ?>, 1)');
                                    <?php endforeach; ?>
                                    return borderColors;
                                },
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100
                                }
                            }
                        }
                    });
                </script>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>
