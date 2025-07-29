<?php
session_start();
include("db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all feedback (for list)
$feedback_query = "SELECT f.*, u.name AS user_name FROM feedback f
                   LEFT JOIN users u ON f.user_id = u.id
                   ORDER BY f.created_at DESC";
$feedback_result = mysqli_query($conn, $feedback_query);

// Fetch last 10 feedbacks for chart
$chart_query = "SELECT created_at, delivery_rating, taste_rating FROM feedback ORDER BY created_at DESC LIMIT 10";
$chart_result = mysqli_query($conn, $chart_query);

$labels = [];
$delivery = [];
$taste = [];

while ($row = mysqli_fetch_assoc($chart_result)) {
    $labels[] = date('d M', strtotime($row['created_at']));
    $delivery[] = (int)$row['delivery_rating'];
    $taste[] = (int)$row['taste_rating'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Feedback - CravFoods</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
            border-radius: 10px;
        }
        .feedback-item {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #fff;
            transition: box-shadow 0.3s;
        }
        .feedback-item:hover {
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        .rating {
            color: #ffc107;
            font-size: 1.1rem;
        }
        .feedback-text {
            font-size: 1rem;
            color: #333;
        }
    </style>
</head>
<body>

<!-- Navbar -->
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
                <li class="nav-item"><a class="nav-link" href="admin_orders.php"><i class="fa fa-list"></i> Orders</a></li>
                <li class="nav-item"><a class="nav-link active" href="admin_feedback.php"><i class="fa fa-comments"></i> Feedback</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_support.php"><i class="fa fa-briefcase"></i> Job Applications</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_coupons.php"><i class="fa fa-fw fa-gift"></i> Coupons</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Feedback Section -->
<div class="container mt-5">
    <h2 class="mb-4"><i class="fa fa-comments"></i> Customer Feedback</h2>
    
    <!-- Feedback Ratings Chart -->
    <div class="mb-5">
        <h5>Recent Feedback Ratings Overview</h5>
        <canvas id="feedbackChart" height="100"></canvas>
    </div>
    
    <div class="card p-4">
        <?php if (mysqli_num_rows($feedback_result) > 0): ?>
            <div class="list-group">
                <?php while ($feedback = mysqli_fetch_assoc($feedback_result)): ?>
                    <div class="list-group-item feedback-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-1"><i class="fa fa-user-circle me-2"></i><?= htmlspecialchars($feedback['user_name'] ?? 'Anonymous') ?></h5>
                            <small class="text-muted"><i class="fa fa-clock me-1"></i><?= date("d M Y, h:i A", strtotime($feedback['created_at'])) ?></small>
                        </div>
                        
                        <div class="my-2">
                            <span class="me-4">
                                <strong>Delivery:</strong>
                                <span class="rating"><?= str_repeat('★', $feedback['delivery_rating']) . str_repeat('☆', 5 - $feedback['delivery_rating']) ?></span>
                            </span>
                            <span>
                                <strong>Taste:</strong>
                                <span class="rating"><?= str_repeat('★', $feedback['taste_rating']) . str_repeat('☆', 5 - $feedback['taste_rating']) ?></span>
                            </span>
                        </div>
                        
                        <div class="feedback-text mt-3">
                            <p><?= nl2br(htmlspecialchars($feedback['feedback_text'])) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info mb-0">No feedback received yet.</div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const ctx = document.getElementById('feedbackChart').getContext('2d');
const feedbackChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_reverse($labels)) ?>,
        datasets: [
            {
                label: 'Delivery Rating',
                backgroundColor: '#ffc107',
                data: <?= json_encode(array_reverse($delivery)) ?>
            },
            {
                label: 'Taste Rating',
                backgroundColor: '#0d6efd',
                data: <?= json_encode(array_reverse($taste)) ?>
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
            }
        },
        scales: {
            y: {
                suggestedMin: 0,
                suggestedMax: 5,
                ticks: { stepSize: 1 }
            }
        }
    }
});
</script>
</body>
</html>
