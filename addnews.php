<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db/connection.php";

if (isset($_POST['submit'])) {
    // Fetch form data and sanitize inputs
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['des']);
    $introduction = mysqli_real_escape_string($conn, $_POST['introduction']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $is_breaking = isset($_POST['is_breaking']) ? 1 : 0;
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $author_id = mysqli_real_escape_string($conn, $_POST['author_id']);
    $final_thoughts = mysqli_real_escape_string($conn, $_POST['final_thoughts']);

    $tags_input = mysqli_real_escape_string($conn, $_POST['tags']);
    $tags = explode(',', $tags_input); // Split tags into an array

    // Handle file upload for thumbnail
    $thumbnail = "";
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        $upload_dir = 'uploads/';
        
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $thumbnail = $upload_dir . basename($_FILES["thumbnail"]["name"]);
        $target_file = $thumbnail;
        $file_extension = pathinfo($thumbnail, PATHINFO_EXTENSION);
        $base_name = pathinfo($thumbnail, PATHINFO_FILENAME);

        $counter = 1;
        while (file_exists($target_file)) {
            $target_file = $upload_dir . $base_name . "_" . $counter . "." . $file_extension;
            $counter++;
        }

        if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file)) {
            $thumbnail = $target_file;
        } else {
            echo "Error uploading file.<br>";
            exit();
        }
    }

    // Handle file upload for inline images
    $inline_images = [];
    for ($i = 1; $i <= 3; $i++) {
        if (isset($_FILES["inline_image_$i"]) && $_FILES["inline_image_$i"]['error'] == 0) {
            $inline_image = $upload_dir . basename($_FILES["inline_image_$i"]["name"]);
            $target_file = $inline_image;
            $file_extension = pathinfo($inline_image, PATHINFO_EXTENSION);
            $base_name = pathinfo($inline_image, PATHINFO_FILENAME);

            $counter = 1;
            while (file_exists($target_file)) {
                $target_file = $upload_dir . $base_name . "_" . $counter . "." . $file_extension;
                $counter++;
            }

            if (move_uploaded_file($_FILES["inline_image_$i"]["tmp_name"], $target_file)) {
                $inline_images[$i] = $target_file;
            } else {
                echo "Error uploading inline image $i.<br>";
                exit();
            }
        } else {
            $inline_images[$i] = ""; // Set to empty if no file was uploaded
        }
    }

    // Insert into the news table
    $query = "INSERT INTO news (title, introduction, description, date, category_id, thumbnail, inline_image_1, inline_image_2, inline_image_3, status, is_breaking, content, author_id, final_thoughts) 
              VALUES ('$title', '$introduction', '$description', '$date', '$category_id', '$thumbnail', '{$inline_images[1]}', '{$inline_images[2]}', '{$inline_images[3]}', '$status', '$is_breaking', '$content', '$author_id', '$final_thoughts')";

    if (mysqli_query($conn, $query)) {
        $news_id = mysqli_insert_id($conn);

        // Insert tags into news_tags table
        foreach ($tags as $tag) {
            $tag = trim($tag); // Trim whitespace
            if (!empty($tag)) { // Avoid empty tags
                $tag_query = "INSERT INTO news_tags (news_id, tag) VALUES ('$news_id', '$tag')";
                mysqli_query($conn, $tag_query);
            }
        }

        echo "News added successfully.";
        header("Location: news.php");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}

include "include/header.php";

$page = 'product'; // Setting the page variable
?>
<div style="margin-left:20%;width:80%">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="home.php">Home</a></li>
        <li class="breadcrumb-item"><a href="news.php">News</a></li>
        <li class="breadcrumb-item active">Add News</li>
    </ul>
</div>
<div style="margin-left:25%; width:70%">
    <form action="addnews.php" name="categoryform" onsubmit="return validateform()" method="post" enctype="multipart/form-data">
        <h1>Add News</h1>

        <div class="form-group">
            Title
            <input type="text" class="form-control" placeholder="Title..." id="title" name="title" required><br>
            Description
            <textarea class="form-control" rows="5" id="comment" name="des" placeholder="Description" required></textarea><br>
            Introduction
            <textarea class="form-control" rows="5" id="introduction" name="introduction" placeholder="Introduction" required></textarea><br>
            Content
            <textarea class="form-control" rows="10" id="content" name="content" placeholder="Full Content" required></textarea><br>
            Final Thoughts
            <textarea class="form-control" rows="5" id="final_thoughts" name="final_thoughts" placeholder="Final Thoughts" required></textarea><br>
        </div>
        
        <div class="form-group">
            Date
            <input type="date" class="form-control" placeholder="Date" id="date" name="date" required><br>
            Thumbnail
            <input type="file" class="form-control img-thumbnail" id="thumbnail" name="thumbnail" accept="image/*" required><br>
        </div>
        
        <div class="form-group">
            Inline Image 1
            <input type="file" class="form-control img-thumbnail" id="inline_image_1" name="inline_image_1" accept="image/*"><br>
            Inline Image 2
            <input type="file" class="form-control img-thumbnail" id="inline_image_2" name="inline_image_2" accept="image/*"><br>
            Inline Image 3
            <input type="file" class="form-control img-thumbnail" id="inline_image_3" name="inline_image_3" accept="image/*"><br>
        </div>

        <div class="form-group">
            Category
            <select name="category_id" id="category" class="form-control" required>
            <?php
                $query = mysqli_query($conn, "SELECT * FROM category");
                while ($row = mysqli_fetch_array($query)) {
                    $category_id = $row['id'];
                    $category_name = $row['category_name'];
                    echo "<option value='$category_id'>$category_name</option>";
                }
            ?>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
                <option value="archived">Archived</option>
            </select>
        </div>
        
        <div class="form-group">
            Author ID
            <input type="text" class="form-control" placeholder="Author ID" id="author_id" name="author_id" required><br>
        </div>

        <div class="form-group">
            Tags
            <textarea class="form-control" placeholder="Comma-separated tags" id="tags" name="tags"></textarea><br>
        </div>

        <input type="submit" class="btn btn-primary" value="Add News" name="submit">
    </form>
</div>

<script>
function validateform() {
    var x = document.forms["categoryform"]["title"].value;
    var y = document.forms["categoryform"]["des"].value;
    var z = document.forms["categoryform"]["date"].value;
    var w = document.forms["categoryform"]["category_id"].value;
    
    if (x == "") {
        alert("Title must be filled out");
        return false;
    }
    if (y == "") {
        alert("Description must be filled out");
        return false;
    }
    if (z == "") {
        alert("Date must be filled out");
        return false;
    }
    if (w == "") {
        alert("Category must be selected");
        return false;
    }
}
</script>


