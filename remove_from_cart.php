<?php
session_start();

if (isset($_GET['food_id']) && isset($_SESSION['cart'][$_GET['food_id']])) {
    unset($_SESSION['cart'][$_GET['food_id']]);
}

header("Location: cart.php");
exit();
