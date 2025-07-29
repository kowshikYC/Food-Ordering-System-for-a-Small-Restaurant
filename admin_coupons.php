<?php
session_start();
include("db.php");


if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle sending coupon
if (isset($_POST['send_coupon'])) {
    $user_id = intval($_POST['user_id']);
    $type = $_POST['coupon_type'];

    // Generate random coupon code
    $coupon_code = strtoupper(substr(md5(time() . rand()), 0, 8)); // 8-character random code

    if ($type == "general") {
        $discount_value = rand(5, 10); // 5%-10% random
        $discount_type = "Percent";
        $is_biryani_only = 0;
    } else if ($type == "biryani") {
        $discount_value = rand(5, 10);
        $discount_type = "Percent";
        $is_biryani_only = 1;
    }

    // Insert into coupons table
    $insert_coupon = "INSERT INTO coupons (code, discount_value, discount_type, is_active, is_first_time_only, is_biryani_only)
                      VALUES ('$coupon_code', $discount_value, '$discount_type', 1, 0, $is_biryani_only)";
    mysqli_query($conn, $insert_coupon);

    $coupon_id = mysqli_insert_id($conn);

    // Link coupon to the user
    mysqli_query($conn, "INSERT INTO user_coupons (user_id, coupon_id) VALUES ($user_id, $coupon_id)");

    echo "<script>alert('Coupon sent successfully!'); window.location='admin_coupons.php';</script>";
    exit();
}

// Fetch all users
$users_result = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Send Coupons | CravFoods</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #1cc88a;
            --dark-color: #5a5c69;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .navbar {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .container {
            background-color: white;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            padding: 2rem;
            margin-top: 2rem;
        }
        
        h2 {
            color: var(--dark-color);
            font-weight: 600;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 0.5rem;
        }
        
        .table {
            border-radius: 0.35rem;
            overflow: hidden;
        }
        
        .table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            border: none;
        }
        
        .table tbody tr {
            transition: all 0.2s;
        }
        
        .table tbody tr:hover {
            background-color: rgba(78, 115, 223, 0.05);
            transform: translateY(-1px);
        }
        
        .table tbody tr:nth-child(even) {
            background-color: #f8f9fc;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #3a5bd9;
            border-color: #3a5bd9;
        }
        
        .form-select {
            border-radius: 0.2rem;
            border: 1px solid #d1d3e2;
        }
        
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .coupon-type-general {
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .coupon-type-biryani {
            color: var(--secondary-color);
            font-weight: 600;
        }
        
        .address-cell {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .table-responsive {
                overflow-x: auto;
            }
            
            .flex-md-row {
                flex-direction: column !important;
            }
        }
    </style>
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
                <li class="nav-item"><a class="nav-link active" href="admin_coupons.php"><i class="fa fa-fw fa-gift"></i> Coupons</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_logout.php"><i class="fa fa-fw fa-sign-out"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container mt-5">
    <h2 class="mb-4"><i class="fas fa-gift me-2"></i>Send Coupons to Users</h2>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Address</th>
                    <th>Send Coupon</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['mobile']) ?></td>
                        <td class="address-cell" title="<?= htmlspecialchars($user['address']) ?>">
                            <?= htmlspecialchars($user['address']) ?>
                        </td>
                        <td>
                            <form method="POST" class="d-flex flex-column flex-md-row gap-2">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <select name="coupon_type" class="form-select form-select-sm" required>
                                    <option value="general" class="coupon-type-general">5%-10% Off (Any Item)</option>
                                    <option value="biryani" class="coupon-type-biryani">5%-10% Off (Only Biryani)</option>
                                </select>
                                <button type="submit" name="send_coupon" class="btn btn-primary btn-sm">
                                    <i class="fas fa-paper-plane me-1"></i> Send
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>