<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$user_result = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($user_result);

// Fetch cart
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
if (empty($cart)) {
    header("Location: cart.php");
    exit();
}

// Calculate total
$total = 0;
$cart_details = [];
foreach ($cart as $food_id => $qty) {
    $item_result = mysqli_query($conn, "SELECT * FROM food_items WHERE id = $food_id");
    $item = mysqli_fetch_assoc($item_result);
    $subtotal = $item['price'] * $qty;
    $total += $subtotal;
    $cart_details[] = [
        'food_id' => $food_id,
        'name' => $item['name'],
        'price' => $item['price'],
        'qty' => $qty,
        'subtotal' => $subtotal
    ];
}

$coupon_applied = "";
$discount = 0;
$final_total = $total;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $coupon_code = mysqli_real_escape_string($conn, $_POST['coupon']);

    // Check coupon
    if (!empty($coupon_code)) {
        $coupon_q = "SELECT * FROM coupons WHERE code = '$coupon_code' AND is_active = 1";
        $coupon_result = mysqli_query($conn, $coupon_q);

        if (mysqli_num_rows($coupon_result) > 0) {
            $coupon = mysqli_fetch_assoc($coupon_result);
            $coupon_applied = $coupon['code'];

            // Check if first-time only and used before
            if ($coupon['is_first_time_only']) {
                $check_used = mysqli_query($conn, "SELECT * FROM used_coupons WHERE user_id = $user_id AND coupon_id = {$coupon['id']}");
                if (mysqli_num_rows($check_used) > 0) {
                    $coupon_applied = "";
                } else {
                    if ($coupon['discount_type'] == 'Flat') {
                        $discount = $coupon['discount_value'];
                    } else {
                        $discount = ($total * $coupon['discount_value']) / 100;
                    }
                    // Track usage
                    mysqli_query($conn, "INSERT INTO used_coupons(user_id, coupon_id) VALUES($user_id, {$coupon['id']})");
                }
            } else {
                if ($coupon['discount_type'] == 'Flat') {
                    $discount = $coupon['discount_value'];
                } else {
                    $discount = ($total * $coupon['discount_value']) / 100;
                }
            }
        }
    }

    $final_total = max(0, $total - $discount);

    // Insert into orders
    $order_q = "INSERT INTO orders (user_id, total_amount, coupon_applied, delivery_address) 
                VALUES ($user_id, $final_total, '$coupon_applied', '$address')";
    mysqli_query($conn, $order_q);
    $order_id = mysqli_insert_id($conn);

    // Insert order items
    foreach ($cart_details as $item) {
        $q = "INSERT INTO order_items (order_id, food_id, quantity, price) 
              VALUES ($order_id, {$item['food_id']}, {$item['qty']}, {$item['price']})";
        mysqli_query($conn, $q);
    }

    // Clear cart
    unset($_SESSION['cart']);

    echo "<script>alert('Order placed successfully!'); window.location='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout | CravFoods</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        :root {
            --primary-color: #ff6b6b;
            --secondary-color: #ff8e8e;
            --dark-color: #333;
            --light-color: #f8f9fa;
            --success-color: #28a745;
            --info-color: #17a2b8;
        }
        
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }
        
        .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: white !important;
        }
        
        .container {
            max-width: 800px;
            margin-bottom: 50px;
        }
        
        h2 {
            color: var(--dark-color);
            margin-bottom: 20px;
            font-weight: 700;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(255, 107, 107, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
            border-radius: 8px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-secondary {
            border-radius: 8px;
            padding: 10px 25px;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
        }
        
        .list-group-item {
            border-radius: 8px !important;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }
        
        .list-group-item:hover {
            transform: translateX(5px);
        }
        
        #applyCouponBtn {
            margin-left: 10px;
        }
        
        .coupon-success {
            color: var(--success-color);
            font-weight: 600;
        }
        
        .order-summary {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-top: 20px;
        }
        
        .checkout-form {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        
        .total-amount {
            font-size: 1.2rem;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }
            
            #applyCouponBtn {
                margin-left: 0;
                margin-top: 10px;
                width: 100%;
            }
        }
    </style>
</head>
<body>

<!-- Navbar Start -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="food_images/cravfoods_logo.png" alt="CravFoods Logo" width="40" height="40" class="d-inline-block align-text-top rounded-circle me-2">
        <span>CravFoods</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i> Home</a>
        </li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="account.php"><i class="fas fa-user me-1"></i> My Account</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="cart.php"><i class="fas fa-shopping-cart me-1"></i> Cart</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
            </li>
        <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt me-1"></i> Login</a>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<!-- Navbar End -->

<!-- In the Checkout Section -->
<div class="container mt-5">
    <h2><i class="fas fa-cash-register me-2"></i>Checkout</h2>
    
    <div class="checkout-form mb-4">
        <form method="POST" id="checkoutForm">  <!-- Added id here -->
            <div class="mb-4">
                <label for="address" class="form-label fw-bold">Delivery Address</label>
                <textarea name="address" id="address" class="form-control" rows="3" required><?= htmlspecialchars($user['address']) ?></textarea>
            </div>

            <div class="mb-4">
                <label for="coupon" class="form-label fw-bold">Apply Coupon Code</label>
                <div class="d-flex align-items-center">
                    <input type="text" name="coupon" id="coupon" class="form-control" placeholder="Enter coupon code">
                    <button type="button" id="applyCouponBtn" class="btn btn-primary"><i class="fas fa-tag me-1"></i>Apply</button>
                </div>
                <div id="couponMessage" class="mt-2"></div>
            </div>
            
            <!-- Moved the order summary inside the form -->
            <div class="order-summary">
                <h4 class="mb-4"><i class="fas fa-receipt me-2"></i>Order Summary</h4>
                <ul class="list-group mb-4">
                    <?php foreach ($cart_details as $item): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-bold"><?= htmlspecialchars($item['name']) ?></span>
                                <span class="text-muted ms-2">x <?= $item['qty'] ?></span>
                            </div>
                            <div>₹<?= number_format($item['subtotal'], 2) ?></div>
                        </li>
                    <?php endforeach; ?>
                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <strong>Subtotal</strong>
                        <strong id="totalPrice">₹<?= number_format($total, 2) ?></strong>
                    </li>
                    <?php if ($discount > 0): ?>
                        <li class="list-group-item d-flex justify-content-between text-success">
                            <span><i class="fas fa-tag me-1"></i>Discount (<?= $coupon_applied ?>)</span>
                            <span id="discountAmount">- ₹<?= number_format($discount, 2) ?></span>
                        </li>
                    <?php endif; ?>
                    <li class="list-group-item d-flex justify-content-between text-danger total-amount">
                        <strong>Total Payable</strong>
                        <strong id="finalTotal">₹<?= number_format($total - $discount, 2) ?></strong>
                    </li>
                </ul>

                <div class="d-flex justify-content-between">
                    <a href="cart.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Back to Cart</a>
                    <button type="submit" class="btn btn-success"><i class="fas fa-check me-1"></i> Place Order</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#applyCouponBtn').on('click', function() {
            var couponCode = $('#coupon').val();

            if (couponCode.trim() === "") {
                $('#couponMessage').html('<div class="alert alert-warning">Please enter a coupon code.</div>');
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'apply_coupon.php',
                data: { coupon: couponCode },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        // Update UI with new total and discount
                        if ($('#discountAmount').length === 0) {
                            // Add discount row if it doesn't exist
                            $('.list-group-item.bg-light').after(
                                '<li class="list-group-item d-flex justify-content-between text-success">' +
                                '<span><i class="fas fa-tag me-1"></i>Discount (' + data.coupon_code + ')</span>' +
                                '<span id="discountAmount">- ₹' + data.discount + '</span>' +
                                '</li>'
                            );
                        } else {
                            $('#discountAmount').text('- ₹' + data.discount);
                        }
                        
                        $('#totalPrice').text('₹' + data.total);
                        $('#finalTotal').text('₹' + data.final_total);
                        $('#couponMessage').html('<div class="alert alert-success">Coupon applied successfully!</div>');
                    } else {
                        $('#couponMessage').html('<div class="alert alert-danger">' + data.message + '</div>');
                    }
                },
                error: function() {
                    $('#couponMessage').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                }
            });
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>