<?php
session_start();
include("db.php");

if (!isset($_SESSION['admin_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Unauthorized access");
}

$appId = $_POST['id'] ?? 0;
$status = $_POST['status'] ?? '';

if (in_array($status, ['pending', 'reviewed', 'rejected', 'hired'])) {
    $query = "UPDATE job_applications SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $appId);
    $stmt->execute();
}

header("Content-Type: application/json");
echo json_encode(['success' => true]);