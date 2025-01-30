<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

include "include/header.php";
include "db/connection.php";
?>

<div style="margin-left:25%; margin-top:10%; width:70%">
   
    <form action="" name="categoryform" onsubmit="return validateform()" method="post" enctype="multipart/form-data">
        <h1>Add Category</h1>

        <div class="form-group">
            <label for="category">Category Name</label>
            <input type="text" class="form-control" placeholder="Enter category name" id="category" name="category"><br>
            
            <label for="description">Description</label>
            <textarea class="form-control" rows="5" id="description" name="description" placeholder="Enter description"></textarea><br>
            
            <label for="thumbnail">Thumbnail</label>
            <input type="file" class="form-control" id="thumbnail" name="thumbnail"><br>
            
            <label for="video">Video</label>
            <input type="file" class="form-control" id="video" name="video"><br>
        </div>

        <input type="submit" class="btn btn-primary" value="Add Category" name="submit">
    </form>

    <script>
    function validateform() {
        var category = document.forms['categoryform']['category'].value;
        if (category == "") {
            alert('Category name must be filled out');
            return false;
        }
    }
    </script>
</div>

<?php
if (isset($_POST["submit"])) {
    $category_name = mysqli_real_escape_string($conn, $_POST['category']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Handle file upload for thumbnail
    $thumbnail = "";
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['thumbnail']['tmp_name'];
        $name = basename($_FILES['thumbnail']['name']);
        $upload_dir = 'uploads/images/'; // Ensure this directory exists and is writable
        $thumbnail = $upload_dir . $name;
        if (!move_uploaded_file($tmp_name, $thumbnail)) {
            $thumbnail = ""; // Reset if upload fails
        }
    }

    // Handle file upload for video
    $video = "";
    if (isset($_FILES['video']) && $_FILES['video']['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['video']['tmp_name'];
        $name = basename($_FILES['video']['name']);
        $upload_dir = 'uploads/videos/'; // Ensure this directory exists and is writable
        $video = $upload_dir . $name;
        if (!move_uploaded_file($tmp_name, $video)) {
            $video = ""; // Reset if upload fails
        }
    }

    // Check for duplicate category name
    $check = mysqli_query($conn, "SELECT * FROM category WHERE category_name='$category_name'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Category name already taken');</script>";
    } else {
        // Insert category data into the database
        $query = mysqli_query($conn, "INSERT INTO category (category_name, description, thumbnail, video) VALUES ('$category_name', '$description', '$thumbnail', '$video')");
        
        if ($query) {
            echo "<script>alert('Category added successfully'); window.location.href = 'categories.php';</script>";
        } else {
            echo "<script>alert('Error adding category: " . mysqli_error($conn) . "');</script>";
        }
    }
}


