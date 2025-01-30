<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Crime News</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style/a.css">
    <style>
        /* Custom Styles */
        body {
            font-family: 'Roboto', sans-serif;
            color: black;
            margin: 0;
            padding: 0;
            background-color: white;
        }

        .header {
            color: black;
            text-align: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #FFD700;
            position: relative;
        }

        .header h1 {
            font-size: 4em;
            margin: 0;
            animation: fadeInUp 2s ease-out;
        }

        .header p {
            font-size: 1.5em;
            animation: fadeInUp 2s ease-out 0.5s;
        }

        .section {
            padding:60 0;
            background-color: white;
        }

        .section h2, .section p {
            color: black;
        }

        .carousel-item {
            padding: 20px;
            border-radius: 10px;
            background-color: #f0f0f0;
        }

        .carousel-item img {
            width: 1250px;
            height: auto;
            margin-bottom: 15px;
            border-radius: 10px;
        }

        .carousel-item .description {
            color: purple;
        }

        .cta-section {
            background-color: #ddd;
            color: black;
            text-align: center;
            padding: 60px 0;
            margin-top: 100px;
        }

        .cta-section h2 {
            font-size: 3em;
            animation: fadeInUp 2s ease-out;
        }

        .cta-section p {
            font-size: 1.5em;
            animation: fadeInUp 2s ease-out 0.5s;
        }

        .cta-section .btn-primary {
            font-size: 1.2em;
            background-color: purple;
            border-color: purple;
            animation: fadeInUp 2s ease-out 1s;
            color: white;
        }

        .cta-section .btn-primary:hover {
            background-color: darkviolet;
        }

        .footer {
            background-color: #fff;
            color: black;
            padding: 20px 0;
            text-align: center;
        }

        /* Navbar */
        .navbar {
            background-color: white;
            color: black;
        }

        .navbar a {
            color: black;
        }

        .navbar a:hover {
            color: purple;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Set all links to black and purple on hover */
        a {
            color: black;
        }

        a:hover {
            color: purple;
        }
    </style>
    <!-- Animation Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/a.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<header>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">BREATHE NEWS</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Main </a></li>
                    <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="category.php">Categories</a></li>
                    <li class="nav-item"><a class="nav-link" href="quiz.php">Quizzes</a></li>
                    <li class="nav-item"><a class="nav-link" href="polls.php">Polls</a></li>
                    <li class="nav-item"><a class="nav-link" href="hiringoffers.php">Hiring Offers</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-primary text-white" style="background-color:purple" href="signUp.php">Sign Up</a></li>
                    
                </ul>
            </div>
        </div>
    </nav>
</header>

<!-- Call to Action -->
<section class="cta-section text-center py-5" style="background-color:#d3c1ff;">
    <div class="container d-flex align-items-center justify-content-center">
        <img src="imgs/1.jpg" alt="Explore Image" class="animate__animated animate__fadeInUp" style="width:500px;height:250px">
        <div class="text-content">
            <h2 class="display-4 mb-4 animate__animated animate__fadeInUp">Are you ready to explore the darkest part of the World?</h2>
            <p class="lead animate__animated animate__fadeInUp">IF YES, Click below to explore our Platform, where you can find the latest videos, articles, quizzes, and more.</p>
            <a href="#features" class="btn btn-primary btn-lg mt-3 animate__animated animate__fadeInUp">What We Offer</a>
            <a href="#about" class="btn btn-primary btn-lg mt-3 animate__animated animate__fadeInUp">About Us</a>
            <a href="home.php" class="btn btn-primary btn-lg mt-3 animate__animated animate__fadeInUp">Go to Home</a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="section py-5" >
    <div class="container text-center" style="background-color:#d3c1ff;">
        <h2 class="display-4 mb-5 animate__animated animate__fadeInUp">What We Offer</h2>

        <!-- Carousel -->
        <div id="offersCarousel" class="carousel slide" data-ride="carousel" >
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active" style="background-color:#d3c1ff;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="description">
                                <img src="imgs/download (4).jfif" class="img-fluid" alt="Crime Categories">
                                <h3 class="display-4 animate__animated animate__fadeInUp">Breaking News</h3>
                                <p class="animate__animated animate__fadeInUp">Stay updated with the latest breaking news in crime, including key incidents and updates.</p>
                                <a href="home.php" class="btn btn-primary text-white" style="background-color:purple">View Breaking News</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="description">
                                <img src="imgs/as.png" class="img-fluid" alt="Crime Quizzes">
                                <h3 class="display-4 animate__animated animate__fadeInUp">Trending News</h3>
                                <p class="animate__animated animate__fadeInUp">Discover the trending news stories surrounding crime and law enforcement in your area.</p>
                                <a href="home.php" class="btn btn-primary text-white" style="background-color:purple">View Trending News</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="description">
                                <img src="imgs/ds.png" class="img-fluid" alt="Polls on Crime">
                                <h3 class="display-4 animate__animated animate__fadeInUp">Top Shows</h3>
                                <p class="animate__animated animate__fadeInUp">Engage in discussions about the latest crime-related shows and share your insights through polls.</p>
                                <a href="home.php" class="btn btn-primary text-white" style="background-color:purple">View Top Shows</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item" style="background-color:#d3c1ff">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="description">
                                <img src="imgs/pexels-mediocrememories-2157191.jpg" class="img-fluid" alt="Latest Articles">
                                <h3 class="display-4 animate__animated animate__fadeInUp">Categories</h3>
                                <p class="animate__animated animate__fadeInUp">Explore a variety of categories detailing crime trends, investigative reports, and essential safety tips.</p>
                                <a href="category.php" class="btn btn-primary text-white" style="background-color:purple">Discover Categories</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="description">
                                <img src="imgs/download (1).jfif" class="img-fluid" alt="Crime Statistics">
                                <h3 class="display-4 animate__animated animate__fadeInUp">Quizzes</h3>
                                <p class="animate__animated animate__fadeInUp">Challenge yourself with quizzes that test your knowledge of crime and law, enhancing your understanding.</p>
                                <a href="quiz.php" class="btn btn-primary text-white" style="background-color:purple">Take a Quiz</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="description">
                                <img src="imgs/download (2).jfif" class="img-fluid" alt="Community Engagement">
                                <h3 class="display-4 animate__animated animate__fadeInUp">Polls</h3>
                                <p class="animate__animated animate__fadeInUp">Participate in our polls to voice your opinion on critical crime-related issues and community initiatives.</p>
                                <a href="polls.php" class="btn btn-primary text-white" style="background-color:purple">Join Polls</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 (New Slide) -->
                <div class="carousel-item" style="background-color:#d3c1ff">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="description">
                                <img src="imgs/download (3).jfif" class="img-fluid" alt="News Alerts">
                                <h3 class="display-4 animate__animated animate__fadeInUp">Job Offers</h3>
                                <p class="animate__animated animate__fadeInUp">Find your dream job in crime field.</p>
                                <a href="hiring0ffers.php" class="btn btn-primary text-white" style="background-color:purple">Go Find</a>
                            </div>
                        </div>
                       
                    </div>
                </div>

            </div>

            <a class="carousel-control-prev" href="#offersCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#offersCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</section>
<!-- About Section -->
<section id="about" class="section py-5" style="background-color:#d3c1ff; margin-bottom: 0;">
    <div class="container text-center">
        <div class="row align-items-center">
            <!-- Text Content -->
            <div class="col-md-6">
                <h2 class="display-4 mb-5 animate__animated animate__fadeInUp">About Us</h2>
                <p class="lead animate__animated animate__fadeInUp">Breathe News is your go-to platform for staying informed and inspired. We curate news that matters,
                    delivering reliable updates across various categories like Cold Cases, Unsolved Mysteries, Serial Killers and more.
                     Our mission is to keep you connected with the world through
                      insightful reporting and compelling stories.</p>
                <a href="about.php" class="btn btn-primary text-white" style="background-color:purple">Learn More</a>
            </div>

            <!-- Image -->
            <div class="col-md-6">
                <img src="imgs/js.jpg" class="img-fluid animate__animated animate__fadeInUp" alt="About Us Image">
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="section py-5" style="background-color: #d3c1ff; margin-top: 50px;">
    <div class="container text-center">
        <h2 class="display-4 mb-5 animate__animated animate__fadeInUp">What Our Users Say</h2>

        <!-- Carousel -->
        <div id="testimonialsCarousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php
                  include 'db/connection.php';

                  // Fetch approved testimonials
                  $query = 'SELECT 
                  al.username AS admin_username, t.content, t.created_at  
                  FROM testimonials t 
                  INNER JOIN admin_login al ON t.user_id = al.id 
                  WHERE t.approved = 1 
                  ORDER BY t.created_at DESC';

                  $result = $conn->query($query);

                  if ($result->num_rows > 0) {
                      $isActive = true; // To set the first item as active

                      while ($row = $result->fetch_assoc()) {
                          ?>
                          <div class="carousel-item <?php echo $isActive ? 'active' : ''; ?>">
                              <div class="row justify-content-center">
                                  <div class="col-md-8">
                                      <div class="testimonial">
                                          <p class="lead animate__animated animate__fadeInUp">
                                              <?php echo htmlspecialchars($row['admin_username']); ?>
                                          </p>
                                          <p class="lead animate__animated animate__fadeInUp">
                                              "<?php echo htmlspecialchars($row['content']); ?>"
                                          </p>
                                          <p class="text-muted">
                                              ON <?php echo date("F j, Y", strtotime($row['created_at'])); ?>
                                          </p>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <?php
                          $isActive = false;
                      }
                  } else {
                      echo "<p>No testimonials available at the moment.</p>";
                  }
                  $conn->close();
                ?>
            </div>

            <!-- Carousel Controls -->
            <a class="carousel-control-prev" href="#testimonialsCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#testimonialsCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</section>


<!-- Footer -->
<footer class="footer py-4">
    <?php include "include/footer1.php"?>
    <div class="container">
        <p class="text-center mb-0">© 2024 Crime News. All rights reserved.</p>
    </div>
</footer>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
