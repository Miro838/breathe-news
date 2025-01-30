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

// Fetch quizzes from the database
$quizzes = [];
$result = $conn->query("SELECT id, title FROM quizzes");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $quizzes[] = $row;
    }
    $result->free();
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
    }

    // Validate correct_answer
    $correct_answer = trim($_POST["correct_answer"]);
    if (empty($correct_answer)) {
        $errors['correct_answer'] = "Please specify the correct answer.";
    }

    // Check if there are no errors before inserting into database
    if (!array_filter($errors)) {
        // Start transaction
        $conn->begin_transaction();

        try {
            // Insert the question into the questions table
            $stmt = $conn->prepare("INSERT INTO questions (quiz_id, question_text, question_type) VALUES (?, ?, ?)");
            if ($stmt === false) {
                throw new Exception("Prepare failed: " . htmlspecialchars($conn->error));
            }

            $stmt->bind_param("iss", $quiz_id, $question_text, $question_type);

            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . htmlspecialchars($stmt->error));
            }

            $question_id = $stmt->insert_id;
            $stmt->close();

            // Insert the answer options into the question_options table
            if ($question_type == "multiple_choice") {
                foreach ($answer_options as $option) {
                    $is_correct = ($correct_answer == $option) ? 1 : 0;
                    $stmt = $conn->prepare("INSERT INTO question_options (question_id, option_text, is_correct) VALUES (?, ?, ?)");
                    if ($stmt === false) {
                        throw new Exception("Prepare failed: " . htmlspecialchars($conn->error));
                    }

                    $stmt->bind_param("isi", $question_id, $option, $is_correct);

                    if (!$stmt->execute()) {
                        throw new Exception("Execute failed: " . htmlspecialchars($stmt->error));
                    }

                    $stmt->close();
                }
            } else {
                // For true/false or short answer, insert only one option as correct answer
                $stmt = $conn->prepare("INSERT INTO question_options (question_id, option_text, is_correct) VALUES (?, ?, ?)");
                if ($stmt === false) {
                    throw new Exception("Prepare failed: " . htmlspecialchars($conn->error));
                }

                $is_correct = 1;
                $stmt->bind_param("isi", $question_id, $correct_answer, $is_correct);

                if (!$stmt->execute()) {
                    throw new Exception("Execute failed: " . htmlspecialchars($stmt->error));
                }

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

ob_end_flush(); // End output buffering
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question</title>
    <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Adjust path as needed -->
    <script src="path/to/bootstrap.bundle.js"></script> <!-- Adjust path as needed -->
    <style>
       
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
    <div class="breadcrumb" style="margin-left:25%; margin-top:10PX; width:70%">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
            <li class="breadcrumb-item"><a href="questions.php">Questions</a></li>
            <li class="breadcrumb-item active">Add Question</li>
        </ul>
    </div>

    <div class="form-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" name="questionform" onsubmit="return validateForm()">
            <h1>Add Question</h1>

            <div class="form-group">
                <label for="quiz_id">Select Quiz</label>
                <select class="form-control <?php echo (!empty($errors['quiz_id'])) ? 'is-invalid' : ''; ?>" id="quiz_id" name="quiz_id">
                    <option value="">Select Quiz</option>
                    <?php foreach ($quizzes as $quiz): ?>
                        <option value="<?php echo htmlspecialchars($quiz['id']); ?>" <?php echo ($quiz_id == $quiz['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($quiz['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="invalid-feedback"><?php echo $errors['quiz_id']; ?></span>
            </div>

            <div class="form-group">
                <label for="question_text">Question Text</label>
                <textarea class="form-control <?php echo (!empty($errors['question_text'])) ? 'is-invalid' : ''; ?>" id="question_text" name="question_text" rows="3"><?php echo htmlspecialchars($question_text); ?></textarea>
                <span class="invalid-feedback"><?php echo $errors['question_text']; ?></span>
            </div>

            <div class="form-group">
                <label for="question_type">Question Type</label>
                <select class="form-control <?php echo (!empty($errors['question_type'])) ? 'is-invalid' : ''; ?>" id="question_type" name="question_type" onchange="toggleOptions()">
                    <option value="">Select Type</option>
                    <option value="multiple_choice" <?php echo ($question_type == 'multiple_choice') ? 'selected' : ''; ?>>Multiple Choice</option>
                    <option value="true_false"  <?php echo ($question_type == 'true_false') ? 'selected' : ''; ?>>True/False</option>
                    <option value="short_answer" <?php echo ($question_type == 'short_answer') ? 'selected' : ''; ?>>Short Answer</option>
                </select>
                <span class="invalid-feedback"><?php echo $errors['question_type']; ?></span>
            </div>

            <div class="form-group" id="answer_options_div" style="display: none;">
                <label for="answer_options">Answer Options (for multiple-choice questions)</label>
                <div id="options_container">
                    <input type="text" class="form-control" name="answer_options[]" placeholder="Option 1">
                    <input type="text" class="form-control" name="answer_options[]" placeholder="Option 2">
                </div>
                <button type="button" class="btn btn-secondary" onclick="addOption()">Add Option</button>
                <span class="invalid-feedback"><?php echo $errors['answer_options']; ?></span>
            </div>

            <div class="form-group">
                <label for="correct_answer">Correct Answer</label>
                <input type="text" class="form-control <?php echo (!empty($errors['correct_answer'])) ? 'is-invalid' : ''; ?>" id="correct_answer" name="correct_answer" value="<?php echo htmlspecialchars($correct_answer); ?>">
                <span class="invalid-feedback"><?php echo $errors['correct_answer']; ?></span>
            </div>

            <button type="submit" class="btn btn-primary" name="submit">Add Question</button>
        </form>
    </div>

    <script>
    function toggleOptions() {
        var questionType = document.getElementById('question_type').value;
        var answerOptionsDiv = document.getElementById('answer_options_div');
        if (questionType === 'multiple_choice') {
            answerOptionsDiv.style.display = 'block';
        } else {
            answerOptionsDiv.style.display = 'none';
        }
    }
    function toggleOptionss() {
            var questionType = document.getElementById('question_type').value;
            var answerOptionsDiv = document.getElementById('answer_options_div');
            var trueFalseOptionsDiv = document.getElementById('true_false_options_div');

            // Hide all option containers first
            answerOptionsDiv.style.display = 'none';
            trueFalseOptionsDiv.style.display = 'none';

            if (questionType === 'multiple_choice') {
                answerOptionsDiv.style.display = 'block';
            } else if (questionType === 'true_false') {
                trueFalseOptionsDiv.style.display = 'block';
            }
        }
    function addOption() {
        var container = document.getElementById('options_container');
        var index = container.children.length + 1;
        var input = document.createElement('input');
        input.type = 'text';
        input.className = 'form-control';
        input.name = 'answer_options[]';
        input.placeholder = 'Option ' + index;
        container.appendChild(input);
    }

    function validateForm() {
        var questionText = document.forms["questionform"]["question_text"].value;
        var questionType = document.forms["questionform"]["question_type"].value;
        var answerOptions = document.forms["questionform"]["answer_options[]"];
        var correctAnswer = document.forms["questionform"]["correct_answer"].value;
        var quizId = document.forms["questionform"]["quiz_id"].value;

        if (quizId == "") {
            alert("Please select a quiz.");
            return false;
        }

        if (questionText == "") {
            alert("Question text must be filled out.");
            return false;
        }

        if (questionType == "") {
            alert("Question type must be selected.");
            return false;
        }

        if (questionType == "multiple_choice" && answerOptions.length < 2) {
            alert("Please provide at least two answer options.");
            return false;
        }

        if (correctAnswer == "") {
            alert("Correct answer must be specified.");
            return false;
        }

        return true;
    }
    </script>
</body>
</html>

