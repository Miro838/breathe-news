<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

include 'db/connection.php'; // Include database connection

// Fetch job offers from the database with pagination
$limit = 10; // Number of job offers per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$query = "SELECT id, title, summary, description, location, salary, status, created_at, updated_at 
          FROM job_offers 
          LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Count total job offers for pagination
$total_query = "SELECT COUNT(*) AS total FROM job_offers";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_pages = ceil($total_row['total'] / $limit);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Job Offers</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 1200px;
        }
        .table th, .table td {
            text-align: center;
        }
        .btn {
            font-size: 0.875rem;
            padding: 0.5rem;
        }
        .content-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .content {
            width: 100%;
            max-width: 1000px;
        }
        .breadcrumb {
            background-color: #f8f9fa;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 0.25rem;
            margin-left:5%
        }
    </style>
</head>
<body>
    <?php include 'include/hiringoffer.php'; // Include header or navigation ?>
    
    <div class="content-wrapper">
        <div class="content">
            <!-- Breadcrumb -->
            <ul class="breadcrumb" style="width:110%">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active">Manage Job Offers</li>
            </ul>
            
            <h1 class="mb-4 text-center">Manage Job Offers</h1>
            <div class="d-flex justify-content-end mb-4" style="margin-left:40%;width:70%">
                <a href="add_job_offer.php" class="btn btn-primary">Add New Job Offer</a>
            </div>
            <table class="table table-bordered table-striped" style="margin-left:10%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Summary</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Salary</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars($row['summary']); ?></td>
                                <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars($row['description']); ?></td>
                                <td><?php echo htmlspecialchars($row['location']); ?></td>
                                <td><?php echo htmlspecialchars($row['salary']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($row['created_at']))); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($row['updated_at']))); ?></td>
                                <td>
                                    <a href="edit_job_offer.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-warning btn-sm" style="background-color:blue;color:white">Edit</a>
                                    <a href="delete_job_offer.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this job offer?');"
                                    style="background-color:red;color:white">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center">No job offers found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center"style="margin-right:60%">
                    <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo max($page - 1, 1); ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo; Previous</span>
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?php echo min($page + 1, $total_pages); ?>" aria-label="Next">
                            <span aria-hidden="true">Next &raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    
</body>
</html>
