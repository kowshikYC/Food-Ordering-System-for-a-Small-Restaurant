<?php
session_start();
include("db.php");

if (!isset($_SESSION['admin_id'])) {
    die("Unauthorized access");
}

$appId = $_GET['id'] ?? 0;
$query = "SELECT * FROM job_applications WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $appId);
$stmt->execute();
$result = $stmt->get_result();
$application = $result->fetch_assoc();

if ($application): ?>
    <div class="row">
        <div class="col-md-6">
            <p><strong>Name:</strong> <?= htmlspecialchars($application['name']) ?></p>
            <p><strong>Mobile:</strong> <?= htmlspecialchars($application['mobile']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($application['email']) ?></p>
        </div>
        <div class="col-md-6">
            <p><strong>Applied On:</strong> <?= date("d M Y, h:i A", strtotime($application['applied_at'])) ?></p>
            <p><strong>Current Status:</strong> 
                <span class="badge bg-<?= 
                    $application['status'] == 'hired' ? 'success' : 
                    ($application['status'] == 'rejected' ? 'danger' : 'warning') 
                ?>">
                    <?= ucfirst($application['status']) ?>
                </span>
            </p>
        </div>
    </div>
    
    <div class="mt-3">
        <h5>Address</h5>
        <p><?= nl2br(htmlspecialchars($application['address'])) ?></p>
    </div>
    
    <div class="mt-3">
        <h5>Reason for Applying</h5>
        <p><?= nl2br(htmlspecialchars($application['reason'])) ?></p>
    </div>
<?php else: ?>
    <div class="alert alert-danger">Application not found!</div>
<?php endif; ?>