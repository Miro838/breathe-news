<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

include "db/connection.php";

// Initialize variables and errors
$name = $content = "";
$name_err = $content_err = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $content = trim($_POST["content"]);

    // Validate input fields
    if (empty($name)) {
        $name_err = "Please enter the name.";
    }
    if (empty($content)) {
        $content_err = "Please enter the testimonial content.";
    }

    // Insert new testimonial into the database if no errors
    if (empty($name_err) && empty($content_err)) {
        $sql = "INSERT INTO testimonials (name, content, date, approved) VALUES (?, ?, NOW(), 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $name, $content);

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
    <h1>Add Testimonial</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-container">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($name); ?>">
            <div class="invalid-feedback"><?php echo $name_err; ?></div>
        </div>
        <div class="form-group">
            <label for="content">Testimonial Content</label>
            <textarea id="content" name="content" class="form-control <?php echo (!empty($content_err)) ? 'is-invalid' : ''; ?>"><?php echo htmlspecialchars($content); ?></textarea>
            <div class="invalid-feedback"><?php echo $content_err; ?></div>
        </div>
        <button type="submit" class="btn btn-primary">Add Testimonial</button>
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
