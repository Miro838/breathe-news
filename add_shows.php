<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db/connection.php";

// Initialize variables and error messages
$title = $description = $category_id = $status = $thumbnail = $video = $publish_date = $rating = $why_is_popular = $why_viewers_love = "";
$errors = [
    'title' => '',
    'description' => '',
    'category_id' => '',
    'status' => '',
    'thumbnail' => '',
    'video' => '',
    'publish_date' => '',
    'rating' => '',
    'why_is_popular' => '',
    'why_viewers_love' => '',
];

// Fetch categories from the database
$category_options = "";
$sql = "SELECT id, category_name FROM category";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $selected = ($category_id == $row['id']) ? 'selected' : '';
        $category_options .= "<option value='" . htmlspecialchars($row['id']) . "' $selected>" . htmlspecialchars($row['category_name']) . "</option>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate title
    $title = trim($_POST["title"]);
    if (empty($title)) {
        $errors['title'] = "Please enter a title.";
    }

    // Validate description
    $description = trim($_POST["description"]);
    if (empty($description)) {
        $errors['description'] = "Please enter a description.";
    } elseif (strlen($description) < 10) {
        $errors['description'] = "Description must be at least 10 characters.";
    }

    // Validate category
    $category_id = trim($_POST["category_id"]);
    if (empty($category_id)) {
        $errors['category_id'] = "Please select a category.";
    }

    // Validate rating
    $rating = trim($_POST["rating"]);
    if (!is_numeric($rating) || $rating < 0 || $rating > 10) {
        $errors['rating'] = "Please provide a valid rating between 0 and 10.";
    }

    // Validate status
    $status = trim($_POST["status"]);
    if (empty($status)) {
        $errors['status'] = "Please select a status.";
    }

    // Validate publish date
    $publish_date = trim($_POST["publish_date"]);
    if (empty($publish_date)) {
        $errors['publish_date'] = "Please select a publish date.";
    }

    // Validate why is popular
    $why_is_popular = trim($_POST["why_is_popular"]);
    if (empty($why_is_popular)) {
        $errors['why_is_popular'] = "Please provide a reason for why the show is popular.";
    }

    // Validate why viewers love
    $why_viewers_love = trim($_POST["why_viewers_love"]);
    if (empty($why_viewers_love)) {
        $errors['why_viewers_love'] = "Please provide a reason why viewers love the show.";
    }

    // Handle thumbnail upload
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/thumbnails/';
        $thumbnail = basename($_FILES['thumbnail']['name']);
        $thumbnail_target_file = $upload_dir . $thumbnail;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $thumbnail_file_type = strtolower(pathinfo($thumbnail_target_file, PATHINFO_EXTENSION));
        $allowed_image_types = ["jpg", "jpeg", "png", "jfif"];
        if (!in_array($thumbnail_file_type, $allowed_image_types)) {
            $errors['thumbnail'] = "Only JPG, JPEG, PNG, and JFIF files are allowed.";
        } elseif (!move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnail_target_file)) {
            $errors['thumbnail'] = "Failed to upload thumbnail.";
        }
    }

    // Handle video upload
    if (isset($_FILES['video']) && $_FILES['video']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/videos/';
        $video = basename($_FILES['video']['name']);
        $video_target_file = $upload_dir . $video;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $video_file_type = strtolower(pathinfo($video_target_file, PATHINFO_EXTENSION));
        $allowed_video_types = ["mp4", "avi", "mov"];
        if (!in_array($video_file_type, $allowed_video_types)) {
            $errors['video'] = "Only MP4, AVI, and MOV files are allowed.";
        } elseif (!move_uploaded_file($_FILES['video']['tmp_name'], $video_target_file)) {
            $errors['video'] = "Failed to upload video.";
        }
    }

    // Insert into database if no errors
    if (!array_filter($errors)) {
        // Prepare an SQL statement
        $stmt = $conn->prepare("INSERT INTO top_shows (title, description, thumbnail, category_id, status, video, publish_date, why_is_popular, why_viewers_love) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if ($stmt === false) {
            // Output the error if the prepare statement failed
            die("Error preparing the SQL statement: " . htmlspecialchars($conn->error));
        }

        // Bind parameters
        $stmt->bind_param("sssssssss", $title, $description, $thumbnail, $category_id, $status, $video, $publish_date, $why_is_popular, $why_viewers_love);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('Show added successfully!');window.location.href='manage_shows.php';</script>";
        } else {
            echo "<script>alert('Error: " . htmlspecialchars($stmt->error) . "');</script>";
        }
        // Close the statement
        $stmt->close();
    }

    $conn->close();
}
?>

<?php include "include/header.php"; ?>

<div style="margin-left:20%;width:80%">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="admin_main_dashboard.php">Home</a></li>
        <li class="breadcrumb-item"><a href="manage_shows.php">Shows</a></li>
        <li class="breadcrumb-item active">Add Show</li>
    </ul>
</div>

<div style="margin-left:25%; width:70%">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" name="showform" onsubmit="return validateForm()">
        <h1>Add Show</h1>

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control <?php echo (!empty($errors['title'])) ? 'is-invalid' : ''; ?>" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>">
            <span class="invalid-feedback"><?php echo $errors['title']; ?></span>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control <?php echo (!empty($errors['description'])) ? 'is-invalid' : ''; ?>" id="description" name="description"><?php echo htmlspecialchars($description); ?></textarea>
            <span class="invalid-feedback"><?php echo $errors['description']; ?></span>
        </div>

        <div class="form-group">
            <label for="category_id">Category</label>
            <select class="form-control <?php echo (!empty($errors['category_id'])) ? 'is-invalid' : ''; ?>" id="category_id" name="category_id">
                <option value="">Select Category</option>
                <?php echo $category_options; ?>
            </select>
            <span class="invalid-feedback"><?php echo $errors['category_id']; ?></span>
        </div>

        <div class="form-group">
            <label for="rating">Rating</label>
            <input type="number" class="form-control <?php echo (!empty($errors['rating'])) ? 'is-invalid' : ''; ?>" id="rating" name="rating" step="0.1" min="0" max="10" value="<?php echo htmlspecialchars($rating); ?>">
            <span class="invalid-feedback"><?php echo $errors['rating']; ?></span>
        </div>

        <div class="form-group">
            <label for="publish_date">Publish Date</label>
            <input type="date" class="form-control <?php echo (!empty($errors['publish_date'])) ? 'is-invalid' : ''; ?>" id="publish_date" name="publish_date" value="<?php echo htmlspecialchars($publish_date); ?>" required>
            <span class="invalid-feedback"><?php echo $errors['publish_date']; ?></span>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control <?php echo (!empty($errors['status'])) ? 'is-invalid' : ''; ?>" id="status" name="status">
                <option value="">Select Status</option>
                <option value="active" <?php echo ($status == 'active') ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo ($status == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
            </select>
            <span class="invalid-feedback"><?php echo $errors['status']; ?></span>
        </div>

        <div class="form-group">
            <label for="thumbnail">Thumbnail</label>
            <input type="file" class="form-control <?php echo (!empty($errors['thumbnail'])) ? 'is-invalid' : ''; ?>" id="thumbnail" name="thumbnail">
            <span class="invalid-feedback"><?php echo $errors['thumbnail']; ?></span>
        </div>

        <div class="form-group">
            <label for="video">Video</label>
            <input type="file" class="form-control <?php echo (!empty($errors['video'])) ? 'is-invalid' : ''; ?>" id="video" name="video">
            <span class="invalid-feedback"><?php echo $errors['video']; ?></span>
        </div>

        <div class="form-group">
            <label for="why_is_popular">Why is this show popular?</label>
            <textarea class="form-control <?php echo (!empty($errors['why_is_popular'])) ? 'is-invalid' : ''; ?>" id="why_is_popular" name="why_is_popular"><?php echo htmlspecialchars($why_is_popular); ?></textarea>
            <span class="invalid-feedback"><?php echo $errors['why_is_popular']; ?></span>
        </div>

        <div class="form-group">
            <label for="why_viewers_love">Why do viewers love this show?</label>
            <textarea class="form-control <?php echo (!empty($errors['why_viewers_love'])) ? 'is-invalid' : ''; ?>" id="why_viewers_love" name="why_viewers_love"><?php echo htmlspecialchars($why_viewers_love); ?></textarea>
            <span class="invalid-feedback"><?php echo $errors['why_viewers_love']; ?></span>
        </div>

        <button type="submit" class="btn btn-primary">Add Show</button>
    </form>
</div>

<script>
// Validate form before submission
function validateForm() {
    let isValid = true;

    const requiredFields = ['title', 'description', 'category_id', 'status', 'thumbnail', 'video', 'publish_date', 'why_is_popular', 'why_viewers_love'];
    requiredFields.forEach(field => {
        const fieldElement = document.getElementById(field);
        if (fieldElement && fieldElement.value === '') {
            isValid = false;
            fieldElement.classList.add('is-invalid');
        } else {
            fieldElement.classList.remove('is-invalid');
        }
    });

    return isValid;
}
</script>


