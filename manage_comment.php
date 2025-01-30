<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

// Ensure the search bar is loaded first
include "db/connection.php";

// Handle Deleting Comments
if (isset($_POST['delete_comment'])) {
    $comment_id = $_POST['comment_id'];
    $deleteSql = "DELETE FROM comments WHERE id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $stmt->close();
}

// Handle Editing Comments
if (isset($_POST['edit_comment'])) {
    $comment_id = $_POST['comment_id'];
    $comment_text = $_POST['comment_text'];
    $editSql = "UPDATE comments SET comment_text = ? WHERE id = ?";
    $stmt = $conn->prepare($editSql);
    $stmt->bind_param("si", $comment_text, $comment_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch All Comments
$fetchCommentsSql = 'SELECT * FROM comments ORDER BY comment_date DESC';
$commentsResult = $conn->query($fetchCommentsSql);
$comments = $commentsResult ? $commentsResult->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Comments</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include "include/header.php";?>
<div style="margin-left:20%; width:80%">
    <!-- Place the comments table below the header/search bar -->
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="home.php">Home</a></li>
        <li class="breadcrumb-item active">Manage Comments</li>
    </ul>

    <h2 class="text-center">Manage Comments</h2>

    <div class="text-right mb-3">
        <!-- Add button if necessary -->
    </div>

    <!-- Comments Table -->
    <table class="table table-bordered table-hover" style="width: 100%; max-width: 1000px;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Section</th>
                <th>Item ID</th>
                <th>Comment</th>
                <th>User ID</th>
                <th>Username</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($comments): ?>
                <?php foreach ($comments as $comment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($comment['id']); ?></td>
                        <td><?php echo htmlspecialchars($comment['section']); ?></td>
                        <td><?php echo htmlspecialchars($comment['item_id']); ?></td>
                        <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <?php echo htmlspecialchars($comment['comment_text']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($comment['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($comment['username']); ?></td>
                        <td><?php echo htmlspecialchars($comment['comment_date']); ?></td>
                        <td>
                            <!-- Edit Comment Form -->
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#editCommentModal" data-id="<?php echo $comment['id']; ?>" data-text="<?php echo htmlspecialchars($comment['comment_text']); ?>">
                                Edit
                            </button>

                            <!-- Delete Comment Form -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                <button type="submit" name="delete_comment" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-center">No comments found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Edit Comment Modal -->
<div class="modal fade" id="editCommentModal" tabindex="-1" role="dialog" aria-labelledby="editCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCommentModalLabel">Edit Comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="comment_id" id="comment_id">
                    <div class="form-group">
                        <label for="comment_text">Comment</label>
                        <textarea class="form-control" id="comment_text" name="comment_text" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="edit_comment" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $('#editCommentModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var commentId = button.data('id'); // Extract info from data-* attributes
        var commentText = button.data('text');
        
        var modal = $(this);
        modal.find('.modal-body #comment_id').val(commentId);
        modal.find('.modal-body #comment_text').val(commentText);
    });
</script>
</body>
</html>

<?php
$conn->close();
?>
