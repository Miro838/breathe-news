<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard Template</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="style/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="include/a.css">
    <!-- jQuery and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
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
                            <a class="nav-link <?php if ($page == 'admin_user') { echo 'active'; } ?>" href="admin_user_dashboard.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'view_user') { echo 'active'; } ?>" href="view_user.php">
                                <i class="fas fa-photo-video"></i> View User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'pass reset') { echo 'active'; } ?>" href="view_user_profile.php">
                                <i class="far fa-newspaper"></i> View User Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'about') { echo 'active'; } ?>" href="manage_about.php">
                                <i class="fas fa-tags"></i> Manage About Us
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'faq') { echo 'active'; } ?>" href="manage_faq.php">
                                <i class="fas fa-tags"></i> Manage FAQ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'main_dashboard') { echo 'active'; } ?>" href="admin_main_dashboard.php">
                                <i class="fas fa-file-alt"></i> Main Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'quiz') { echo 'active'; } ?>" href="quiz_dashboard.php">
                                <i class="fas fa-photo-video"></i> Manage Quiz
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'polls') { echo 'active'; } ?>" href="admin_polls_dashboard.php">
                                <i class="fas fa-photo-video"></i> Manage Polls
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'hiring_offer') { echo 'active'; } ?>" href="admin_dashboard_hiringoffer.php">
                                <i class="fas fa-photo-video"></i> Manage Hiring Offer
                            </a>
                        </li>
                    </ul>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span></span>
                        <a href="#">
                            <i class="fas fa-plus-circle"></i>
                        </a>
                    </h6>
                    
                </div>
            </nav>
        </div>
    </div>
</body>
</html>
