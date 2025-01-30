<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Quiz Dashboard</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <link href="style/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="include/a.css">
</head>
<style>
        .form-inline .form-control {
            flex: 1 1 auto;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#"><?php echo htmlspecialchars($_SESSION["username"]); ?></a>
        <form class="form-inline w-100" action="search.php" method="get">
            <input class="form-control form-control-dark mr-sm-2 flex-fill" type="text" name="query" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search</button>
        </form>
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="logout.php">Sign out</a>
            </li>
        </ul>
    </nav>


    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'home') { echo 'active'; } ?>" href="quiz_dashboard.php">
                                <i class="fas fa-home"></i>
                                Dashboard <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'manage_quiz') { echo 'active'; } ?>" href="create_quiz.php">
                                <i class="fas fa-tasks"></i>
                                Manage Quiz
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'manage_questions') { echo 'active'; } ?>" href="create_question.php">
                                <i class="fas fa-question-circle"></i>
                                Manage Questions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'main_dashboard') { echo 'active'; } ?>" href="admin_main_dashboard.php">
                                <i class="fas fa-chart-bar"></i>
                                Main Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'manage_user') { echo 'active'; } ?>" href="admin_user_dashboard.php">
                                <i class="fas fa-users"></i>
                                Manage User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'manage_polls') { echo 'active'; } ?>" href="admin_polls_dashboard.php">
                                <i class="fas fa-poll"></i>
                                Manage Polls
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'manage_offers') { echo 'active'; } ?>" href="admin_dashboard_hiringoffer.php">
                                <i class="fas fa-briefcase"></i>
                                Manage Hiring Offers
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</body>
</html>
