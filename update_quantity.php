<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_GET['food_id']) && isset($_GET['action'])) {
    $food_id = (int)$_GET['food_id'];
    $action = $_GET['action'];

    if (isset($_SESSION['cart'][$food_id])) {
        if ($action === 'increase') {
            $_SESSION['cart'][$food_id]++;
        } elseif ($action === 'decrease') {
            $_SESSION['cart'][$food_id]--;
            // Remove if quantity becomes 0 or less
            if ($_SESSION['cart'][$food_id] <= 0) {
                unset($_SESSION['cart'][$food_id]);
            }
        }
    }
}

header("Location: cart.php");
exit();
