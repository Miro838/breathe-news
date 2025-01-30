<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <title>Dashboard Template</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <link href="style/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="include/a.css">
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
                            <a class="nav-link <?php if ($page == 'home') { echo 'active'; } ?>" href="admin_dashboard_hiringoffer.php">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard <span class="sr-only">(current)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'manage_media') { echo 'active'; } ?>" href="manage_joboffer.php">
                                <i class="fas fa-photo-video"></i>
                                Manage jobs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'news') { echo 'active'; } ?>" href="view_applications.php">
                                <i class="far fa-newspaper"></i>
                                View applications
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'user_activity') { echo 'active'; } ?>" href="admin_main_dashboard.php">
                                <i class="fas fa-chart-line"></i>
                                main dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'categories') { echo 'active'; } ?>" href="admin_user_dashboard.php">
                                <i class="fas fa-tags"></i>
                                manage User
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'articles') { echo 'active'; } ?>" href="quiz_dashboard.php">
                                <i class="fas fa-file-alt"></i>
                                manage quiz
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if ($page == 'manage_shows') { echo 'active'; } ?>" href="admin_polls_dashboard.php">
                                <i class="fas fa-photo-video"></i>
                                Manage Polls 
                            </a>
                        </li>
                    
                    </ul>
                    
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span></span>
                        <a class="d-flex align-items-center text-muted" href="#">
                            <i class="fas fa-plus-circle"></i>
                        </a>
                    </h6>
                </div>
            </nav>
        </div>
    </div>
</body>
</html>
