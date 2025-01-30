<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

include "include/header.php";
include "db/connection.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
</head>
<body>
    
<div style="margin-left:20%; width:80%">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="categories.php">Home</a></li>
        <li class="breadcrumb-item active">Categories </li>
    </ul>
</div>
<div style="margin-left:25%; margin-top:30px; width:70%">
    <div class="text-right">
        <button style="background-color:blue; margin-bottom:10px; height:30px" ><a href="addcategory.php" style="color:white">Add Category</a></buttons>
    </div>
    <table class="table table-bordered">
        <tr>
            <th>Id</th>
            <th>Category Name</th>
            <th>Description</th>
            <th>Thumbnail</th>
            <th>Video</th>
            <th>Actions</th>
        </tr>
        <?php 
        $query = mysqli_query($conn, "SELECT * FROM category"); // Make sure the table name is correct
        while ($row = mysqli_fetch_array($query)) {
            // Paths stored in the database already include 'uploads/', so no need to add it again
            $thumbnail_path = htmlspecialchars($row['thumbnail']);
            $video_path = htmlspecialchars($row['video']);
        ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['category_name']); ?></td>
            <td><?php echo htmlspecialchars(substr($row['description'], 0, 200)); ?><?php if (strlen($row['description']) > 200) echo '...'; ?></td>
            <td>
                <?php if (!empty($thumbnail_path)): ?>
                    <img src="<?php echo $thumbnail_path; ?>" alt="Thumbnail" style="width:100px;">
                <?php else: ?>
                    No Thumbnail
                <?php endif; ?>
            </td>
            <td>
                <?php if (!empty($video_path)): ?>
                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#videoModal" data-video="<?php echo $video_path; ?>">Play Video</button>
                <?php else: ?>
                    No Video
                <?php endif; ?>
            </td>
            <td>
                <a href="editcategory.php?edit=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-info">Edit</a>
                <a href="deletecategory.php?delete=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

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



<!-- Include Bootstrap JavaScript for Modal Functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var videoModal = document.getElementById('videoModal');
    videoModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Button that triggered the modal
        var videoSrc = button.getAttribute('data-video'); // Extract video URL from data attribute

        var modalVideo = videoModal.querySelector('#videoPlayer');
        var modalVideoSource = videoModal.querySelector('#videoSource');

        modalVideoSource.src = videoSrc;
        modalVideo.load(); // Load the new video
    });
});
</script>
