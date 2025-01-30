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

// Initialize variables
$poll_id = null;
$poll = null;
$options = [];

// Check if an ID is provided and fetch poll details
if (isset($_GET['id'])) {
    $poll_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Fetch the current poll details
    $query = "SELECT * FROM polls WHERE id = '$poll_id'";
    $result = mysqli_query($conn, $query);
    $poll = mysqli_fetch_assoc($result);

    if (!$poll) {
        echo "Poll not found.";
        exit();
    }

    // Fetch options for the poll
    $optionsQuery = "SELECT * FROM poll_options WHERE poll_id = '$poll_id'";
    $optionsResult = mysqli_query($conn, $optionsQuery);
    while ($option = mysqli_fetch_assoc($optionsResult)) {
        $options[] = $option;
    }
}

// Handle form submission
if (isset($_POST['submit'])) {
    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Update the poll in the database
    $query = "UPDATE polls SET question='$question', status='$status', updated_at=NOW() WHERE id='$poll_id'";
    
    if (mysqli_query($conn, $query)) {
        // Update or insert options
        foreach ($_POST['option_id'] as $index => $option_id) {
            $option_text = mysqli_real_escape_string($conn, $_POST['option_text'][$index]);

            if ($option_id) {
                // Update existing option
                $updateOptionQuery = "UPDATE poll_options SET option_text='$option_text' WHERE id='$option_id'";
                mysqli_query($conn, $updateOptionQuery);
            } else {
                // Insert new option
                $insertOptionQuery = "INSERT INTO poll_options (poll_id, option_text) VALUES ('$poll_id', '$option_text')";
                mysqli_query($conn, $insertOptionQuery);
            }
        }

        // Handle deletions if needed
        if (isset($_POST['delete_option_id'])) {
            $delete_option_ids = $_POST['delete_option_id'];
            foreach ($delete_option_ids as $delete_option_id) {
                $deleteOptionQuery = "DELETE FROM poll_options WHERE id='$delete_option_id'";
                mysqli_query($conn, $deleteOptionQuery);
            }
        }

        header("Location: manage_polls.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    
    mysqli_close($conn);
}

include "include/polls.php";
?>

<div style="margin-left:20%; width:80%">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="home.php">Home</a></li>
        <li class="breadcrumb-item"><a href="manage_polls.php">Manage Polls</a></li>
        <li class="breadcrumb-item active">Edit Poll</li>
    </ul>
</div>

<div style="margin-left:25%; width:70%">
    <form action="editpoll.php?id=<?php echo htmlspecialchars($poll_id); ?>" name="pollform" onsubmit="return validateform()" method="post">
        <h1>Edit Poll</h1>

        <div class="form-group">
            <label for="question">Question</label>
            <input type="text" class="form-control" placeholder="Poll question..." id="question" name="question" value="<?php echo htmlspecialchars($poll['question']); ?>"><br>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="Active" <?php if ($poll['status'] == 'Active') echo 'selected'; ?>>Active</option>
                <option value="Inactive" <?php if ($poll['status'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
            </select>
        </div>

        <h2>Poll Options</h2>
        <div id="options-container">
            <?php foreach ($options as $index => $option): ?>
                <div class="form-group">
                    <label for="option_text_<?php echo $index; ?>">Option Text</label>
                    <input type="text" class="form-control" placeholder="Option text..." name="option_text[]" value="<?php echo htmlspecialchars($option['option_text']); ?>">
                    <input type="hidden" name="option_id[]" value="<?php echo htmlspecialchars($option['id']); ?>">
                    <input type="checkbox" name="delete_option_id[]" value="<?php echo htmlspecialchars($option['id']); ?>"> Delete
                    <br>
                </div>
            <?php endforeach; ?>
            <div class="form-group">
                <label for="new_option_text">Add New Option</label>
                <input type="text" class="form-control" placeholder="New option text..." name="option_text[]">
                <input type="hidden" name="option_id[]" value="">
                <br>
            </div>
        </div>

        <input type="submit" class="btn btn-primary" value="Update Poll" name="submit">
    </form>
</div>

<script>
function validateform() {
    var x = document.forms["pollform"]["question"].value;
    var y = document.forms["pollform"]["status"].value;
    
    if (x == "") {
        alert("Question must be filled out");
        return false;
    }
    if (y == "") {
        alert("Status must be selected");
        return false;
    }
}
</script>


