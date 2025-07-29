<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | CravFoods</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<!-- Admin Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="admin_dashboard.php">
            <img src="food_images/cravfoods_logo.png" alt="CravFoods Logo" width="50" height="50" class="me-2">
            <span>Admin Panel</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="admin_orders.php"><i class="fa fa-fw fa-list"></i> Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_feedback.php"><i class="fa fa-fw fa-comments"></i> Feedback</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_support.php"><i class="fa fa-fw fa-briefcase"></i> Job Applications</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_coupons.php"><i class="fa fa-fw fa-gift"></i> Coupons</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_logout.php"><i class="fa fa-fw fa-sign-out"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Admin Dashboard Content -->
<div class="container mt-5">
    <h2 class="mb-4">Welcome, <?= $_SESSION['admin_username'] ?>!</h2>
    <div class="row">
        <div class="col-md-6">
            <div class="list-group">
                <a href="admin_orders.php" class="list-group-item list-group-item-action">
                    <i class="fa fa-list"></i> View All Orders
                </a>
                <a href="admin_feedback.php" class="list-group-item list-group-item-action">
                    <i class="fa fa-comments"></i> View Customer Feedback
                </a>
                <a href="admin_support.php" class="list-group-item list-group-item-action">
                    <i class="fa fa-briefcase"></i> View Job Applications
                </a>
                <a href="admin_coupons.php" class="list-group-item list-group-item-action">
                    <i class="fa fa-gift"></i> Manage Coupons
                </a>
                <a href="admin_logout.php" class="list-group-item list-group-item-action text-danger">
                    <i class="fa fa-sign-out"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
