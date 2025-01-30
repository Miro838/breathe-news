<?php
session_start();

// Redirect to login if the user is not authenticated
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db/connection.php";

// Fetch the quiz item to be updated
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure ID is an integer
    $stmt = $conn->prepare("SELECT * FROM quizzes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $title = htmlspecialchars($row['title']);
        $description = htmlspecialchars($row['description']);
        $category_id = htmlspecialchars($row['category_id']);
        $difficulty = htmlspecialchars($row['difficulty']);
        $start_date = htmlspecialchars($row['start_date']);
        $end_date = htmlspecialchars($row['end_date']);
        $status = htmlspecialchars($row['status']);
    } else {
        echo "Quiz item not found.";
        exit();
    }
    $stmt->close();
} else {
    echo "Invalid request. No quiz ID provided.";
    exit();
}

// Fetch categories for dropdown
$category_query = "SELECT * FROM category";
$category_result = $conn->query($category_query);
$categories = [];
while ($row = $category_result->fetch_assoc()) {
    $categories[] = $row;
}

// Handle form submission and update the quiz item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category_id = intval($_POST['category_id']); // Ensure category_id is an integer
    $difficulty = trim($_POST['difficulty']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];

    // Validate form inputs
    if (empty($title) || empty($description) || empty($category_id) || empty($difficulty) || empty($start_date) || empty($end_date) || empty($status)) {
        echo "<script>alert('All fields are required.');</script>";
    } elseif (strlen($description) < 10) {
        echo "<script>alert('Description must be at least 10 characters.');</script>";
    } else {
        // Prepare the SQL query
        $stmt = $conn->prepare("UPDATE quizzes SET title = ?, description = ?, category_id = ?, difficulty = ?, start_date = ?, end_date = ?, status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("ssissssi", $title, $description, $category_id, $difficulty, $start_date, $end_date, $status, $id);

        // Execute the query and handle success or error
        if ($stmt->execute()) {
            header("Location: create_quiz.php"); // Redirect to manage_quizzes.php
            exit();
        } else {
            echo "<script>alert('Error updating quiz.');</script>";
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$conn->close();

include "include/quiz_header.php";
?>

<div style="margin-left:20%;width:80%">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="home.php">Home</a></li>
        <li class="breadcrumb-item"><a href="manage_quizzes.php">Quizzes</a></li>
        <li class="breadcrumb-item active">Edit Quiz</li>
    </ul>
</div>

<div style="margin-left:25%; width:70%">
    <form action="" name="quizform" onsubmit="return validateForm()" method="post">
        <h1>Edit Quiz</h1>

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" placeholder="Title..." id="title" name="title" value="<?php echo htmlspecialchars($title); ?>"><br>
            
            <label for="description">Description</label>
            <textarea class="form-control" rows="5" id="description" name="description" placeholder="Description"><?php echo htmlspecialchars($description); ?></textarea><br>
        </div>

        <div class="form-group">
            <label for="category_id">Category</label>
            <select class="form-control" id="category_id" name="category_id">
                <option value="">Select Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $category_id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['category_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br>

            <label for="difficulty">Difficulty</label>
            <select class="form-control" id="difficulty" name="difficulty">
                <option value="">Select Difficulty</option>
                <option value="easy" <?php echo ($difficulty == 'easy') ? 'selected' : ''; ?>>Easy</option>
                <option value="medium" <?php echo ($difficulty == 'medium') ? 'selected' : ''; ?>>Medium</option>
                <option value="hard" <?php echo ($difficulty == 'hard') ? 'selected' : ''; ?>>Hard</option>
            </select><br>

            <label for="start_date">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>"><br>

            <label for="end_date">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>"><br>

            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="">Select Status</option>
                <option value="active" <?php echo ($status == 'active') ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo ($status == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Update Quiz" name="submit">
            <a href="create_quiz.php" class="btn btn-secondary">Back to Quizzes List</a>
        </div>
    </form>
</div>

<script>
function validateForm() {
    var title = document.forms["quizform"]["title"].value;
    var description = document.forms["quizform"]["description"].value;
    var category_id = document.forms["quizform"]["category_id"].value;
    var difficulty = document.forms["quizform"]["difficulty"].value;
    var start_date = document.forms["quizform"]["start_date"].value;
    var end_date = document.forms["quizform"]["end_date"].value;
    var status = document.forms["quizform"]["status"].value;

    if (title == "") {
        alert("Title must be filled out");
        return false;
    }
    if (description.length < 10) {
        alert("Description must be at least 10 characters");
        return false;
    }
    if (category_id == "") {
        alert("Category must be selected");
        return false;
    }
    if (difficulty == "") {
        alert("Difficulty must be selected");
        return false;
    }
    if (start_date == "") {
        alert("Start Date must be filled out");
        return false;
    }
    if (end_date == "") {
        alert("End Date must be filled out");
        return false;
    }
    if (status == "") {
        alert("Status must be selected");
        return false;
    }

    return true;
}
</script>
