<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

include 'db/connection.php';
include 'include/header.php'; // Include the header

// Pagination Logic
$totalItems = $conn->query("SELECT COUNT(*) as count FROM top_shows")->fetch_assoc()['count'];
$itemsPerPage = 3; // Number of items to show per page
$totalPages = ceil($totalItems / $itemsPerPage);

// Get current page from URL (default to 1 if not set)
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) $currentPage = 1;
if ($currentPage > $totalPages) $currentPage = $totalPages;

$startIndex = ($currentPage - 1) * $itemsPerPage;
$sql = "
    SELECT ts.*, c.category_name 
    FROM top_shows ts
    LEFT JOIN category c ON ts.category_id = c.id
    LIMIT $startIndex, $itemsPerPage
";
$result = $conn->query($sql);
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

<!-- Breadcrumb -->
<div style="margin-left:20%; width:80%">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="home.php">Home</a></li>
        <li class="breadcrumb-item active">Top Shows</li>
    </ul>
</div>

<div style="margin-left:20%; width:80%">
    <div class="text-right mb-3">
        <h1 style="text-align:center">Manage Shows</h1>
        <a href="add_shows.php" class="btn btn-primary">Add Shows</a>
    </div>

    <!-- Shows Table -->
    <table class="table table-bordered table-striped table-custom">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th style="width:10px">Description</th>
                <th>Thumbnail</th>
                <th>Video</th>
                <th>Publish Date</th>
                <th>Category</th>
                <th>Status</th>
                <th>Why Is Popular</th>
                <th>Why Viewers Love</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>
                        <?php if (!empty($row['thumbnail'])): ?>
                            <img src="uploads/thumbnails/<?php echo htmlspecialchars($row['thumbnail']); ?>" alt="Thumbnail" class="img-fluid" style="max-width: 100px;">
                        <?php else: ?>
                            <p style="color: red;">Image not found</p>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($row['video'])): ?>
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#videoModal" data-video="uploads/videos/<?php echo htmlspecialchars($row['video']); ?>">Play Video</button>
                        <?php else: ?>
                            No Video
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('d F Y', strtotime($row['publish_date'])); ?></td>
                    <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                    <td><?php echo ucfirst(htmlspecialchars($row['status'])); ?></td>
                    <td><?php echo htmlspecialchars($row['why_is_popular']); ?></td>
                    <td><?php echo htmlspecialchars($row['why_viewers_love']); ?></td>
                    <td>
                        <a href="edit_shows.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_shows.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item <?php if ($currentPage == 1) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>">Previous</a>
            </li>
            <?php
            // Display up to 3 page numbers (1, 2, 3 ...)
            for ($i = max(1, $currentPage - 1); $i <= min($totalPages, $currentPage + 1); $i++) {
                echo '<li class="page-item ' . ($i == $currentPage ? 'active' : '') . '">
                        <a class="page-link" href="?page=' . $i . '">' . $i . '</a>
                    </li>';
            }
            ?>
            <li class="page-item <?php if ($currentPage == $totalPages) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>">Next</a>
            </li>
        </ul>
    </nav>
</div>

<!-- Include Bootstrap JavaScript for Modal Functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var videoModal = document.getElementById('videoModal');
    videoModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var videoSrc = button.getAttribute('data-video');
        var modalVideo = videoModal.querySelector('#videoPlayer');
        var modalVideoSource = videoModal.querySelector('#videoSource');
        modalVideoSource.src = videoSrc;
        modalVideo.load();
    });
});
</script>

<?php
$conn->close();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
