<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db/connection.php";

// Fetch categories from the database
$category_query = "SELECT id, category_name FROM category";
$category_result = $conn->query($category_query);

if ($category_result === false) {
    die("Error fetching categories: " . htmlspecialchars($conn->error));
}

// Initialize variables and error messages
$title = $description = $category_id = $difficulty = $start_date = $end_date = $status = "";
$errors = array(
    'title' => '',
    'description' => '',
    'category_id' => '',
    'difficulty' => '',
    'start_date' => '',
    'end_date' => '',
    'status' => ''
);

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

    // Validate category_id
    $category_id = trim($_POST["category_id"]);
    if (empty($category_id)) {
        $errors['category_id'] = "Please enter a category ID.";
    } elseif (!is_numeric($category_id)) {
        $errors['category_id'] = "Category ID must be a number.";
    }

    // Validate difficulty
    $difficulty = trim($_POST["difficulty"]);
    if (empty($difficulty)) {
        $errors['difficulty'] = "Please select difficulty.";
    }

    // Validate start_date
    $start_date = trim($_POST["start_date"]);
    if (empty($start_date)) {
        $errors['start_date'] = "Please select a start date.";
    }

    // Validate end_date
    $end_date = trim($_POST["end_date"]);
    if (empty($end_date)) {
        $errors['end_date'] = "Please select an end date.";
    }

    // Validate status
    $status = trim($_POST["status"]);
    if (empty($status)) {
        $errors['status'] = "Please select a status.";
    }

    // Check if there are no errors before inserting into database
    if (!array_filter($errors)) {
        // Prepare an SQL statement
        $stmt = $conn->prepare("INSERT INTO quizzes (title, description, category_id, difficulty, start_date, end_date, status) VALUES (?, ?, ?, ?, ?, ?, ?)");

        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }

        // Bind parameters
        $stmt->bind_param("ssissss", $title, $description, $category_id, $difficulty, $start_date, $end_date, $status);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to quiz dashboard after successful submission
            header("Location: create_quiz.php");
            exit();
        } else {
            echo "<script>alert('Error: " . htmlspecialchars($stmt->error) . "');</script>";
        }

        // Close the statement
        $stmt->close();
    }

    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Quiz</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body> <?php include 'include/quiz_header.php';?>
    <div style="margin-left:20%; width:80%">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
            <li class="breadcrumb-item"><a href="quizzes.php">Quizzes</a></li>
            <li class="breadcrumb-item active">Add Quiz</li>
        </ul>
    </div>
    
    <div style="margin-left:25%; width:70%">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" name="quizform" onsubmit="return validateForm()">
            <h1>Add Quiz</h1>

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
                    <?php
                    // Loop through the categories and create options
                    while ($category = $category_result->fetch_assoc()) {
                        echo "<option value='" . $category['id'] . "' " . (($category_id == $category['id']) ? 'selected' : '') . ">" . htmlspecialchars($category['category_name']) . "</option>";
                    }
                    ?>
                </select>
                <span class="invalid-feedback"><?php echo $errors['category_id']; ?></span>
            </div>

            <div class="form-group">
                <label for="difficulty">Difficulty</label>
                <select class="form-control <?php echo (!empty($errors['difficulty'])) ? 'is-invalid' : ''; ?>" id="difficulty" name="difficulty">
                    <option value="">Select Difficulty</option>
                    <option value="easy" <?php echo ($difficulty == 'easy') ? 'selected' : ''; ?>>Easy</option>
                    <option value="medium" <?php echo ($difficulty == 'medium') ? 'selected' : ''; ?>>Medium</option>
                    <option value="hard" <?php echo ($difficulty == 'hard') ? 'selected' : ''; ?>>Hard</option>
                </select>
                <span class="invalid-feedback"><?php echo $errors['difficulty']; ?></span>
            </div>

            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" class="form-control <?php echo (!empty($errors['start_date'])) ? 'is-invalid' : ''; ?>" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                <span class="invalid-feedback"><?php echo $errors['start_date']; ?></span>
            </div>

            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" class="form-control <?php echo (!empty($errors['end_date'])) ? 'is-invalid' : ''; ?>" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                <span class="invalid-feedback"><?php echo $errors['end_date']; ?></span>
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

            <button type="submit" class="btn btn-primary">Add Quiz</button>
        </form>
    </div>

</body>
</html>
