<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

include "db/connection.php";

// Initialize variables and errors
$title = $content = "";
$title_err = $content_err = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);

    // Validate input fields
    if (empty($title)) {
        $title_err = "Please enter a title.";
    }
    if (empty($content)) {
        $content_err = "Please enter content.";
    }

    // Insert new website info into the database if no errors
    if (empty($title_err) && empty($content_err)) {
        $sql = "INSERT INTO website_info (title, content, created_at, updated_at) VALUES (?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $title, $content);

        if ($stmt->execute()) {
            header("Location: manage_about.php"); // Redirect after successful submission
            exit();
        } else {
            echo "<script>alert('Error: " . htmlspecialchars($stmt->error) . "');</script>";
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<?php include "include/header.php"; ?>
<div style="margin-left:20%; width:80%">
    <h1>Add Website Info</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-container">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($title); ?>">
            <div class="invalid-feedback"><?php echo $title_err; ?></div>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea id="content" name="content" class="form-control <?php echo (!empty($content_err)) ? 'is-invalid' : ''; ?>"><?php echo htmlspecialchars($content); ?></textarea>
            <div class="invalid-feedback"><?php echo $content_err; ?></div>
        </div>
        <button type="submit" class="btn btn-primary">Add Website Info</button>
    </form>
</div>


<style>
    .form-container {
        padding: 25px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
    }
    .form-control {
        margin-bottom: 20px;
        font-size: 1.1em;
    }
    .btn-primary {
        font-size: 1.1em;
        padding: 10px 20px;
    }
</style>
