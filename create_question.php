<?php
session_start();

// Redirect to login if the user is not authenticated
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

include "include/quiz_header.php";
include "db/connection.php";

// Fetch questions and options from the database
$query = "
    SELECT q.id AS question_id, q.quiz_id, q.question_text, q.question_type,
           qo.id AS option_id, qo.option_text, qo.is_correct 
    FROM questions q 
    LEFT JOIN question_options qo ON q.id = qo.question_id 
    ORDER BY q.quiz_id ASC, q.id ASC, qo.id ASC
";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

$questions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $quizId = $row['quiz_id'];
    $questionId = $row['question_id'];

    if (!isset($questions[$quizId])) {
        $questions[$quizId] = [];
    }

    if (!isset($questions[$quizId][$questionId])) {
        $questions[$quizId][$questionId] = [
            'quiz_id' => $row['quiz_id'],
            'question_text' => $row['question_text'],
            'question_type' => $row['question_type'],
            'options' => []
        ];
    }

    if (!empty($row['option_text'])) {
        $questions[$quizId][$questionId]['options'][] = [
            'option_text' => $row['option_text'],
            'is_correct' => $row['is_correct']
        ];
    }
}

mysqli_close($conn);
?>

<div style="margin-left:20%; width:80%">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="create_question.php">Home</a></li>
        <li class="breadcrumb-item active">QUESTIONS</li>
    </ul>
</div>

<div style="margin-left:25%; margin-top:10px; width:70%">
    <div class="text-right mb-3">
        <a href="addquestion.php" class="btn btn-primary">Add Question</a>
    </div>

    <?php foreach ($questions as $quizId => $quizQuestions): ?>
        <h3>Quiz ID: <?php echo htmlspecialchars($quizId); ?></h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 15%;">Quiz ID</th>
                    <th style="width: 30%;">Question Text</th>
                    <th style="width: 20%;">Question Type</th>
                    <th style="width: 30%;">Options (Correct Answer in Bold)</th>
                    <th style="width: 15%;">Edit</th>
                    <th style="width: 15%;">Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($quizQuestions as $id => $question): ?>
                    <tr>
    <td><?php echo htmlspecialchars($id); ?></td>
    <td><?php echo htmlspecialchars($question['quiz_id']); ?></td>
    <td><?php echo htmlspecialchars($question['question_text']); ?></td>
    <td>
        <?php 
        $questionType = htmlspecialchars($question['question_type']);
        echo ($questionType == 'multiple_choice') ? 'Multiple Choice' : 
             ($questionType == 'true_false' ? 'True/False' : 
              ($questionType == 'short_answer' ? 'Short Answer' : 'Unknown Type'));
        ?>
    </td>
    <td>
        <ul>
            <?php foreach ($question['options'] as $option): ?>
                <li>
                    <?php if ((int)$option['is_correct'] === 1): ?>
                        <strong><?php echo htmlspecialchars($option['option_text']); ?></strong>
                    <?php else: ?>
                        <?php echo htmlspecialchars($option['option_text']); ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </td>
    <td style="text-align: center;">
        <a href="editquestion.php?id=<?php echo urlencode($id); ?>" class="btn btn-primary">Edit</a>
    </td>
    <td style="text-align: center;">
        <a href="deletequestion.php?id=<?php echo urlencode($id); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this question?');">Delete</a>
    </td>
</tr>

                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
</div>

<!-- Include Bootstrap JavaScript for Modal Functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
