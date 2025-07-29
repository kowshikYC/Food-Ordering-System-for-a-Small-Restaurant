<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$user_result = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($user_result);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("UPDATE users SET name=?, mobile=?, address=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $mobile, $address, $user_id);
    $stmt->execute();
    $stmt->close();

    // Update session data
    $_SESSION['user_name'] = $name;
}

// Get user details
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
$stmt->close();

// Get order history
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orderResult = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - CravFoods</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #ff6b6b;
            --secondary-color: #ff8e8e;
            --dark-color: #333;
            --light-color: #f8f9fa;
            --success-color: #28a745;
            --info-color: #17a2b8;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }
        
        /* Navbar Styles */
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            /*background-color: #2c3e50 !important; */
            padding: 0.5rem 1rem;
        }
        
        .navbar-brand {
            font-weight: bold;
            display: flex;
            align-items: center;
        }
        
        .navbar-brand img {
            margin-right: 10px;
            transition: transform 0.3s ease;
            height: 50px;
            width: 50px;
        }
        
        .navbar-brand:hover img {
            transform: scale(1.05);
        }
        
        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .nav-link i {
            margin-right: 6px;
            font-size: 1.1rem;
        }
        
        .nav-link:hover, .nav-link.active {
            color: white !important;
        }
        
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        /* Rest of the styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: var(--dark-color);
            line-height: 1.6;
            padding-top: 0; /* Remove padding since navbar is fixed */
        }
        
        .account-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        }
        
        h1, h2, h3 {
            font-weight: 700;
            color: var(--dark-color);
        }
        
        h1 {
            color: var(--primary-color);
            position: relative;
            padding-bottom: 0.5rem;
        }
        
        h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border-radius: 2px;
        }
        
        h2 {
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #eee;
        }
        
        .profile-section {
            margin-bottom: 3rem;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--primary-color);
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(255, 107, 107, 0.25);
        }
        
        .btn {
            border-radius: 8px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
        }
        
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }
        
        .order-table {
            width: 100%;
            margin-top: 1.5rem;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .order-table th {
            background-color: #2c3e50;
            color: white;
            padding: 1rem;
            position: sticky;
            top: 0;
        }
        
        .order-table td {
            padding: 1rem;
            vertical-align: top;
            border-bottom: 1px solid #eee;
        }
        
        .order-table tr:hover td {
            background-color: rgba(255, 107, 107, 0.05);
        }
        
        .order-items-cell {
            max-width: 300px;
        }
        
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 0.3rem 0;
            border-bottom: 1px dashed #eee;
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: capitalize;
        }
        
        .status-processing { 
            background-color: var(--warning-color); 
            color: #212529; 
        }
        
        .status-delivered { 
            background-color: var(--success-color); 
            color: white; 
        }
        
        .status-cancelled { 
            background-color: var(--danger-color); 
            color: white; 
        }
        
        /* 🎟️ Coupon Ticket Styles */
        .coupon-section {
            margin: 3rem 0;
        }
        
        .ticket-card {
            position: relative;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 2px dashed var(--primary-color);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .ticket-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.1) 0%, rgba(255, 107, 107, 0.05) 100%);
            z-index: 0;
        }
        
        .ticket-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }
        
        .ticket-code {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            position: relative;
            z-index: 1;
        }
        
        .ticket-discount {
            font-size: 1.2rem;
            margin: 0.8rem 0;
            font-weight: 600;
            color: var(--dark-color);
            position: relative;
            z-index: 1;
        }
        
        .ticket-expiry {
            font-size: 0.9rem;
            color: #6c757d;
            position: relative;
            z-index: 1;
        }
        
        .copy-btn {
            margin-top: 1.2rem;
            width: 100%;
            border-radius: 30px;
            font-weight: 600;
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }
        
        .copy-btn:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .account-container {
                padding: 1.5rem;
            }
            
            .profile-section {
                padding: 1.5rem;
            }
            
            .navbar-nav {
                margin-top: 1rem;
            }
            
            .nav-link {
                padding: 0.5rem 0;
            }
            
            .order-table {
                display: block;
                overflow-x: auto;
            }
        }
        
        /* Animation for status badges */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .status-processing {
            animation: pulse 2s infinite;
        }
        
        /* Edit mode styling */
        .edit-mode {
            box-shadow: 0 0 0 2px var(--primary-color);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="food_images/cravfoods_logo.png" alt="CravFoods Logo" width="50" height="50" class="me-2">
            <span>CravFoods</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav" aria-controls="navbarNav" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
      
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="menu.php">
                        <i class="fas fa-utensils"></i> Menu
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="account.php">
                        <i class="fas fa-user"></i> Account
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cart.php">
                        <i class="fas fa-shopping-cart"></i> Cart
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="help.php">
                        <i class="fas fa-question-circle"></i> Help
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Account Container -->
<div class="container">
    <div class="account-container">
        <h1 class="mb-4"><i class="fas fa-user-circle me-2"></i>My Account</h1>

        <!-- Profile Section -->
        <div class="profile-section">
            <h2 class="mb-4"><i class="fas fa-id-card me-2"></i>Profile Details</h2>
            <form method="post" id="profileForm">
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($customer['name'] ?? '') ?>" required disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" value="<?= htmlspecialchars($customer['email'] ?? '') ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="tel" class="form-control" name="mobile" value="<?= htmlspecialchars($customer['mobile'] ?? '') ?>" required disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            <textarea class="form-control" name="address" rows="2" required disabled><?= htmlspecialchars($customer['address'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary" id="editProfileBtn">
                        <i class="fas fa-edit me-1"></i> Edit Profile
                    </button>
                    <button type="submit" name="update_profile" class="btn btn-success" id="saveProfileBtn" style="display: none;">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="cancelEditBtn" style="display: none;">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                </div>
            </form>
        </div>

        <!-- Coupon Section -->
        <div class="coupon-section">
            <h2 class="mb-4"><i class="fas fa-tags me-2"></i>Available Coupons</h2>

            <div class="row">
            <?php
                $today = date('Y-m-d');
                $coupon_query = "
                    SELECT c.* 
                    FROM coupons c
                    INNER JOIN user_coupons uc ON c.id = uc.coupon_id
                    WHERE uc.user_id = $user_id 
                    AND c.is_active = 1 
                    AND c.expiry_date >= '$today'
                ";
                $coupon_result = mysqli_query($conn, $coupon_query);

                if (mysqli_num_rows($coupon_result) > 0):
                    while ($coupon = mysqli_fetch_assoc($coupon_result)):
            ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="ticket-card">
                        <div class="ticket-code"><?= htmlspecialchars($coupon['code']) ?></div>
                        <div class="ticket-discount">
                            <?= $coupon['discount_type'] == 'Percentage' ? $coupon['discount_value'] . '% OFF' : '₹' . $coupon['discount_value'] . ' OFF' ?>
                        </div>
                        <div class="ticket-expiry">
                            <i class="far fa-clock me-1"></i> Expires: <?= date('M j, Y', strtotime($coupon['expiry_date'])) ?>
                        </div>
                        <div class="ticket-expiry">
                            <i class="fas fa-gift me-1"></i> 
                            <?= $coupon['discount_type'] == 'Percentage' ? 
                                'Save '.$coupon['discount_value'].'% on your order' : 
                                'Get ₹'.$coupon['discount_value'].' off your order' ?>
                        </div>
                            <button class="btn btn-outline-primary copy-btn" data-code="<?= htmlspecialchars($coupon['code']) ?>">
                            <i class="far fa-copy me-1"></i> Copy Code
                        </button>
                    </div>
                </div>
            <?php endwhile; else: ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> No available coupons at the moment. Check back later!
                    </div>
                </div>
            <?php endif; ?>
            </div>
        </div>

        <!-- Order History Section -->
        <h2 class="mb-4"><i class="fas fa-history me-2"></i>Order History</h2>
        <?php if ($orderResult->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="order-table table table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($order = $orderResult->fetch_assoc()): ?>
                        <tr>
                            <td class="fw-bold">#<?= $order['id'] ?></td>
                            <td class="order-items-cell">
                                <?php
                                $itemStmt = $conn->prepare("
                                    SELECT fi.name, oi.quantity, oi.price 
                                    FROM order_items oi
                                    JOIN food_items fi ON oi.food_id = fi.id
                                    WHERE oi.order_id = ?
                                ");
                                $itemStmt->bind_param("i", $order['id']);
                                $itemStmt->execute();
                                $itemResult = $itemStmt->get_result();
                                if ($itemResult->num_rows > 0):
                                    echo "<div class='d-flex flex-column gap-1'>";
                                    while ($item = $itemResult->fetch_assoc()) {
                                        echo "<div class='d-flex justify-content-between'>";
                                        echo "<span>" . htmlspecialchars($item['name']) . "</span>";
                                        echo "<span>x" . (int)$item['quantity'] . " (₹" . number_format($item['price'], 2) . ")</span>";
                                        echo "</div>";
                                    }
                                    echo "</div>";
                                else:
                                    echo "<em>No items found</em>";
                                endif;
                                $itemStmt->close();
                                ?>
                            </td>
                            <td class="fw-bold">₹<?= number_format($order['total_amount'], 2) ?></td>
                            <td><?= htmlspecialchars($order['delivery_address']) ?></td>
                            <td><span class="status-badge <?= 'status-' . strtolower($order['status']) ?>"><?= ucfirst($order['status']) ?></span></td>
                            <td><?= date('M j, Y g:i A', strtotime($order['order_date'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> You haven't placed any orders yet. <a href="menu.php" class="alert-link">Browse our menu</a> to get started!
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editBtn = document.getElementById('editProfileBtn');
        const saveBtn = document.getElementById('saveProfileBtn');
        const cancelBtn = document.getElementById('cancelEditBtn');
        const formInputs = document.querySelectorAll('#profileForm input, #profileForm textarea');
        const profileSection = document.querySelector('.profile-section');
        const originalValues = {};

        // Store original values
        formInputs.forEach(input => {
            if (input.name) {
                originalValues[input.name] = input.value;
            }
        });

        // Enable profile editing
        editBtn.addEventListener('click', function() {
            formInputs.forEach(input => input.disabled = false);
            editBtn.style.display = 'none';
            saveBtn.style.display = 'block';
            cancelBtn.style.display = 'block';
            profileSection.classList.add('edit-mode');
        });

        // Cancel editing
        cancelBtn.addEventListener('click', function() {
            formInputs.forEach(input => {
                input.disabled = true;
                if (input.name && originalValues[input.name] !== undefined) {
                    input.value = originalValues[input.name];
                }
            });
            editBtn.style.display = 'block';
            saveBtn.style.display = 'none';
            cancelBtn.style.display = 'none';
            profileSection.classList.remove('edit-mode');
        });

        // Copy coupon code
        const copyButtons = document.querySelectorAll('.copy-btn');
        copyButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const code = this.getAttribute('data-code');
                navigator.clipboard.writeText(code).then(() => {
                    // Show feedback
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check me-1"></i> Copied!';
                    this.classList.remove('btn-outline-primary');
                    this.classList.add('btn-success');
                    
                    // Reset after 2 seconds
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.add('btn-outline-primary');
                        this.classList.remove('btn-success');
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy coupon: ', err);
                });
            });
        });
    });
</script>
</body>
</html>