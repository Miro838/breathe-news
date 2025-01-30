<?php
session_start(); // Ensure session is started
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

include 'db/connection.php'; // Include database connection



// Handle deleting a poll
if (isset($_GET['delete_id'])) {
    $poll_id = (int)$_GET['delete_id'];

    // Check if the poll exists
    $checkPoll = $conn->query("SELECT id FROM polls WHERE id = $poll_id");
    if ($checkPoll->num_rows > 0) {
        // Delete associated options and votes first due to foreign key constraints
        $conn->query("DELETE FROM poll_votes WHERE poll_id = $poll_id");
        $conn->query("DELETE FROM poll_options WHERE poll_id = $poll_id");
        $conn->query("DELETE FROM polls WHERE id = $poll_id");
    }

    header('Location: manage_polls.php');
    exit();
}

// Fetch all polls with pagination
$pollsQuery = "SELECT id, question, status, created_at, updated_at FROM polls ";
$pollsResult = $conn->query($pollsQuery);

// Check if any polls were fetched
if ($pollsResult->num_rows > 0) {
    // Initialize an array to store the polls
    $polls = [];
    
    // Fetch the polls
    while ($poll = $pollsResult->fetch_assoc()) {
        // Ensure every poll has its options
        $polls[$poll['id']] = $poll;
        $polls[$poll['id']]['options'] = [];
    }

    // Fetch options for each poll
    $optionsQuery = "SELECT poll_id, id, option_text, votes FROM poll_options";
    $optionsResult = $conn->query($optionsQuery);

    // Group options by poll
    while ($option = $optionsResult->fetch_assoc()) {
        $polls[$option['poll_id']]['options'][] = $option;
    }
} else {
    $polls = []; // No polls found
}

// Fetch total number of polls for pagination
$totalPollsQuery = "SELECT COUNT(*) AS total FROM polls";
$totalPollsResult = $conn->query($totalPollsQuery);
$totalPolls = $totalPollsResult->fetch_assoc()['total'];



?>
<?php include 'include/polls.php' ?>
<div style="margin-left:20%; width:80%">
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="home.php">Home</a></li>
        <li class="breadcrumb-item active">Manage Polls</li>
    </ul>
</div>

<div style="margin-left:20%; width:80%">
    <div class="text-right mb-3">
        <a href="addpoll.php" class="btn btn-primary">Add Poll</a>
    </div>

    <!-- Polls Table -->
    <table class="table table-bordered" style="width: 1000px;">
        <thead>
            <tr>
                <th style="width: 30%;">Poll Question</th>
                <th style="width: 15%;">Status</th>
                <th style="width: 15%;">Created At</th>
                <th style="width: 15%;">Updated At</th>
                <th style="width: 15%;">Options</th>
                <th style="width: 10%;">Edit</th>
                <th style="width: 10%;">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($polls)): ?>
                <?php foreach ($polls as $poll): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($poll['question']); ?></td>
                        <td><?php echo htmlspecialchars($poll['status']); ?></td>
                        <td><?php echo htmlspecialchars($poll['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($poll['updated_at']); ?></td>
                        <td>
                            <ul style="margin: 0; padding: 0;">
                                <?php foreach ($poll['options'] as $option): ?>
                                    <li><?php echo htmlspecialchars($option['option_text']) . ' - ' . $option['votes'] . ' votes'; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td>
                            <a href="editpoll.php?id=<?php echo $poll['id']; ?>" class="btn btn-primary" role="button">Edit</a>
                        </td>
                        <td>
                            <a href="manage_polls.php?delete_id=<?php echo $poll['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No polls found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

   
    
    </ul>
</div>

<?php
$conn->close(); // Close the connection at the end
?>
