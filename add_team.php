<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

include "db/connection.php";

// Initialize variables and errors
$name = $position = $bio = $profile_image_url = $social_links = "";
$name_err = $position_err = $bio_err = $profile_image_err = $social_links_err = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $position = trim($_POST["position"]);
    $bio = trim($_POST["bio"]);
    $profile_image_url = trim($_POST["profile_image_url"]);
    $social_links = trim($_POST["social_links"]);

    // Validate input fields
    if (empty($name)) {
        $name_err = "Please enter the name.";
    }
    if (empty($position)) {
        $position_err = "Please enter the position.";
    }
    if (empty($bio)) {
        $bio_err = "Please enter a bio.";
    }
    if (empty($profile_image_url)) {
        $profile_image_err = "Please enter the profile image URL.";
    }
    if (empty($social_links)) {
        $social_links_err = "Please enter the social links.";
    }

    // Insert new team member into the database if no errors
    if (empty($name_err) && empty($position_err) && empty($bio_err) && empty($profile_image_err) && empty($social_links_err)) {
        $sql = "INSERT INTO team (name, position, bio, profile_image_url, social_links, joined_date) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $position, $bio, $profile_image_url, $social_links);

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
    <h1>Add Team Member</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-container">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($name); ?>">
            <div class="invalid-feedback"><?php echo $name_err; ?></div>
        </div>
        <div class="form-group">
            <label for="position">Position</label>
            <input type="text" id="position" name="position" class="form-control <?php echo (!empty($position_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($position); ?>">
            <div class="invalid-feedback"><?php echo $position_err; ?></div>
        </div>
        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" class="form-control <?php echo (!empty($bio_err)) ? 'is-invalid' : ''; ?>"><?php echo htmlspecialchars($bio); ?></textarea>
            <div class="invalid-feedback"><?php echo $bio_err; ?></div>
        </div>
        <div class="form-group">
            <label for="profile_image_url">Profile Image URL</label>
            <input type="text" id="profile_image_url" name="profile_image_url" class="form-control <?php echo (!empty($profile_image_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($profile_image_url); ?>">
            <div class="invalid-feedback"><?php echo $profile_image_err; ?></div>
        </div>
        <div class="form-group">
            <label for="social_links">Social Links</label>
            <input type="text" id="social_links" name="social_links" class="form-control <?php echo (!empty($social_links_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($social_links); ?>">
            <div class="invalid-feedback"><?php echo $social_links_err; ?></div>
        </div>
        <button type="submit" class="btn btn-primary">Add Team Member</button>
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
