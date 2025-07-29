<?php
// order_details.php
session_start();
include("db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit();
}

$order_id = intval($_GET['id']);

// Fetch order basic info
$order_query = "SELECT orders.*, users.name, users.mobile 
                FROM orders 
                JOIN users ON orders.user_id = users.id 
                WHERE orders.id = $order_id";
$order_result = mysqli_query($conn, $order_query);
$order = mysqli_fetch_assoc($order_result);

// Fetch order items
$items_query = "SELECT order_items.*, food_items.name AS food_name 
                FROM order_items 
                JOIN food_items ON order_items.food_id = food_items.id 
                WHERE order_items.order_id = $order_id";
$items_result = mysqli_query($conn, $items_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order #<?= $order_id ?> Details | CravFoods</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h3 class="mb-3">Order #<?= $order_id ?> Details</h3>
    <div class="card mb-4">
        <div class="card-body">
            <h5>User: <?= htmlspecialchars($order['name']) ?> (<?= htmlspecialchars($order['mobile']) ?>)</h5>
            <p><strong>Address:</strong> <?= htmlspecialchars($order['delivery_address']) ?></p>
            <p><strong>Status:</strong> <?= $order['status'] ?></p>
            <p><strong>Order Date:</strong> <?= $order['order_date'] ?></p>
        </div>
    </div>

    <h5>Items Ordered:</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Food Item</th>
                <th>Quantity</th>
                <th>Price (₹)</th>
                <th>Subtotal (₹)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $grand_total = 0;
            while ($item = mysqli_fetch_assoc($items_result)) {
                $subtotal = $item['price'] * $item['quantity'];
                $grand_total += $subtotal;
                echo "<tr>
                        <td>{$item['food_name']}</td>
                        <td>{$item['quantity']}</td>
                        <td>{$item['price']}</td>
                        <td>" . number_format($subtotal, 2) . "</td>
                      </tr>";
            }
            ?>
            <tr>
                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                <td><strong>₹<?= number_format($grand_total, 2) ?></strong></td>
            </tr>
        </tbody>
    </table>

    <a href="admin_orders.php" class="btn btn-secondary mt-3">← Back to Orders</a>
</div>

</body>
</html>
