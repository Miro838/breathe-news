<?php
session_start();
include 'db/connection.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: home1.php');
    exit();
}

// Fetch data from all four tables
$milestonesResult = $conn->query("SELECT * FROM milestones");
$teamResult = $conn->query("SELECT * FROM team");
$testimonialsResult = $conn->query("SELECT * FROM testimonials");
$websiteInfoResult = $conn->query("SELECT * FROM website_info");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage About Us</title>
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
        h4{text-align: center;}
    </style>
</head>
<body>
    <?php include 'include/admin_user.php'; ?>
    
    <div class="container" style="margin-left:20%; width:80%">
        <div class="breadcrumb">
            <ul class="breadcrumb" style="height:10px">
                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                <li class="breadcrumb-item active">Manage About Us</li>
            </ul>
        </div>
        <h2>Manage About Us</h2>

        <!-- Manage Milestones -->
        <h4>Milestones</h4>
        <div class="btn-right">
            <a href="add_about.php" class="btn btn-success mb-3 btn-blue"  style="background-color:blue">Add Milestone</a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Milestone</th>
                    <th>Date</th>
                    <th>Details</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $milestonesResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['milestone']); ?></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['details']); ?></td>
                    <td>
                        <a href="edit_about.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="delete_about.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this entry?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                
            </tbody>
        </table>

        <!-- Manage Team -->
        <h4>Team</h4>
        <div class="btn-right">
            <a href="add_team.php" class="btn btn-success mb-3 btn-blue"  style="background-color:blue">Add Team Member</a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Profile Image</th>
                    <th>Social Links</th>
                    <th>Joined Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $teamResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['position']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($row['profile_image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" style="width:50px;height:50px;"></td>
                    <td><?php echo htmlspecialchars($row['social_links']); ?></td>
                    <td><?php echo htmlspecialchars($row['joined_date']); ?></td>
                    <td>
                        <a href="edit_team.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="delete_team.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this entry?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Manage Testimonials -->
        <h4>Testimonials</h4>
        <div class="btn-right">
            <a href="add_testimonial.php" class="btn btn-success mb-3 btn-blue"  style="background-color:blue">Add Testimonial</a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Content</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $testimonialsResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['content']); ?></td>
                    <td>
                        <a href="edit_testimonial.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="delete_testimonial.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this entry?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Manage Website Info -->
        <h4>Website Info</h4>
        <div class="btn-right">
            <a href="add_website_info.php" class="btn btn-success mb-3 btn-blue" style="background-color:blue">Add Website Info</a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $websiteInfoResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['content']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($row['updated_at']); ?></td>
                    <td>
                        <a href="edit_website_info.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="delete_website_info.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this entry?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    
</body>
</html>
