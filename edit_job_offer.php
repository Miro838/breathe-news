<?php
// Start the session if it hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in; if not, redirect to the admin login page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db/connection.php'; // Include database connection

// Get job offer ID from URL
$jobOfferId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the job offer details from the database
if ($jobOfferId > 0) {
    $query = "SELECT * FROM job_offers WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $jobOfferId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $jobOffer = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger'>Job offer not found.</div>";
        exit();
    }

    $stmt->close();
} else {
    echo "<div class='alert alert-danger'>Invalid job offer ID.</div>";
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $summary = $_POST['summary'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];
    $status = $_POST['status'];

    // Validate inputs (basic validation)
    if (!empty($title) && !empty($summary) && !empty($description) && !empty($location) && !empty($salary) && !empty($status)) {
        $title = $conn->real_escape_string($title);
        $summary = $conn->real_escape_string($summary);
        $description = $conn->real_escape_string($description);
        $location = $conn->real_escape_string($location);
        $salary = $conn->real_escape_string($salary);
        $status = $conn->real_escape_string($status);

        $query = "UPDATE job_offers 
                  SET title = ?, summary = ?, description = ?, location = ?, salary = ?, status = ?, updated_at = NOW()
                  WHERE id = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssssi", $title, $summary, $description, $location, $salary, $status, $jobOfferId);

        if ($stmt->execute()) {
            // Redirect to manage_joboffer.php after successful update
            header("Location: manage_joboffer.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-warning'>All fields are required.</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job Offer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }
        .header-placeholder {
            height: 60px; /* Adjust based on the height of your header */
        }
        .breadcrumb-container {
            margin-left: 20%;
            padding: 0;
            background-color: #f8f9fa;
            width: 80%;
            border-bottom: 1px solid #ddd;
        }
        .breadcrumb {
            margin: 0;
            padding: 10px 20px;
            background-color: transparent;
            display: flex;
            align-items: center;
        }
        .form-container {
            margin-left: 20%;
            width: 78%;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .btn-primary {
            margin-top: 10px;
        }
        .content-wrapper {
            padding: 20px;
        }
    </style>
</head>
<body>
    <?php include 'include/hiringoffer.php'; ?> <!-- Include header -->

    <!-- Breadcrumb directly below the header -->
    <div class="breadcrumb-container">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="home.php">Home</a></li>
            <li class="breadcrumb-item"><a href="job_offers.php">Job Offers</a></li>
            <li class="breadcrumb-item active">Edit Job Offer</li>
        </ul>
    </div>

    <div class="content-wrapper">
        <div class="form-container">
            <h1 style="text-align:center">Edit Job Offer</h1>
            <form method="POST" action="edit_job_offer.php?id=<?php echo $jobOfferId; ?>">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($jobOffer['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="summary">Summary</label>
                    <input type="text" class="form-control" id="summary" name="summary" value="<?php echo htmlspecialchars($jobOffer['summary']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($jobOffer['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($jobOffer['location']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="salary">Salary</label>
                    <input type="text" class="form-control" id="salary" name="salary"  value="<?php echo htmlspecialchars($jobOffer['salary']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="Open" <?php echo ($jobOffer['status'] == 'Open') ? 'selected' : ''; ?>>Open</option>
                        <option value="Closed" <?php echo ($jobOffer['status'] == 'Closed') ? 'selected' : ''; ?>>Closed</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Job Offer</button>
            </form>
        </div>
    </div>

</body>
</html>
