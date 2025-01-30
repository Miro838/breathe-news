<?php
ob_start(); // Start output buffering
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}
include "include/header.php";
include "db/connection.php";

$id = isset($_GET['edit']) ? $_GET['edit'] : null;
$category = '';
$description = '';
$thumbnail = '';
$video = '';

if ($id) {
    $query = mysqli_query($conn, "SELECT * FROM category WHERE id='$id'");
    if ($row = mysqli_fetch_array($query)) {
        $category = $row['category_name'];
        $description = $row['description'];
        $thumbnail = $row['thumbnail'];
        $video = $row['video'];
    }
}
?>

<div style="margin-left:25%; margin-top:10%; width:70%">
<form action="editcategory.php" name="categoryform" onsubmit="return validateform()" method="post" enctype="multipart/form-data">
    <h1>Update Category</h1>
    <div class="form-group">
        <label for="category">Category Name</label>
        <input type="text" class="form-control" placeholder="Category Name" id="category" name="category" value="<?php echo htmlspecialchars($category); ?>"><br>

        <label for="description">Description</label>
        <textarea class="form-control" rows="5" id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea><br>

        <label for="thumbnail">Thumbnail</label>
        <input type="file" class="form-control" id="thumbnail" name="thumbnail"><br>
        <?php if ($thumbnail): ?>
            <img src="uploads/videos/<?php echo htmlspecialchars($thumbnail); ?>" alt="Current Thumbnail" style="width:100px;"><br>
        <?php endif; ?>

        <label for="video">Upload Video</label>
        <input type="file" class="form-control" id="video" name="video"><br>
        <?php if ($video): ?>
            <video width="320" height="240" controls>
                <source src="uploads/videos/<?php echo htmlspecialchars($video); ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video><br>
        <?php endif; ?>
    </div>
    <input type="hidden" value="<?php echo htmlspecialchars($id); ?>" name="id">
    <input type="submit" class="btn btn-primary" value="Update Category" name="submit">
</form>
<script>
function validateform() {
    var x = document.forms['categoryform']['category'].value;
    if (x === "") {
        alert('Category must be filled out');
        return false;
    }
}
</script>
</div>

<?php
if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $category = $_POST['category'];
    $description = isset($_POST['description']) ? $_POST['description'] : '';

    // Handle file upload for thumbnail
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['thumbnail']['tmp_name'];
        $name = basename($_FILES['thumbnail']['name']);
        $upload_dir = 'uploads/';
        $thumbnail = $upload_dir . $name;
        move_uploaded_file($tmp_name, $thumbnail);
    }

    // Handle file upload for video
    if (isset($_FILES['video']) && $_FILES['video']['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['video']['tmp_name'];
        $name = basename($_FILES['video']['name']);
        $upload_dir = 'uploads/';
        $video = $upload_dir . $name;
        move_uploaded_file($tmp_name, $video);
    }

    $stmt = $conn->prepare("UPDATE category SET category_name=?, description=?, thumbnail=?, video=? WHERE id=?");
    $stmt->bind_param("ssssi", $category, $description, $thumbnail, $video, $id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: categories.php"); // Corrected redirect
        exit();
    } else {
        echo "Category not updated.";
    }
}

ob_end_flush(); // End output buffering
?>
