<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

include "db/connection.php";

// Fetch categories from the database
$categories = [];
$sql = "SELECT id, category_name FROM category";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Initialize variables and error messages
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$title = $description = $introduction = $content = $final_thoughts = $video_title = $video_description = $category_id = $thumbnail = $video = $item_id = "";
$inline_images = ['', '', ''];
$errors = [];
$publish_date = date('Y-m-d H:i:s'); // Default publish date

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $introduction = trim($_POST["introduction"]);
    $content = trim($_POST["content"]);
    $final_thoughts = trim($_POST["final_thoughts"]);
    $video_title = trim($_POST["video_title"]);
    $video_description = trim($_POST["video_description"]);
    $category_id = trim($_POST["category_id"]);
    $item_id = trim($_POST["item_id"]);

    // Required fields validation
    if (empty($title)) $errors['title'] = "Please enter a title.";
    if (empty($description)) $errors['description'] = "Please enter a description.";
    if (empty($introduction)) $errors['introduction'] = "Please enter an introduction.";
    if (empty($content)) $errors['content'] = "Please enter content.";
    if (empty($final_thoughts)) $errors['final_thoughts'] = "Please enter final thoughts.";
    if (empty($video_title)) $errors['video_title'] = "Please enter a video title.";
    if (empty($category_id)) $errors['category'] = "Please select a category.";
    if (empty($item_id)) $errors['item_id'] = "Please enter an item ID.";

    // File upload handling
    function handleFileUpload($fileInputName, $targetDir, $allowedTypes) {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] == 0) {
            $fileName = basename($_FILES[$fileInputName]['name']);
            $targetFilePath = $targetDir . $fileName;
            if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            if (!in_array($fileType, $allowedTypes)) {
                return ["error" => "Only " . implode(", ", $allowedTypes) . " files are allowed."];
            } else {
                if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetFilePath)) {
                    return $fileName;
                } else {
                    return ["error" => "Failed to upload $fileInputName."];
                }
            }
        }
        return ''; // No file uploaded
    }

    // Upload inline images
    for ($i = 1; $i <= 3; $i++) {
        $result = handleFileUpload("inline_image_$i", "uploads/images/", ["jpg", "jpeg", "png"]);
        if (is_array($result) && isset($result['error'])) {
            $errors["inline_image_$i"] = $result['error'];
        } else {
            $inline_images[$i - 1] = $result;
        }
    }

    // Handle thumbnail and video uploads
    $thumbnail = handleFileUpload('thumbnail', 'uploads/images/', ['jpg', 'jpeg', 'png']);
    if (is_array($thumbnail) && isset($thumbnail['error'])) $errors['thumbnail'] = $thumbnail['error'];

    $video = handleFileUpload('video', 'uploads/videos/', ['mp4', 'avi', 'mov']);
    if (is_array($video) && isset($video['error'])) $errors['video'] = $video['error'];

    // Insert or update article in the database if no errors
    if (empty($errors)) {
        if ($id > 0) {
            // Update existing article
            $sql = "UPDATE articles SET title = ?, description = ?, introduction = ?, content = ?, final_thoughts = ?, video_title = ?, video_description = ?, publish_date = ?, category_name = ?, thumbnail = ?, inline_image_1 = ?, inline_image_2 = ?, inline_image_3 = ?, video = ?, item_id = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssssssssi", $title, $description, $introduction, $content, $final_thoughts, $video_title, $video_description, $publish_date, $category_id, $thumbnail, $inline_images[0], $inline_images[1], $inline_images[2], $video, $item_id, $id);
        } else {
            // Insert new article
            $author_id = $_SESSION["user_id"];
            $sql = "INSERT INTO articles (title, description, introduction, content, final_thoughts, video_title, video_description, author_id, publish_date, category_name, thumbnail, inline_image_1, inline_image_2, inline_image_3, video, item_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssiissssssss", $title, $description, $introduction, $content, $final_thoughts, $video_title, $video_description, $author_id, $publish_date, $category_id, $thumbnail, $inline_images[0], $inline_images[1], $inline_images[2], $video, $item_id);
        }

        if ($stmt->execute()) {
            header("Location: articles.php");
            exit();
        } else {
            echo "<script>alert('Error: " . htmlspecialchars($stmt->error) . "');</script>";
        }

        $stmt->close();
    }

    $conn->close();
}

// Fetch existing article details if editing
if ($id > 0) {
    $sql = "SELECT * FROM articles WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $article = $result->fetch_assoc();
        // Populate variables with article data
        $title = $article['title'];
        $description = $article['description'];
        $introduction = $article['introduction'];
        $content = $article['content'];
        $final_thoughts = $article['final_thoughts'];
        $video_title = $article['video_title'];
        $video_description = $article['video_description'];
        $publish_date = $article['publish_date'];
        $category_id = $article['category_name'];
        $thumbnail = $article['thumbnail'];
        $video = $article['video'];
        $item_id = $article['item_id'];
        $inline_images[0] = $article['inline_image_1'];
        $inline_images[1] = $article['inline_image_2'];
        $inline_images[2] = $article['inline_image_3'];
    } else {
        echo "Article not found.";
        exit();
    }
    $stmt->close();
    $conn->close();
}
?>

<?php include "include/header.php"; ?>
<div style="margin-left:20%; width:80%">
    <h1><?php echo $id > 0 ? "Edit Article" : "Add Article"; ?></h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=$id"; ?>" method="post" enctype="multipart/form-data">
        <!-- Form fields for title, description, introduction, content, and final thoughts -->
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
            <span class="error"><?php echo $errors['title'] ?? ''; ?></span>

            <label for="description">Description</label>
            <textarea class="form-control" rows="5" id="description" name="description" required><?php echo htmlspecialchars($description); ?></textarea>
            <span class="error"><?php echo $errors['description'] ?? ''; ?></span>

            <label for="introduction">Introduction</label>
            <textarea class="form-control" rows="5" id="introduction" name="introduction" required><?php echo htmlspecialchars($introduction); ?></textarea>
            <span class="error"><?php echo $errors['introduction'] ?? ''; ?></span>

           

            <label for="content">Content</label>
            <textarea class="form-control" rows="10" id="content" name="content" required><?php echo htmlspecialchars($content); ?></textarea>
            <span class="error"><?php echo $errors['content'] ?? ''; ?></span>

            <label for="final_thoughts">Final Thoughts</label>
            <textarea class="form-control" rows="5" id="final_thoughts" name="final_thoughts" required><?php echo htmlspecialchars($final_thoughts); ?></textarea>
            <span class="error"><?php echo $errors['final_thoughts'] ?? ''; ?></span>
        </div>

        <div class="form-group">
            <label for="video_title">Video Title</label>
            <input type="text" class="form-control" id="video_title" name="video_title" value="<?php echo htmlspecialchars($video_title); ?>" required>
            <span class="error"><?php echo $errors['video_title'] ?? ''; ?></span>

            <label for="video_description">Video Description</label>
            <textarea class="form-control" rows="5" id="video_description" name="video_description" required><?php echo htmlspecialchars($video_description); ?></textarea>

            <label for="item_id">Item ID</label>
            <input type="text" class="form-control" id="item_id" name="item_id" value="<?php echo htmlspecialchars($item_id); ?>" required>
            <span class="error"><?php echo $errors['item_id'] ?? ''; ?></span>

            <label for="category_id">Category</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo $category['category_name']; ?>" <?php echo ($category['category_name'] == $category_id) ? "selected" : ""; ?>>
                        <?php echo htmlspecialchars($category['category_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="error"><?php echo $errors['category'] ?? ''; ?></span>
        </div>

        <div class="form-group">
            <label for="thumbnail">Thumbnail Image</label>
            <input type="file" class="form-control" id="thumbnail" name="thumbnail">
            <span class="error"><?php echo $errors['thumbnail'] ?? ''; ?></span>

            <label for="inline_image_1">Inline Image 1</label>
            <input type="file" class="form-control" id="inline_image_1" name="inline_image_1">
            <span class="error"><?php echo $errors['inline_image_1'] ?? ''; ?></span>

            <label for="inline_image_2">Inline Image 2</label>
            <input type="file" class="form-control" id="inline_image_2" name="inline_image_2">
            <span class="error"><?php echo $errors['inline_image_2'] ?? ''; ?></span>

            <label for="inline_image_3">Inline Image 3</label>
            <input type="file" class="form-control" id="inline_image_3" name="inline_image_3">
            <span class="error"><?php echo $errors['inline_image_3'] ?? ''; ?></span>

            <label for="video">Video File</label>
            <input type="file" class="form-control" id="video" name="video">
            <span class="error"><?php echo $errors['video'] ?? ''; ?></span>
        </div>

        <button type="submit" class="btn btn-primary">Add Article</button>
    </form>
</div>
