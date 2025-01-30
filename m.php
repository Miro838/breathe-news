<link rel="stylesheet" href="style/m.css">
<div class="container">
        <!-- Sidebar Navigation -->
        <nav class="sidebar">
            <div class="profile">
                <img src="uploads/images/<?php echo htmlspecialchars($profile['profile_picture']); ?>" alt="Profile Image" class="profile-img">
                <h2><?php echo htmlspecialchars($profile['first_name']) . " " . htmlspecialchars($profile['last_name']); ?></h2>
            </div>
            <ul class="nav-links">
                <li><a href="m.php" data-section="user-info" class="active"><i class="fas fa-user"></i>User Info</a></li>
                <li><a href="account_activity.php" data-section="account-activity"><i class="fas fa-history"></i>Account Activity</a></li>
                <li><a href="breaking_news.php" data-section="breaking-news"><i class="fas fa-bolt"></i>Breaking News</a></li>
                <li><a href="trending_news.php" data-section="trending-news"><i class="fas fa-chart-line"></i>Trending News</a></li>
                <li><a href="exclusive_videos.php" data-section="exclusive-videos"><i class="fas fa-video"></i>Exclusive Videos</a></li>
                <li><a href="top_shows.php" data-section="top-shows"><i class="fas fa-tv"></i>Top Shows</a></li>
                <li><a href="quiz_participation.php" data-section="quiz-participation"><i class="fas fa-question-circle"></i>Quizzes</a></li>
                <li><a href="poll_participation.php" data-section="poll-participation"><i class="fas fa-poll"></i>Polls</a></li>
                <li><a href="job_offers.php" data-section="job-offers"><i class="fas fa-briefcase"></i>Job Offers</a></li>
                <li><a href="additional_info.php" data-section="additional-info"><i class="fas fa-info-circle"></i>Additional Info</a></li>
            
            </ul>
            <div class="logout">
                <a href="#"><i class="fas fa-sign-out-alt"></i>Logout</a>
            </div>
        </nav>