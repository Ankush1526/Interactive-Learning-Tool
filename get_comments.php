<?php
require_once 'db_connect.php';

header('Content-Type: application/json');

$sql = "SELECT username, comment, created_at FROM comments ORDER BY created_at DESC";
$result = $conn->query($sql);

$comments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
}

echo json_encode($comments);
$conn->close();
?>