<?php
session_start();
include("db.php");

$response = ['success' => false, 'message' => 'Invalid coupon', 'discount' => 0, 'total' => 0, 'final_total' => 0];

// Check if coupon code is provided
if (isset($_POST['coupon']) && !empty($_POST['coupon'])) {
    $coupon_code = mysqli_real_escape_string($conn, $_POST['coupon']);

    // Fetch cart details from session
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    if (empty($cart)) {
        $response['message'] = "Cart is empty!";
        echo json_encode($response);
        exit();
    }

    // Calculate total
    $total = 0;
    foreach ($cart as $food_id => $qty) {
        $item_result = mysqli_query($conn, "SELECT * FROM food_items WHERE id = $food_id");
        $item = mysqli_fetch_assoc($item_result);
        $subtotal = $item['price'] * $qty;
        $total += $subtotal;
    }

    // Check coupon in the database
    $coupon_q = "SELECT * FROM coupons WHERE code = '$coupon_code' AND is_active = 1";
    $coupon_result = mysqli_query($conn, $coupon_q);

    if (mysqli_num_rows($coupon_result) > 0) {
        $coupon = mysqli_fetch_assoc($coupon_result);

        // Validate if it's for first-time use
        $user_id = $_SESSION['user_id'];
        if ($coupon['is_first_time_only']) {
            $check_used = mysqli_query($conn, "SELECT * FROM used_coupons
             WHERE user_id = $user_id AND coupon_id = {$coupon['id']}");
            if (mysqli_num_rows($check_used) > 0) {
                $response['message'] = "Coupon already used.";
                echo json_encode($response);
                exit();
            }
        }

        // Calculate discount based on type
        if ($coupon['discount_type'] == 'Flat') {
            $discount = $coupon['discount_value'];
        } else {
            $discount = ($total * $coupon['discount_value']) / 100;
        }

        // Final total after discount
        $final_total = max(0, $total - $discount);

        // Prepare response data
        $response['success'] = true;
        $response['message'] = "Coupon applied successfully!";
        $response['discount'] = number_format($discount, 2);
        $response['total'] = number_format($total, 2);
        $response['final_total'] = number_format($final_total, 2);

        // Optionally, track coupon usage for first-time only coupons
        if ($coupon['is_first_time_only']) {
            mysqli_query($conn, "INSERT INTO used_coupons(user_id, coupon_id) VALUES($user_id, {$coupon['id']})");
        }
    } else {
        $response['message'] = "Invalid or expired coupon code.";
    }
}

echo json_encode($response);
?>
