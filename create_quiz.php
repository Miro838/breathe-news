<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

include "include/quiz_header.php";
include "db/connection.php";
?>
<div style="margin-left:20%; width:80%">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="create_quiz.php">Home</a></li>
        <li class="breadcrumb-item active">Quiz</li>
    </ul>
</div>
<div style="margin-left:25%; margin-top:20px; width:70%">
    <div class="text-right">
        <button class="btn"><a href="add_quiz.php">Add Quiz</a></button>
    </div>
    <table class="table table-bordered">
        <tr>
            <th style="width: 5%;">Id</th>
            <th style="width: 15%;">Title</th>
            <th style="width: 20%;">Description</th>
            <th style="width: 10%;">Category ID</th>
            <th style="width: 10%;">Difficulty</th>
            <th style="width: 10%;">Start Date</th>
            <th style="width: 10%;">End Date</th>
            <th style="width: 10%;">Status</th>
            <th style="width: 10%;">Created At</th>
            <th style="width: 10%;">Updated At</th>
            <th style="width: 15%;">Edit</th>
            <th style="width: 15%;">Delete</th>
        </tr>

   
        <?php 
        $query = mysqli_query($conn, "SELECT * FROM quizzes"); // Ensure the table name is correct
        while ($row = mysqli_fetch_array($query)) {
        ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars(substr($row['description'], 0, 200)); ?><?php if (strlen($row['description']) > 200) echo '...'; ?></td>
            <td><?php echo htmlspecialchars($row['category_id']); ?></td>
            <td><?php echo htmlspecialchars($row['difficulty']); ?></td>
            <td><?php echo htmlspecialchars($row['start_date']); ?></td>
            <td><?php echo htmlspecialchars($row['end_date']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
            <td><?php echo htmlspecialchars($row['updated_at']); ?></td>
 
            <td style="text-align: center;">
                <a href="editquiz.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-primary" role="button">Edit</a>
            </td>
            <td style="text-align: center;">
                <a href="deletequiz.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>

</div>



<!-- Include Bootstrap JavaScript for Modal Functionality -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
