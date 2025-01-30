<?php
session_start();
include 'db/connection.php';

// Redirect to login if not logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

// Handle form submission for updating user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $status = $_POST['status'];
    $role = $_POST['role'];

    $query = "UPDATE admin_login SET username = ?, email = ?, status = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssi', $username, $email, $status, $role, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: view_user.php");
        exit();
    } else {
        echo "Error updating user.";
    }

    $stmt->close();
}

// Fetch user data for form display
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM admin_login WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "User not found.";
        exit();
    }
} else {
    echo "No user ID specified.";
    exit();
}

$conn->close();
?>

<?php include "include/admin_user.php"; ?>

<div style="margin-left:20%; width:80%">
    <h2>Edit User</h2>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control" required>
                <option value="active" <?php if ($user['status'] === 'active') echo 'selected'; ?>>Active</option>
                <option value="inactive" <?php if ($user['status'] === 'inactive') echo 'selected'; ?>>Inactive</option>
                <option value="banned" <?php if ($user['status'] === 'banned') echo 'selected'; ?>>Banned</option>
            </select>
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" class="form-control" required>
                <option value="user" <?php if ($user['role'] === 'user') echo 'selected'; ?>>User</option>
                <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
</div>
