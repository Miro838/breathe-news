<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

include "db/connection.php";

// Initialize variables and errors
$milestone = $date = $details = "";
$milestone_err = $date_err = $details_err = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $milestone = trim($_POST["milestone"]);
    $date = trim($_POST["date"]);
    $details = trim($_POST["details"]);

    // Validate input fields
    if (empty($milestone)) {
        $milestone_err = "Please enter a milestone.";
    }
    if (empty($date)) {
        $date_err = "Please enter a date.";
    }
    if (empty($details)) {
        $details_err = "Please enter details.";
    }

    // Insert new milestone into the database if no errors
    if (empty($milestone_err) && empty($date_err) && empty($details_err)) {
        $sql = "INSERT INTO milestones (milestone, date, details) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $milestone, $date, $details);

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
    <h1>Add Milestone</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-container">
        <div class="form-group">
            <label for="milestone">Milestone</label>
            <input type="text" id="milestone" name="milestone" class="form-control <?php echo (!empty($milestone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($milestone); ?>">
            <div class="invalid-feedback"><?php echo $milestone_err; ?></div>
        </div>
        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" id="date" name="date" class="form-control <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($date); ?>">
            <div class="invalid-feedback"><?php echo $date_err; ?></div>
        </div>
        <div class="form-group">
            <label for="details">Details</label>
            <textarea id="details" name="details" class="form-control <?php echo (!empty($details_err)) ? 'is-invalid' : ''; ?>"><?php echo htmlspecialchars($details); ?></textarea>
            <div class="invalid-feedback"><?php echo $details_err; ?></div>
        </div>
        <button type="submit" class="btn btn-primary">Add Milestone</button>
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
