<?php
session_start();
include 'db/connection.php';

$data = json_decode(file_get_contents('php://input'), true);
$content_id = $data['content_id'];
$rating = $data['rating'];
$user_id = $data['user_id'];

// Check if a rating already exists for the user
$check_rating_query = "SELECT * FROM ratings WHERE content_id = ? AND user_id = ?";
$check_rating_stmt = $conn->prepare($check_rating_query);
$check_rating_stmt->bind_param('ii', $content_id, $user_id);
$check_rating_stmt->execute();
$check_result = $check_rating_stmt->get_result();

if ($check_result->num_rows > 0) {
    // Update existing rating
    $update_rating_query = "UPDATE ratings SET rating = ?, rate_date = NOW() WHERE content_id = ? AND user_id = ?";
    $update_rating_stmt = $conn->prepare($update_rating_query);
    $update_rating_stmt->bind_param('iii', $rating, $content_id, $user_id);
    $update_rating_stmt->execute();
} else {
    // Insert new rating
    $insert_rating_query = "INSERT INTO ratings (content_type, content_id, user_id, rating, rate_date) VALUES (?, ?, ?, ?, NOW())";
    $content_type = 'top_shows'; // Set content type
    $insert_rating_stmt = $conn->prepare($insert_rating_query);
    $insert_rating_stmt->bind_param('siid', $content_type, $content_id, $user_id, $rating);
    $insert_rating_stmt->execute();
}

// Calculate new average rating
$rating_query = "SELECT AVG(rating) AS new_avg_rating FROM ratings WHERE content_id = ? AND content_type = ?";
$rating_stmt = $conn->prepare($rating_query);
$rating_stmt->bind_param('is', $content_id, $content_type);
$rating_stmt->execute();
$rating_result = $rating_stmt->get_result();
$new_avg_rating = round($rating_result->fetch_assoc()['new_avg_rating'], 1);

// Return success response with new average rating
echo json_encode(['success' => true, 'new_avg_rating' => $new_avg_rating]);
?>