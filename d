// Fetch articles for the current category
                                $categoryName = $category['category_name'];
                                $articlesQuery = mysqli_prepare($conn, "SELECT * FROM articles WHERE category_name = ?");
                                mysqli_stmt_bind_param($articlesQuery, 's', $categoryName);
                                mysqli_stmt_execute($articlesQuery);
                                $articles = mysqli_stmt_get_result($articlesQuery);

                                // Display category stats
                                $categoryStatsQuery = mysqli_prepare($conn, "SELECT COUNT(*) as article_count, MAX(publish_date) as last_updated FROM articles WHERE category_name = ?");
                                mysqli_stmt_bind_param($categoryStatsQuery, 's', $categoryName);
                                mysqli_stmt_execute($categoryStatsQuery);
                                $categoryStats = mysqli_stmt_get_result($categoryStatsQuery);
                                $stats = mysqli_fetch_assoc($categoryStats);
                                echo '<p>Total Articles: ' . $stats['article_count'] . '</p>';
                                echo '<p>Last Updated: ' . $stats['last_updated'] . '</p>';

                                // Check if there are any articles
                                if (mysqli_num_rows($articles) == 0) {
                                    echo '<div class="carousel-item active"><p>No articles available for this category.</p></div>';
                                } else {
                                    $index = 0;
                                    while ($article = mysqli_fetch_assoc($articles)): ?>
                                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                            <img src="uploads/images/<?php echo htmlspecialchars($article['thumbnail']); ?>" class="d-block w-100" alt="Article Image">
                                            <div class="carousel-caption d-none d-md-block">
                                                <h5><?php echo htmlspecialchars($article['title']); ?></h5>
                                                <p><?php echo htmlspecialchars($article['description']); ?></p>
                                                <?php if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']): ?>
                                                <!-- Optionally show sign-up prompt if not logged in -->
                                                <?php else: ?>
                                                <a href="article.php?id=<?php echo $article['id']; ?>" class="btn btn-primary">Read More</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php $index++; endwhile; ?>
                                <?php } ?>
                            </div>
                            <a class="carousel-control-prev" href="#carousel<?php echo $category['id']; ?>" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carousel<?php echo $category['id']; ?>" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                        <div class="card-body text-center category-card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($category['category_name']); ?></h5>
                            <p><?php echo htmlspecialchars($category['description']); ?></p>
                            <a href="f.php" class="btn btn-primary mt-3">View</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>