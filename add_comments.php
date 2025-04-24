<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['logged_in'])) {
    die(json_encode(['error' => 'You must be logged in to post comments']));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['error' => 'Invalid request method']));
}

if (empty($_POST['comment'])) {
    die(json_encode(['error' => 'Comment cannot be empty']));
}

$comment = trim($_POST['comment']);
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Prepare statement to prevent SQL injection
$stmt = $conn->prepare("INSERT INTO comments (username, comment) VALUES ( ?, ?)");
$stmt->bind_param("iss", $username, $comment);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Comment added successfully']);
} else {
    echo json_encode(['error' => 'Error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>