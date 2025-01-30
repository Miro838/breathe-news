<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

include "include/header.php";
?>

<!-- Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <video id="videoPlayer" controls style="width: 100%;">
                    <source id="videoSource" src="" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div style="margin-left: 20%; width: 80%;">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="home.php">Home</a></li>
        <li class="breadcrumb-item active">Articles</li>
    </ul>
</div>

<div style="margin-left: 20%; width: 80%;">
    <div class="text-right mb-3">
        <a href="add_article.php" class="btn btn-primary">Add Article</a>
    </div>

    <!-- Articles Table -->
    <div style="overflow-x: auto;">
        <table class="table table-bordered" style="width: 100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Item ID</th>
                    <th>Description</th>
                    <th>Introduction</th>
                    <th>Content</th>
                    <th>Final Thoughts</th>
                    <th>Video Title</th>
                    <th>Video Description</th>
                    <th>Author ID</th>
                    <th>Publish Date</th>
                    <th>Category Name</th>
                    <th>Thumbnail</th>
                    <th>Inline Images</th>
                    <th>Video</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                include "db/connection.php";

                $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1; 
                $limit = 5;
                $offset = ($page - 1) * $limit;

                // Query to fetch articles with pagination
                $sql = "SELECT id, title, item_id, description, introduction, content, final_thoughts, video_title, video_description, 
                               author_id, publish_date, category_name, thumbnail, 
                               inline_image_1, inline_image_2, inline_image_3, video
                        FROM articles LIMIT ?, ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $offset, $limit);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result === false) {
                    echo "<tr><td colspan='18'>Error: " . htmlspecialchars($conn->error) . "</td></tr>";
                } else {
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["item_id"]) . "</td>";
                            echo "<td style='max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>" . htmlspecialchars($row["description"]) . "</td>";
                            echo "<td style='max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>" . htmlspecialchars($row["introduction"]) . "</td>";
                            echo "<td style='max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>" . htmlspecialchars($row["content"]) . "</td>";
                            echo "<td style='max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>" . htmlspecialchars($row["final_thoughts"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["video_title"]) . "</td>";
                            echo "<td style='max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>" . htmlspecialchars($row["video_description"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["author_id"]) . "</td>";

                            $publish_date = new DateTime($row["publish_date"]);
                            echo "<td>" . $publish_date->format('d F Y') . "</td>";

                            echo "<td>" . htmlspecialchars($row["category_name"]) . "</td>";
                            echo "<td><img src='uploads/images/" . htmlspecialchars($row["thumbnail"]) . "' alt='Thumbnail' style='width:80px; height:auto;'></td>";

                            // Displaying inline images
                            echo "<td>";
                            for ($i = 1; $i <= 3; $i++) {
                                $inline_image = htmlspecialchars($row["inline_image_$i"]);
                                if (!empty($inline_image)) {
                                    echo "<img src='uploads/inline_images/" . $inline_image . "' alt='Inline Image' style='width:80px; height:auto; margin: 0 5px;'>";
                                }
                            }
                            echo "</td>";

                            echo "<td>";
                            if (!empty($row["video"])) {
                                echo "<a href='#' data-bs-toggle='modal' data-bs-target='#videoModal' data-video='uploads/videos/" . htmlspecialchars($row["video"]) . "'>View Video</a>";
                            } else {
                                echo "No video";
                            }
                            echo "</td>";

                            echo "<td><a href='edit_article.php?id=" . urlencode($row["id"]) . "' class='btn btn-primary'>Edit</a></td>";
                            echo "<td><a href='delete_article.php?id=" . urlencode($row['id']) . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this item?\");'>Delete</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='18'>No articles found</td></tr>";
                    }
                }

                $stmt->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <ul class="pagination">
        <?php
        include "db/connection.php";
        $sql_count = $conn->query("SELECT COUNT(*) as count FROM articles");
        $count = $sql_count ? $sql_count->fetch_assoc()['count'] : 0;
        $total_pages = max(3, ceil($count / $limit));

        if ($page > 1) {
            echo "<li class='page-item'><a class='page-link' href='articles.php?page=" . ($page - 1) . "'>Previous</a></li>";
        } else {
            echo "<li class='page-item disabled'><a class='page-link' href='#'>Previous</a></li>";
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            echo $i == $page
                ? "<li class='page-item active'><a class='page-link' href='articles.php?page=$i'>$i</a></li>"
                : "<li class='page-item'><a class='page-link' href='articles.php?page=$i'>$i</a></li>";
        }

        if ($page < $total_pages) {
            echo "<li class='page-item'><a class='page-link' href='articles.php?page=" . ($page + 1) . "'>Next</a></li>";
        } else {
            echo "<li class='page-item disabled'><a class='page-link' href='#'>Next</a></li>";
        }
        ?>
    </ul>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var videoModal = document.getElementById('videoModal');
    videoModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var videoSrc = button.getAttribute('data-video');
        var videoPlayer = videoModal.querySelector('#videoPlayer');
        videoPlayer.src = videoSrc;
        videoPlayer.load();
    });
});
</script>
