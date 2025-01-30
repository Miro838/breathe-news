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

if (isset($_POST['submit'])) {
    // Fetch form data and sanitize inputs
    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $options = $_POST['options']; // Array of options

    // Insert poll into the database
    $query = "INSERT INTO polls (question, status) VALUES ('$question', '$status')";

    if (mysqli_query($conn, $query)) {
        // Get the last inserted poll ID
        $poll_id = mysqli_insert_id($conn);

        // Insert options into poll_options table
        foreach ($options as $option) {
            $option_text = mysqli_real_escape_string($conn, $option);
            $option_query = "INSERT INTO poll_options (poll_id, option_text) VALUES ('$poll_id', '$option_text')";
            if (!mysqli_query($conn, $option_query)) {
                echo "Error: " . $option_query . "<br>" . mysqli_error($conn);
            }
        }

        // Redirect to manage polls page after successful insertion
        header("Location: manage_polls.php");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}

include "include/polls.php";
?>

<div style="margin-left:20%; width:80%">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="home.php">Home</a></li>
        <li class="breadcrumb-item"><a href="managepolls.php">Manage Polls</a></li>
        <li class="breadcrumb-item active">Add Poll</li>
    </ul>
</div>

<div style="margin-left:25%; width:70%">
    <form action="addpoll.php" name="pollform" onsubmit="return validateform()" method="post">
        <h1>Add Poll</h1>

        <div class="form-group">
            Question
            <input type="text" class="form-control" placeholder="Poll question..." id="question" name="question"><br>
        </div>

        <div class="form-group">
            Status
            <select name="status" id="status" class="form-control">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>

        <div class="form-group">
            <label for="options[]">Options</label>
            <input type="text" class="form-control mb-2" name="options[]" placeholder="Option 1">
            <input type="text" class="form-control mb-2" name="options[]" placeholder="Option 2">
            <input type="text" class="form-control mb-2" name="options[]" placeholder="Option 3">
            <input type="text" class="form-control mb-2" name="options[]" placeholder="Option 4">
            <!-- Add more input fields as needed -->
        </div>

        <input type="submit" class="btn btn-primary" value="Add Poll" name="submit">
    </form>
</div>

<script>
function validateform() {
    var question = document.forms["pollform"]["question"].value;
    var status = document.forms["pollform"]["status"].value;
    var options = document.getElementsByName("options[]");

    if (question == "") {
        alert("Question must be filled out");
        return false;
    }
    if (status == "") {
        alert("Status must be selected");
        return false;
    }

    var filledOptions = 0;
    for (var i = 0; i < options.length; i++) {
        if (options[i].value != "") {
            filledOptions++;
        }
    }

    if (filledOptions < 2) {
        alert("At least two options must be provided");
        return false;
    }
}
</script>


