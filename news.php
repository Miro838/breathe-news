<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

include "include/header.php";
?>

<div style="margin-left:20%; width:80%">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="news.php">Home</a></li>
        <li class="breadcrumb-item active">News</li>
    </ul>
</div>

<div style="margin-left:20%; width:80%">
    <div class="text-right mb-3">
        <a href="addnews.php" class="btn btn-primary">Add News</a>
    </div>

    <!-- News Table -->
    <div style="overflow-x:auto;">
        <table class="table table-bordered" style="width: 100%; max-width: 100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Introduction</th>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Thumbnail</th>
                    <th>Inline Images</th>
                    <th>Status</th>
                    <th>Breaking</th>
                    <th>Tags</th>
                    <th>Author</th>
                    <th>Final Thoughts</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include "db/connection.php";

                // Pagination settings
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = 2;
                $offset = ($page - 1) * $limit;
                $search_term = $_GET['search_term'] ?? '';
                // Query to fetch data with additional fields
                $sql = "SELECT news.id, news.title, news.description, news.introduction, news.date, news.thumbnail, 
                               news.inline_image_1, news.inline_image_2, news.inline_image_3, 
                               news.status, news.is_breaking, news.content, news.author_id, 
                               news.final_thoughts, category.category_name, 
                               GROUP_CONCAT(news_tags.tag SEPARATOR ', ') AS tags
                        FROM news
                        LEFT JOIN category ON news.category_id = category.id
                        LEFT JOIN news_tags ON news.id = news_tags.news_id
                        GROUP BY news.id
                        LIMIT $offset, $limit";

                $result = $conn->query($sql);

                if (!$result) {
                    echo "<tr><td colspan='15'>Error fetching data: " . $conn->error . "</td></tr>";
                } elseif ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
                        echo "<td style='max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>" . htmlspecialchars($row["description"]) . "</td>";
                        echo "<td style='max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>" . htmlspecialchars($row["introduction"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["category_name"]) . "</td>";
                        echo "<td><img src='" . htmlspecialchars($row["thumbnail"]) . "' alt='Thumbnail' style='width:80px; height:auto;'></td>";
                        
                        // Display inline images if available
                        echo "<td>";
                        foreach (['inline_image_1', 'inline_image_2', 'inline_image_3'] as $imgField) {
                            if (!empty($row[$imgField])) {
                                echo "<img src='" . htmlspecialchars($row[$imgField]) . "' alt='Inline Image' style='width:50px; height:auto; margin-right:5px;'>";
                            }
                        }
                        echo "</td>";
                        
                        echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                        echo "<td>" . ($row["is_breaking"] ? "Yes" : "No") . "</td>";
                        echo "<td>" . (!empty($row["tags"]) ? htmlspecialchars($row["tags"]) : 'No tags') . "</td>";
                        echo "<td>" . htmlspecialchars($row["author_id"]) . "</td>";
                        echo "<td style='max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>" . htmlspecialchars($row["final_thoughts"]) . "</td>";

                        echo "<td><a href='editnews.php?id=" . urlencode($row["id"]) . "' class='btn btn-primary' role='button'>Edit</a></td>";
                        echo "<td><a href='deletenews.php?id=" . urlencode($row["id"]) . "' class='btn btn-danger' role='button' onclick='return confirm(\"Are you sure you want to delete this item?\");'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='15'>No news items found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <ul class="pagination">
        <?php
        include "db/connection.php";
        $sql_count = $conn->query("SELECT COUNT(*) as count FROM news");
        $count = $sql_count ? $sql_count->fetch_assoc()['count'] : 0;
        $total_pages = max(3, ceil($count / $limit));

        if ($page > 1) {
            echo "<li class='page-item'><a class='page-link' href='news.php?page=" . ($page - 1) . "'>Previous</a></li>";
        } else {
            echo "<li class='page-item disabled'><a class='page-link' href='#'>Previous</a></li>";
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            echo $i == $page
                ? "<li class='page-item active'><a class='page-link' href='news.php?page=$i'>$i</a></li>"
                : "<li class='page-item'><a class='page-link' href='news.php?page=$i'>$i</a></li>";
        }

        if ($page < $total_pages) {
            echo "<li class='page-item'><a class='page-link' href='news.php?page=" . ($page + 1) . "'>Next</a></li>";
        } else {
            echo "<li class='page-item disabled'><a class='page-link' href='#'>Next</a></li>";
        }
        ?>
    </ul>

</div>
