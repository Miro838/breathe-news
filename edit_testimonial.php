<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

include "db/connection.php";

// Initialize variables and errors
$id = $name = $content = "";
$name_err = $content_err = "";

// Check if ID is set in the query string
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = trim($_GET["id"]);

    // Retrieve the testimonial to edit
    $sql = "SELECT * FROM testimonials WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $name = $row["name"];
            $content = $row["content"];
        } else {
            // No record found
            echo "<script>alert('No record found.'); window.location.href='manage_testimonials.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Error: " . htmlspecialchars($stmt->error) . "');</script>";
    }
    $stmt->close();
} else {
    // ID is not valid, redirect to manage page
    header("Location: manage_testimonials.php");
    exit();
}

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

    // Update the testimonial in the database if no errors
    if (empty($name_err) && empty($content_err)) {
        $sql = "UPDATE testimonials SET name = ?, content = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $content, $id);

        if ($stmt->execute()) {
            header("Location: manage_about.php"); // Redirect after successful update
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
    <h1>Edit Testimonial</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>" method="post" class="form-container">
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
        <button type="submit" class="btn btn-primary">Update Testimonial</button>
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
