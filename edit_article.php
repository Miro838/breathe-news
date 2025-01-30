<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

include "db/connection.php";

// Fetch categories from the database
$categories = [];
$sql = "SELECT category_name FROM category";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['category_name'];
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $title = $_POST['title'];
    $item_id = $_POST['item_id'];
    $description = $_POST['description'];
    $content = $_POST['content'];
    $final_thoughts = $_POST['final_thoughts'];
    $video_title = $_POST['video_title'];
    $video_description = $_POST['video_description'];
    $author_id = $_SESSION['user_id'];
    $publish_date = date('Y-m-d H:i:s');
    $category = $_POST['category'];

    // Function to handle file upload
    function uploadFile($file, $target_dir) {
        if ($file['name']) { // Check if file was uploaded
            $target_file = $target_dir . basename($file['name']);
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
            }
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                return $file['name'];
            } else {
                die("Failed to upload " . $file['name'] . ". Check the directory permissions.");
            }
        }
        return null;
    }

    // Directories for file uploads
    $thumbnail_target_dir = 'uploads/images/';
    $video_target_dir = 'uploads/videos/';
    $inline_image_dir = 'uploads/inline_images/';

    // Upload files or retrieve existing files if not uploaded
    $thumbnail = uploadFile($_FILES['thumbnail'], $thumbnail_target_dir) ?: getExistingFile('thumbnail', $id);
    $video = uploadFile($_FILES['video'], $video_target_dir) ?: getExistingFile('video', $id);
    $inline_image_1 = uploadFile($_FILES['inline_image_1'], $inline_image_dir) ?: getExistingFile('inline_image_1', $id);
    $inline_image_2 = uploadFile($_FILES['inline_image_2'], $inline_image_dir) ?: getExistingFile('inline_image_2', $id);
    $inline_image_3 = uploadFile($_FILES['inline_image_3'], $inline_image_dir) ?: getExistingFile('inline_image_3', $id);

    // Update article in the database
    $sql = "UPDATE articles SET title = ?, item_id = ?, description = ?, content = ?, final_thoughts = ?, 
            video_title = ?, video_description = ?, author_id = ?, publish_date = ?, 
            category_name = ?, thumbnail = ?, inline_image_1 = ?, inline_image_2 = ?, 
            inline_image_3 = ?, video = ?  
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sisssssssssssssi", 
        $title, 
        $item_id, 
        $description, 
        $content, 
        $final_thoughts, 
        $video_title, 
        $video_description, 
        $author_id, 
        $publish_date, 
        $category, 
        $thumbnail, 
        $inline_image_1, 
        $inline_image_2, 
        $inline_image_3, 
        $video, 
        $id
    );

    if ($stmt->execute()) {
        header("Location: articles.php");
        exit();
    } else {
        echo "Error updating article: " . $conn->error;
    }

    $stmt->close();
}

// Function to retrieve existing file if not uploaded again
function getExistingFile($field, $id) {
    global $conn;
    $sql = "SELECT $field FROM articles WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $existing_article = $result->fetch_assoc();
    $stmt->close();
    return $existing_article[$field];
}

// Retrieve article ID from query string
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch existing article details
$sql = "SELECT * FROM articles WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $article = $result->fetch_assoc();
} else {
    echo "Article not found.";
    exit();
}

$stmt->close();
$conn->close();
?>

       

<?php include "include/header.php"; ?>

<div style="margin-left:20%; width:80%">
    <h2>Edit Article</h2>
    <form action="edit_article.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($article['id']); ?>">
        
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($article['title']); ?>" required>
        </div>

        <div class="form-group">
            <label for="item_id">Item ID</label>
            <input type="text" id="item_id" name="item_id" class="form-control" value="<?php echo htmlspecialchars($article['item_id']); ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="3" required><?php echo htmlspecialchars($article['description']); ?></textarea>
        </div>
          <!-- #region-->
        <div class="form-group">
            <label for="content">Content</label>
            <textarea id="content" name="content" class="form-control" rows="5" required><?php echo htmlspecialchars($article['content']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="final_thoughts">Final Thoughts</label>
            <textarea id="final_thoughts" name="final_thoughts" class="form-control" rows="3" required><?php echo htmlspecialchars($article['final_thoughts']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="video_title">Video Title</label>
            <input type="text" id="video_title" name="video_title" class="form-control" value="<?php echo htmlspecialchars($article['video_title']); ?>">
        </div>

        <div class="form-group">
            <label for="video_description">Video Description</label>
            <textarea id="video_description" name="video_description" class="form-control" rows="3"><?php echo htmlspecialchars($article['video_description']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="publish_date">Publish Date</label>
            <input type="date" id="publish_date" name="publish_date" class="form-control" value="<?php echo htmlspecialchars($article['publish_date']); ?>" required>
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category" class="form-control" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo ($cat == $article['category_name']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        

        <div class="form-group">
            <label for="thumbnail">Thumbnail</label>
            <input type="file" id="thumbnail" name="thumbnail" class="form-control">
            <img src="uploads/images/<?php echo htmlspecialchars($article['thumbnail']); ?>" alt="Thumbnail" style="max-width: 200px;">
        </div>

        <div class="form-group">
            <label for="video">Video</label>
            <input type="file" id="video" name="video" class="form-control">
            <video controls style="max-width: 200px;">
                <source src="uploads/videos/<?php echo htmlspecialchars($article['video']); ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>

        <div class="form-group">
            <label for="inline_image_1">Inline Image 1</label>
            <input type="file" id="inline_image_1" name="inline_image_1" class="form-control">
            <img src="uploads/inline_images/<?php echo htmlspecialchars($article['inline_image_1']); ?>" alt="Inline Image 1" style="max-width: 200px;">
        </div>

        <div class="form-group">
            <label for="inline_image_2">Inline Image 2</label>
            <input type="file" id="inline_image_2" name="inline_image_2" class="form-control">
            <img src="uploads/inline_images/<?php echo htmlspecialchars($article['inline_image_2']); ?>" alt="Inline Image 2" style="max-width: 200px;">
        </div>

        <div class="form-group">
            <label for="inline_image_3">Inline Image 3</label>
            <input type="file" id="inline_image_3" name="inline_image_3" class="form-control">
            <img src="uploads/inline_images/<?php echo htmlspecialchars($article['inline_image_3']); ?>" alt="Inline Image 3" style="max-width: 200px;">
        </div>

        <button type="submit" class="btn btn-primary">Update Article</button>
    </form>
</div>

