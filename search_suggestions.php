<?php
header('Content-Type: application/json');
include 'db.php';

$searchTerm = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($searchTerm) > 2) {
    $stmt = $conn->prepare("
        SELECT id, name, price 
        FROM food_items 
        WHERE name LIKE CONCAT('%', ?, '%')
        LIMIT 5
    ");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $results = $stmt->get_result();
    
    $items = [];
    while ($row = $results->fetch_assoc()) {
        $items[] = $row;
    }
    
    echo json_encode($items);
} else {
    echo json_encode([]);
}