<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders | CravFoods</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .order-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 30px;
            padding: 20px;
        }
        .order-items {
            font-size: 0.95rem;
        }
        .order-status {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">My Orders</h2>

    <?php
    $orders_query = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
    $orders_result = mysqli_query($conn, $orders_query);

    if (mysqli_num_rows($orders_result) > 0) {
        while ($order = mysqli_fetch_assoc($orders_result)) {
            echo "<div class='order-card'>";
            echo "<h5>Order #{$order['id']}</h5>";
            echo "<p><strong>Date:</strong> " . date("d M Y, h:i A", strtotime($order['created_at'])) . "</p>";
            echo "<p><strong>Total:</strong> ₹" . number_format($order['total_amount'], 2) . "</p>";
            echo "<p><strong>Coupon Used:</strong> " . ($order['coupon_applied'] ?: 'None') . "</p>";
            echo "<p><strong>Status:</strong> <span class='text-primary order-status'>" . ucfirst($order['status']) . "</span></p>";

            // Get items for this order
            $order_id = $order['id'];
            $items_query = "SELECT oi.*, fi.name FROM order_items oi 
                            JOIN food_items fi ON oi.food_id = fi.id
                            WHERE oi.order_id = $order_id";
            $items_result = mysqli_query($conn, $items_query);

            echo "<h6 class='mt-3'>Items:</h6>";
            echo "<ul class='list-group order-items'>";
            while ($item = mysqli_fetch_assoc($items_result)) {
                echo "<li class='list-group-item d-flex justify-content-between'>
                        <span>{$item['name']} x {$item['quantity']}</span>
                        <span>₹" . number_format($item['price'] * $item['quantity'], 2) . "</span>
                      </li>";
            }
            echo "</ul>";
            echo "</div>";
        }
    } else {
        echo "<div class='alert alert-info'>You haven't placed any orders yet.</div>";
    }
    ?>
</div>

</body>
</html>
