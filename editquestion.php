<?php
ob_start(); // Start output buffering
session_start();

// Check if user is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include necessary files
include "db/connection.php";
include 'include/quiz_header.php';

// Initialize variables and error messages
$question_text = $question_type = $correct_answer = $quiz_id = "";
$answer_options = array();
$errors = array(
    'question_text' => '',
    'question_type' => '',
    'answer_options' => '',
    'correct_answer' => '',
    'quiz_id' => ''
);

// Fetch the list of quizzes for the dropdown
$quizzes = array();
$stmt = $conn->prepare("SELECT id, title FROM quizzes");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $quizzes[] = $row;
}
$stmt->close();

// Fetch the question item to be updated
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $question_id = intval($_GET['id']); // Ensure ID is an integer
    $stmt = $conn->prepare("SELECT * FROM questions WHERE id = ?");
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $question_text = htmlspecialchars($row['question_text']);
        $question_type = htmlspecialchars($row['question_type']);
        $quiz_id = htmlspecialchars($row['quiz_id']); // Fetch quiz_id
    } else {
        echo "<script>alert('Question item not found.'); window.location.href = 'create_question.php';</script>";
        exit();
    }
    $stmt->close();
    
    // Fetch existing options for the question
    if ($question_type == "multiple_choice") {
        $stmt = $conn->prepare("SELECT * FROM question_options WHERE question_id = ?");
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $answer_options[] = $row['option_text'];
            if ($row['is_correct']) {
                $correct_answer = $row['option_text'];
            }
        }
        $stmt->close();
    } elseif ($question_type == "short_answer") {
        $stmt = $conn->prepare("SELECT * FROM question_options WHERE question_id = ?");
        $stmt->bind_param("i", $question_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $correct_answer = $row['option_text'];
        }
        $stmt->close();
    }
} else {
    echo "<script>alert('Invalid request. No question ID provided.'); window.location.href = 'create_question.php';</script>";
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate quiz_id
    $quiz_id = trim($_POST["quiz_id"]);
    if (empty($quiz_id)) {
        $errors['quiz_id'] = "Please select a quiz.";
    }

    // Validate question_text
    $question_text = trim($_POST["question_text"]);
    if (empty($question_text)) {
        $errors['question_text'] = "Please enter the question text.";
    }

    // Validate question_type
    $question_type = trim($_POST["question_type"]);
    if (empty($question_type)) {
        $errors['question_type'] = "Please select a question type.";
    }

    // Validate answer_options for multiple-choice questions
    if ($question_type == "multiple_choice") {
        $answer_options = isset($_POST['answer_options']) ? $_POST['answer_options'] : array();
        if (count($answer_options) < 2) {
            $errors['answer_options'] = "Please provide at least two answer options.";
        }

        // Validate correct_answer
        if (empty($_POST["correct_answer"])) {
            $errors['correct_answer'] = "Please specify the correct answer.";
        } else {
            $correct_answer = trim($_POST["correct_answer"]);
        }
    } elseif ($question_type == "short_answer") {
        // Validate correct_answer for short_answer
        $correct_answer = trim($_POST["correct_answer"]);
        if (empty($correct_answer)) {
            $errors['correct_answer'] = "Please provide the correct answer.";
        }
    } else {
        $correct_answer = ''; // No correct_answer for non-multiple-choice questions
    }

    // Check if there are no errors before updating the database
    if (!array_filter($errors)) {
        // Start transaction
        $conn->begin_transaction();

        try {
            // Update the question in the questions table
            $stmt = $conn->prepare("UPDATE questions SET question_text = ?, question_type = ?, quiz_id = ? WHERE id = ?");
            $stmt->bind_param("ssii", $question_text, $question_type, $quiz_id, $question_id);
            $stmt->execute();
            $stmt->close();

            // Clear existing options
            $stmt = $conn->prepare("DELETE FROM question_options WHERE question_id = ?");
            $stmt->bind_param("i", $question_id);
            $stmt->execute();
            $stmt->close();

            // Insert new options
            if ($question_type == "multiple_choice") {
                foreach ($answer_options as $index => $option) {
                    $is_correct = ($correct_answer == $option) ? 1 : 0;
                    $stmt = $conn->prepare("INSERT INTO question_options (question_id, option_text, is_correct) VALUES (?, ?, ?)");
                    $stmt->bind_param("isi", $question_id, $option, $is_correct);
                    $stmt->execute();
                    $stmt->close();
                }
            } elseif ($question_type == "short_answer") {
                // Insert correct answer for short_answer
                $stmt = $conn->prepare("INSERT INTO question_options (question_id, option_text, is_correct) VALUES (?, ?, ?)");
                $is_correct = 1; // Short answer question has only one correct answer
                $stmt->bind_param("isi", $question_id, $correct_answer, $is_correct);
                $stmt->execute();
                $stmt->close();
            }

            // Commit transaction
            $conn->commit();

            // Redirect to question management page after successful submission
            header("Location: create_question.php");
            exit();
        } catch (Exception $e) {
            // Rollback transaction if something went wrong
            $conn->rollback();
            echo "<script>alert('Transaction failed: " . htmlspecialchars($e->getMessage()) . "');</script>";
        }

        // Close the connection
        $conn->close();
    }
}

ob_end_flush(); ?>// End output buffering
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
    <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Adjust path as needed -->
    <script src="path/to/bootstrap.bundle.js"></script> <!-- Adjust path as needed -->
    <style>
        .breadcrumb {
            margin-left: 20%;
            width: 80%;
        }
        .form-container {
            margin-left: 25%;
            width: 70%;
        }
        .invalid-feedback {
            display: block;
        }
    </style>
</head>
<body>
    <div class="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
            <li class="breadcrumb-item"><a href="create_question.php">Questions</a></li>
            <li class="breadcrumb-item active">Edit Question</li>
        </ul>
    </div>

    <div class="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . htmlspecialchars($question_id); ?>" method="post" enctype="multipart/form-data" name="questionform" onsubmit="return validateForm()">
            <h1>Edit Question</h1>

            <div class="form-group">
                <label for="quiz_id">Select Quiz</label>
                <select class="form-control <?php echo (!empty($errors['quiz_id'])) ? 'is-invalid' : ''; ?>" id="quiz_id" name="quiz_id">
                    <option value="">Select Quiz</option>
                    <?php foreach ($quizzes as $quiz): ?>
                        <option value="<?php echo $quiz['id']; ?>" <?php echo ($quiz_id == $quiz['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($quiz['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="invalid-feedback"><?php echo $errors['quiz_id']; ?></span>
            </div>

            <div class="form-group">
                <label for="question_text">Question Text</label>
                <textarea class="form-control <?php echo (!empty($errors['question_text'])) ? 'is-invalid' : ''; ?>" id="question_text" name="question_text" rows="4"><?php echo htmlspecialchars($question_text); ?></textarea>
                <span class="invalid-feedback"><?php echo $errors['question_text']; ?></span>
            </div>

            <div class="form-group">
                <label for="question_type">Question Type</label>
                <select class="form-control <?php echo (!empty($errors['question_type'])) ? 'is-invalid' : ''; ?>" id="question_type" name="question_type" onchange="showOptionsFields()">
                    <option value="">Select Type</option>
                    <option value="multiple_choice" <?php echo ($question_type == 'multiple_choice') ? 'selected' : ''; ?>>Multiple Choice</option>
                    <option value="true_false" <?php echo ($question_type == 'true_false') ? 'selected' : ''; ?>>True/False</option>
                    <option value="short_answer" <?php echo ($question_type == 'short_answer') ? 'selected' : ''; ?>>Short Answer</option>
                </select>
                <span class="invalid-feedback"><?php echo $errors['question_type']; ?></span>
            </div>

            <div id="options-container" class="form-group" style="display: <?php echo ($question_type == 'multiple_choice') ? 'block' : 'none'; ?>;">
                <label for="answer_options">Answer Options</label>
                <div id="answer-options">
                    <?php if ($question_type == 'multiple_choice'): ?>
                        <?php foreach ($answer_options as $index => $option): ?>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control <?php echo (!empty($errors['answer_options'])) ? 'is-invalid' : ''; ?>" name="answer_options[]" value="<?php echo htmlspecialchars($option); ?>">
                                <button type="button" class="btn btn-danger" onclick="removeOption(this)">Remove</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="btn btn-primary" onclick="addOption()">Add Option</button>
                <span class="invalid-feedback"><?php echo $errors['answer_options']; ?></span>
            </div>

            <div id="correct-answer-container" class="form-group">
                <label for="correct_answer">Correct Answer</label>
                <input type="text" class="form-control <?php echo (!empty($errors['correct_answer'])) ? 'is-invalid' : ''; ?>" id="correct_answer" name="correct_answer" value="<?php echo htmlspecialchars($correct_answer); ?>">
                <span class="invalid-feedback"><?php echo $errors['correct_answer']; ?></span>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>

    <script>
        function showOptionsFields() {
            const questionType = document.getElementById('question_type').value;
            document.getElementById('options-container').style.display = (questionType === 'multiple_choice') ? 'block' : 'none';
        }

        function addOption() {
            const container = document.getElementById('answer-options');
            const index = container.children.length;
            const optionHtml = `
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="answer_options[]" value="">
                    <button type="button" class="btn btn-danger" onclick="removeOption(this)">Remove</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', optionHtml);
        }

        function removeOption(button) {
            button.parentElement.remove();
        }

        function validateForm() {
            let valid = true;
            // Add validation logic here
            return valid;
        }

        document.addEventListener('DOMContentLoaded', function() {
            showOptionsFields(); // Ensure correct display on page load
        });
    </script>
</body>
</html>
