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

// Function to handle file uploads
function uploadFile($file, $upload_dir = 'uploads/') {
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_path = $upload_dir . basename($file["name"]);
    $target_file = $file_path;
    $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);
    $base_name = pathinfo($file_path, PATHINFO_FILENAME);
    
    $counter = 1;
    while (file_exists($target_file)) {
        $target_file = $upload_dir . $base_name . "_" . $counter . "." . $file_extension;
        $counter++;
    }
    
    return move_uploaded_file($file["tmp_name"], $target_file) ? $target_file : false;
}

// Fetch the news item to be updated
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = mysqli_query($conn, "SELECT * FROM news WHERE id='$id'");

    if ($row = mysqli_fetch_array($query)) {
        $title = $row['title'];
        $introduction = $row['introduction'];
        $description = $row['description'];
        $date = $row['date'];
        $thumbnail = $row['thumbnail'];
        $category_id = $row['category_id'];
        $status = $row['status'];
        $content = $row['content'];
        $author_id = $row['author_id'];
        $final_thoughts = $row['final_thoughts']; // Fetch final thoughts
    } else {
        echo "News item not found.";
        exit();
    }

    // Fetch associated tags
    $tags_query = mysqli_query($conn, "SELECT tag FROM news_tags WHERE news_id='$id'");
    $existing_tags = [];
    while ($tag_row = mysqli_fetch_array($tags_query)) {
        $existing_tags[] = $tag_row['tag'];
    }
} else {
    echo "Invalid request.";
    exit();
}

// Handle form submission and update the news item
if (isset($_POST['submit'])) {
    // Fetch form data and sanitize inputs
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['des']);
    $introduction = mysqli_real_escape_string($conn, $_POST['introduction']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $author_id = mysqli_real_escape_string($conn, $_POST['author_id']);
    $final_thoughts = mysqli_real_escape_string($conn, $_POST['final_thoughts']); // Get final thoughts from form
    
    // Handle tags (if any)
    $tags = isset($_POST['tags']) ? array_map('trim', explode(',', $_POST['tags'])) : [];
    
    // Handle file upload for thumbnail
    $thumbnail_path = $thumbnail; // Default to the old thumbnail
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        $new_thumbnail = uploadFile($_FILES['thumbnail']);
        if (!$new_thumbnail) {
            echo "Error uploading file.";
            exit();
        }
        // Delete the old thumbnail if a new one is uploaded
        if (!empty($thumbnail) && file_exists($thumbnail)) {
            unlink($thumbnail);
        }
        $thumbnail_path = $new_thumbnail;
    }

    // Handle inline images
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

    // Update the news item in the database
    $sql = "UPDATE news SET title='$title', introduction='$introduction', description='$description', date='$date', category_id='$category_id', status='$status', thumbnail='$thumbnail_path', content='$content', author_id='$author_id', inline_image_1='{$inline_images[1]}', inline_image_2='{$inline_images[2]}', inline_image_3='{$inline_images[3]}', final_thoughts='$final_thoughts' WHERE id='$id'";

    // Execute the query and handle success or error
    if (mysqli_query($conn, $sql)) {
        // Update tags
        mysqli_query($conn, "DELETE FROM news_tags WHERE news_id='$id'"); // Clear old tags
        foreach ($tags as $tag) {
            $tag = mysqli_real_escape_string($conn, $tag);
            mysqli_query($conn, "INSERT INTO news_tags (news_id, tag) VALUES ('$id', '$tag')"); // Insert new tags
        }

        header("Location: news.php"); // Redirect to news.php
        exit();
    } else {
        echo "<script>alert('Error updating news.');</script>";
    }

    mysqli_close($conn);
}

include "include/header.php";
?>

<div style="margin-left:25%; width:70%">
    <form action="editnews.php?id=<?php echo htmlspecialchars($id); ?>" name="newsform" onsubmit="return validateForm()" method="post" enctype="multipart/form-data">
        <h1>Update News</h1>

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" placeholder="Title..." id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required><br>
            
            <label for="description">Description</label>
            <textarea class="form-control" rows="5" id="description" name="des" placeholder="Description" required><?php echo htmlspecialchars($description); ?></textarea><br>

            <label for="introduction">Introduction</label>
            <textarea class="form-control" rows="5" id="introduction" name="introduction" placeholder="Introduction" required><?php echo htmlspecialchars($introduction); ?></textarea><br>

            <label for="content">Full Content</label>
            <textarea class="form-control" rows="10" id="content" name="content" placeholder="Full Content" required><?php echo htmlspecialchars($content); ?></textarea><br>

            <label for="final_thoughts">Final Thoughts</label>
            <textarea class="form-control" rows="5" id="final_thoughts" name="final_thoughts" placeholder="Final Thoughts" required><?php echo htmlspecialchars($final_thoughts); ?></textarea><br>
        </div>

        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" class="form-control" id="date" name="date" value="<?php echo htmlspecialchars($date); ?>" required><br>

            <label for="thumbnail">Thumbnail</label>
            <input type="file" class="form-control img-thumbnail" id="thumbnail" name="thumbnail" accept="image/*"><br>
            <?php if (!empty($thumbnail)) { ?>
                <img src="<?php echo htmlspecialchars($thumbnail); ?>" alt="Thumbnail" style="width:100px;"><br>
            <?php } ?>
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <select name="category" id="category" class="form-control" required>
            <?php
                // Fetch categories from the database
                $category_query = mysqli_query($conn, "SELECT * FROM category");
                while ($category_row = mysqli_fetch_array($category_query)) {
                    $category_id_val = $category_row['id'];
                    $category_name = $category_row['category_name'];
                    $selected = ($category_id == $category_id_val) ? "selected" : "";
                    echo "<option value='$category_id_val' $selected>$category_name</option>";
                }
            ?>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="Published" <?php echo ($status == 'Published') ? 'selected' : ''; ?>>Published</option>
                <option value="Draft" <?php echo ($status == 'Draft') ? 'selected' : ''; ?>>Draft</option>
                <option value="Archived" <?php echo ($status == 'Archived') ? 'selected' : ''; ?>>Archived</option>
            </select><br>
        </div>

        <div class="form-group">
            <label for="tags">Tags (comma separated)</label>
            <input type="text" class="form-control" name="tags" value="<?php echo implode(',', $existing_tags); ?>"><br>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Update News</button>
    </form>
</div>

<?php include "include/footer.php"; ?>
