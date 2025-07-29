<?php
session_start();
include("db.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$applications_query = "SELECT * FROM job_applications ORDER BY applied_at DESC";
$applications_result = mysqli_query($conn, $applications_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Applications - CravFoods</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
        .badge {
            font-size: 0.85rem;
        }
        .modal-header {
            background-color: #343a40;
            color: white;
        }
        .modal-footer .btn {
            min-width: 100px;
        }
    </style>
</head>
<body>

<!-- Navbar (same as admin_dashboard.php) -->
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
                <li class="nav-item"><a class="nav-link" href="admin_feedback.php"><i class="fa fa-comments"></i> Feedback</a></li>
                <li class="nav-item"><a class="nav-link active" href="admin_support.php"><i class="fa fa-briefcase"></i> Job Applications</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_coupons.php"><i class="fa fa-fw fa-gift"></i> Coupons</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Content -->
<div class="container mt-5">
    <h2 class="mb-4"><i class="fa fa-briefcase"></i> Delivery Job Applications</h2>

    <div class="card p-4">
        <?php if (mysqli_num_rows($applications_result) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Contact Info</th>
                            <th>Applied On</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($application = mysqli_fetch_assoc($applications_result)): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($application['name']) ?></strong></td>
                                <td>
                                    <div><i class="fa fa-phone"></i> <?= htmlspecialchars($application['mobile']) ?></div>
                                    <div><i class="fa fa-envelope"></i> <?= htmlspecialchars($application['email']) ?></div>
                                </td>
                                <td><?= date("d M Y", strtotime($application['applied_at'])) ?></td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $application['status'] == 'hired' ? 'success' : 
                                        ($application['status'] == 'rejected' ? 'danger' : 'warning') 
                                    ?>">
                                        <?= ucfirst($application['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary view-application" 
                                            data-id="<?= $application['id'] ?>" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#applicationModal">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info mb-0">No job applications received yet.</div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="applicationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-id-card"></i> Application Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="applicationDetails">
                <!-- AJAX content loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success hire-btn"><i class="fa fa-check"></i> Hire</button>
                <button type="button" class="btn btn-danger reject-btn"><i class="fa fa-times"></i> Reject</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-close"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS & Custom Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.view-application').forEach(btn => {
    btn.addEventListener('click', function () {
        const appId = this.getAttribute('data-id');
        fetch('get_application_details.php?id=' + appId)
            .then(response => response.text())
            .then(data => {
                document.getElementById('applicationDetails').innerHTML = data;
                document.getElementById('applicationModal').setAttribute('data-current-app', appId);
            });
    });
});

document.querySelector('.hire-btn').addEventListener('click', function () {
    const modal = document.getElementById('applicationModal');
    const appId = modal.getAttribute('data-current-app');
    updateApplicationStatus(appId, 'hired');
});

document.querySelector('.reject-btn').addEventListener('click', function () {
    const modal = document.getElementById('applicationModal');
    const appId = modal.getAttribute('data-current-app');
    updateApplicationStatus(appId, 'rejected');
});

function updateApplicationStatus(appId, status) {
    fetch('update_application_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + appId + '&status=' + status
    })
    .then(response => {
        if (response.ok) location.reload();
    });
}
</script>
</body>
</html>
