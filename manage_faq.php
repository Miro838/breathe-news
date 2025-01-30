<?php
session_start();
include 'db/connection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

// Fetch FAQs from the database
$faqsResult = $conn->query("SELECT * FROM faqs");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage FAQs</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-right {
            text-align: right; /* Aligns the button to the right */
            margin-bottom: 20px; /* Space below the button */
        }

        .btn-blue {
            background-color: blue; /* Button background color */
            color: white; /* Text color */
        }
        h4 { text-align: center; }
    </style>
</head>
<body>
    <?php include 'include/admin_user.php'; ?>
    
    <div class="container" style="margin-left:20%; width:80%">
        <div class="breadcrumb">
            <ul class="breadcrumb" style="height:10px">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active">Manage FAQs</li>
            </ul>
        </div>
        <h2>Manage FAQs</h2>

        <div class="btn-right">
            <a href="add_faq.php" class="btn btn-success mb-3 btn-blue" style="background-color:blue">Add FAQ</a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Category</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $faqsResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['question']); ?></td>
                    <td><?php echo htmlspecialchars($row['answer']); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td>
                        <a href="edit_faq.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="delete_faq.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this entry?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    
</body>
</html>
