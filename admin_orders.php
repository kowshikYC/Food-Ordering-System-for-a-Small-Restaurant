<?php
session_start();
include("db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['order_id'], $_POST['new_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];
    $update_query = "UPDATE orders SET status='$new_status' WHERE id=$order_id";
    mysqli_query($conn, $update_query);
}

// Fetch orders
$order_query = "SELECT orders.*, users.name, users.mobile FROM orders 
                JOIN users ON orders.user_id = users.id 
                ORDER BY orders.order_date DESC";
$order_result = mysqli_query($conn, $order_query);

// Chart Data: Last 7 days (weekly)
$weekly_labels = [];
$weekly_data = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $weekly_labels[] = date('M d', strtotime($date));
    $q = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE DATE(order_date) = '$date'");
    $data = mysqli_fetch_assoc($q);
    $weekly_data[] = (int)$data['count'];
}

// Chart Data: Last 30 days (monthly chart, day-wise)
$monthly_labels = [];
$monthly_data = [];
for ($i = 29; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $monthly_labels[] = date('M d', strtotime($date));
    $q = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders WHERE DATE(order_date) = '$date'");
    $data = mysqli_fetch_assoc($q);
    $monthly_data[] = (int)$data['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders | CravFoods</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 30px;
        }
        .table-container {
            overflow-x: auto;
        }
        .table {
            border-radius: 8px;
            overflow: hidden;
        }
        .table thead th {
            background-color: #343a40;
            color: white;
            font-weight: 600;
            border: none;
        }
        .table tbody tr {
            transition: all 0.3s ease;
        }
        .table tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
            transform: translateY(-2px);
        }
        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .status-pending { color: #ffc107; font-weight: 600; }
        .status-processing { color: #17a2b8; font-weight: 600; }
        .status-delivered { color: #28a745; font-weight: 600; }
        .form-select { min-width: 120px; }
        .btn-update { white-space: nowrap; }
        h2 {
            color: #343a40;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        .chart-container {
            margin: 15px 0 40px 0;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 1px 8px rgba(0,0,0,0.04);
            padding: 24px 28px 10px 28px;
        }
        .chart-dropdown {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 0.5rem;
        }
        @media (max-width: 600px) {
            .chart-container { padding: 12px 4px 8px 4px;}
            .chart-dropdown { justify-content: center; }
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
                <li class="nav-item"><a class="nav-link active" href="admin_orders.php"><i class="fa fa-fw fa-list"></i> Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_feedback.php"><i class="fa fa-fw fa-comments"></i> Feedback</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_support.php"><i class="fa fa-fw fa-briefcase"></i> Job Applications</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_coupons.php"><i class="fa fa-fw fa-gift"></i> Coupons</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_logout.php"><i class="fa fa-fw fa-sign-out"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2><i class="fas fa-clipboard-list me-2"></i>All Orders</h2>
    
    <!-- Chart area with dropdown -->
    <div class="chart-container mb-4">
        <div class="chart-dropdown">
            <select id="chartRange" class="form-select w-auto" style="font-size: 15px;">
                <option value="weekly">Weekly (Last 7 days)</option>
                <option value="monthly">Monthly (Last 30 days)</option>
            </select>
        </div>
        <h5><i class="fa fa-chart-line"></i> Orders Performance</h5>
        <canvas id="ordersChart" height="103"></canvas>
    </div>

    <div class="table-container">
        <table class="table table-hover align-middle">
            <thead>
            <tr>
                <th>#ID</th>
                <th>User</th>
                <th>Mobile</th>
                <th>Amount</th>
                <th>Address</th>
                <th>Status</th>
                <th>Ordered On</th>
                <th>Change Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($order_result)) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['mobile']) ?></td>
                    <td>₹<?= number_format($row['total_amount'], 2) ?></td>
                    <td><?= htmlspecialchars(substr($row['delivery_address'], 0, 20)) ?>...</td>
                    <td>
                        <span class="status-<?= strtolower($row['status']) ?>">
                            <?= $row['status'] ?>
                        </span>
                    </td>
                    <td><?= date('M j, Y g:i A', strtotime($row['order_date'])) ?></td>
                    <td>
                        <form method="POST" class="d-flex align-items-center">
                            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                            <select name="new_status" class="form-select me-2">
                                <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Processing" <?= $row['status'] == 'Processing' ? 'selected' : '' ?>>Processing</option>
                                <option value="Delivered" <?= $row['status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary btn-update">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </form>
                    </td>
                    <td>
                        <a href="order_details.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> Details
                        </a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Data provided from PHP
    const weeklyLabels = <?= json_encode($weekly_labels) ?>;
    const weeklyData = <?= json_encode($weekly_data) ?>;
    const monthlyLabels = <?= json_encode($monthly_labels) ?>;
    const monthlyData = <?= json_encode($monthly_data) ?>;

    let chartInstance = null;

    function renderChart(labels, data) {
        const ctx = document.getElementById('ordersChart').getContext('2d');
        if (chartInstance) {
            chartInstance.destroy();
        }
        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Orders per Day',
                    data: data,
                    backgroundColor: 'rgba(13, 110, 253, 0.7)',
                    borderRadius: 8,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top', display: false },
                    title: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }

    // Initial chart
    renderChart(weeklyLabels, weeklyData);

    document.getElementById('chartRange').addEventListener('change', function() {
        if (this.value === 'weekly') {
            renderChart(weeklyLabels, weeklyData);
        } else {
            renderChart(monthlyLabels, monthlyData);
        }
    });
</script>
</body>
</html>
