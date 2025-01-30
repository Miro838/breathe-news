<?php
session_start();

// Redirect to login if the user is not authenticated
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db/connection.php";

// Function to handle file uploads (thumbnail and video)
function uploadFile($file, $type) {
    $upload_dir = $type === 'thumbnail' ? 'uploads/thumbnails/' : 'uploads/videos/';
    
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = basename($file["name"]);
    $target_file = $upload_dir . $file_name;
    $file_extension = pathinfo($target_file, PATHINFO_EXTENSION);
    $base_name = pathinfo($target_file, PATHINFO_FILENAME);
    
    $counter = 1;
    while (file_exists($target_file)) {
        $target_file = $upload_dir . $base_name . "_" . $counter . "." . $file_extension;
        $counter++;
    }
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $file_name; // Return just the file name
    } else {
        return false;
    }
}

// Fetch the show item to be updated
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = mysqli_query($conn, "SELECT * FROM top_shows WHERE id='$id'");

    if ($row = mysqli_fetch_array($query)) {
        $title = $row['title'];
        $description = $row['description'];
        $category_id = $row['category_id'];
        $status = $row['status'];
        $thumbnail = $row['thumbnail'];
        $video = $row['video'];
        $publish_date = $row['publish_date']; // Added publish_date
        $why_is_popular = $row['why_is_popular']; // Added new column
        $why_viewers_love = $row['why_viewers_love']; // Added new column
    } else {
        echo "Show item not found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}

// Fetch categories for the dropdown
$categories = [];
$category_query = mysqli_query($conn, "SELECT id, category_name FROM category");
while ($cat_row = mysqli_fetch_assoc($category_query)) {
    $categories[] = $cat_row;
}

// Handle form submission and update the show item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $publish_date = mysqli_real_escape_string($conn, $_POST['publish_date']);
    $why_is_popular = mysqli_real_escape_string($conn, $_POST['why_is_popular']);
    $why_viewers_love = mysqli_real_escape_string($conn, $_POST['why_viewers_love']);
    
    // Handle thumbnail upload
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        $thumbnail = uploadFile($_FILES['thumbnail'], 'thumbnail');
        if (!$thumbnail) {
            echo "Error uploading thumbnail.";
            exit();
        }
    } else {
        // Retain the existing thumbnail if no new file is uploaded
        $thumbnail = $row['thumbnail'];
    }

    // Handle video upload
    if (isset($_FILES['video']) && $_FILES['video']['error'] == 0) {
        $video = uploadFile($_FILES['video'], 'video');
        if (!$video) {
            echo "Error uploading video.";
            exit();
        }
    } else {
        // Retain the existing video if no new file is uploaded
        $video = $row['video'];
    }

    // Update the database
    $sql = "UPDATE top_shows SET 
            title='$title', 
            description='$description', 
            category_id='$category_id',  
            status='$status', 
            publish_date='$publish_date',
            why_is_popular='$why_is_popular', 
            why_viewers_love='$why_viewers_love'";

    if (!empty($thumbnail)) {
        $sql .= ", thumbnail='$thumbnail'";
    }
    if (!empty($video)) {
        $sql .= ", video='$video'";
    }

    $sql .= " WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: manage_shows.php");
        exit();
    } else {
        echo "<script>alert('Error updating show.');</script>";
    }
}

mysqli_close($conn);

include "include/header.php";
?>

<div style="margin-left:20%;width:80%">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="home.php">Home</a></li>
        <li class="breadcrumb-item"><a href="top_shows.php">Top Shows</a></li>
        <li class="breadcrumb-item active">Edit Show</li>
    </ul>
</div>

<div style="margin-left:25%; width:70%">
    <form action="" name="showform" onsubmit="return validateForm()" method="post" enctype="multipart/form-data">
        <h1>Edit Show</h1>

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" placeholder="Title..." id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required><br>
            
            <label for="description">Description</label>
            <textarea class="form-control" rows="5" id="description" name="description" placeholder="Description" required><?php echo htmlspecialchars($description); ?></textarea><br>
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <select class="form-control" id="category" name="category" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo $category_id == $category['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['category_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br>

            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="active" <?php echo $status == 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo $status == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
            </select><br>
            
            <label for="publish_date">Publish Date</label>
            <input type="date" class="form-control" id="publish_date" name="publish_date" value="<?php echo htmlspecialchars($publish_date); ?>" required><br>

            <label for="thumbnail">Thumbnail</label>
            <input type="file" class="form-control img-thumbnail" id="thumbnail" name="thumbnail"><br>

            <?php 
            if (!empty($thumbnail)) { 
                // Display the existing thumbnail
                $thumbnailPath = 'uploads/thumbnails/' . htmlspecialchars($thumbnail);
                if (file_exists($thumbnailPath)) { 
            ?>
                <img src="<?php echo $thumbnailPath; ?>" alt="Thumbnail" style="width:100px;"><br>
            <?php 
                } else { 
            ?>
                <img src="path/to/placeholder-image.jpg" alt="No Thumbnail Available" style="width:100px;"><br>
            <?php 
                } 
            } 
            ?>

            <label for="video">Video</label>
            <input type="file" class="form-control" id="video" name="video"><br>
            <?php 
            if (!empty($video)) { 
                $videoPath = 'uploads/videos/' . htmlspecialchars($video);
                if (file_exists($videoPath)) { 
            ?>
                <video width="320" height="240" controls>
                    <source src="<?php echo $videoPath; ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video><br>
            <?php 
                } 
            } 
            ?>

            <label for="why_is_popular">Why is this show popular?</label>
            <textarea class="form-control" id="why_is_popular" name="why_is_popular" required><?php echo htmlspecialchars($why_is_popular); ?></textarea><br>

            <label for="why_viewers_love">Why do viewers love this show?</label>
            <textarea class="form-control" id="why_viewers_love" name="why_viewers_love" required><?php echo htmlspecialchars($why_viewers_love); ?></textarea><br>
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Update Show" name="submit">
            <a href="manage_shows.php" class="btn btn-secondary">Back to Shows List</a>
        </div>
    </form>
</div>

<script>
function validateForm() {
    var title = document.forms["showform"]["title"].value;
    var description = document.forms["showform"]["description"].value;
    var publish_date = document.forms["showform"]["publish_date"].value;
    var why_is_popular = document.forms["showform"]["why_is_popular"].value;
    var why_viewers_love = document.forms["showform"]["why_viewers_love"].value;

    if (title == "") {
        alert("Title must be filled out");
        return false;
    }
    if (description.length < 100) {
        alert("Description must be at least 100 characters");
        return false;
    }
    if (publish_date == "") {
        alert("Publish date must be filled out");
        return false;
    }
    if (why_is_popular == "" || why_viewers_love == "") {
        alert("Please provide reasons why the show is popular and why viewers love it.");
        return false;
    }
}
</script>


