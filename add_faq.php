<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

include "db/connection.php";

// Initialize variables and errors
$question = $answer = $category = "";
$question_err = $answer_err = $category_err = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = trim($_POST["question"]);
    $answer = trim($_POST["answer"]);
    $category = trim($_POST["category"]);

    // Validate input fields
    if (empty($question)) {
        $question_err = "Please enter a question.";
    }
    if (empty($answer)) {
        $answer_err = "Please enter an answer.";
    }
    if (empty($category)) {
        $category_err = "Please enter a category.";
    }

    // Insert new FAQ into the database if no errors
    if (empty($question_err) && empty($answer_err) && empty($category_err)) {
        $sql = "INSERT INTO faqs (question, answer, category, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $question, $answer, $category);

        if ($stmt->execute()) {
            header("Location: manage_faq.php"); // Redirect after successful submission
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
    <h1>Add FAQ</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-container">
        <div class="form-group">
            <label for="question">Question</label>
            <input type="text" id="question" name="question" class="form-control <?php echo (!empty($question_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($question); ?>">
            <div class="invalid-feedback"><?php echo $question_err; ?></div>
        </div>
        <div class="form-group">
            <label for="answer">Answer</label>
            <textarea id="answer" name="answer" class="form-control <?php echo (!empty($answer_err)) ? 'is-invalid' : ''; ?>"><?php echo htmlspecialchars($answer); ?></textarea>
            <div class="invalid-feedback"><?php echo $answer_err; ?></div>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" id="category" name="category" class="form-control <?php echo (!empty($category_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($category); ?>">
            <div class="invalid-feedback"><?php echo $category_err; ?></div>
        </div>
        <button type="submit" class="btn btn-primary">Add FAQ</button>
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
