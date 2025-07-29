<?php
session_start();
include 'db.php';

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
    $delivery_rating = $_POST['delivery_rating'];
    $taste_rating = $_POST['taste_rating'];
    $feedback = $_POST['feedback'];
    $user_id = $_SESSION['user_id'] ?? null;

    $stmt = $conn->prepare("INSERT INTO feedback (user_id, delivery_rating, taste_rating, feedback_text, created_at) 
                            VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiis", $user_id, $delivery_rating, $taste_rating, $feedback);
    $stmt->execute();
    $feedback_success = $stmt->affected_rows > 0;
    $stmt->close();
}

// Handle job application submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_job_application'])) {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("INSERT INTO job_applications (name, mobile, email, address, reason, applied_at) 
                            VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $name, $mobile, $email, $address, $reason);
    $stmt->execute();
    $application_success = $stmt->affected_rows > 0;
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Help & Support - CravFoods</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background: #f1f1f1;
        }
        .help-section {
            margin-bottom: 40px;
            padding: 20px;
            border-radius: 8px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .rating-stars {
            font-size: 24px;
            color: #ffc107; /* golden */
            cursor: pointer;
        }
        .rating-stars i {
            transition: color 0.2s;
        }
        .rating-stars i.active .fa-star {
            display: inline;
        }
        .rating-stars i.active {
            color: #ffc107;
        }
        .contact-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="food_images/cravfoods_logo.png" alt="CravFoods Logo" width="50" height="50" class="me-2">
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="fa fa-fw fa-home"></i> Home</a></li>
                <li class="nav-item"><a class="nav-link" href="menu.php"><i class="fa fa-fw fa-cutlery"></i> Menu</a></li>
                <li class="nav-item"><a class="nav-link" href="account.php"><i class="fa fa-fw fa-user"></i> Account</a></li>
                <li class="nav-item"><a class="nav-link active" href="help.php"><i class="fa fa-fw fa-question-circle"></i> Help</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h1 class="mb-4">Help & Support</h1>

    <!-- Contact Details -->
    <div class="help-section">
        <h2><i class="fa fa-phone"></i> Contact Details</h2>
        <div class="contact-details mt-3">
            <p><strong>Admin Contact:</strong> 9398868142 </p>
            <p><strong>Email:</strong> admin@cravfoods.com</p>
            <p><strong>Available:</strong> 6:30 AM - 11:59 PM (Everyday)</p>
        </div>
    </div>

    <!-- Feedback Form -->
    <div class="help-section">
        <h2><i class="fa fa-comment"></i> Give Us Feedback</h2>
        <?php if (isset($feedback_success) && $feedback_success): ?>
            <div class="alert alert-success">Thank you for your feedback!</div>
        <?php endif; ?>
        
        <form method="POST" class="mt-3">
            <div class="mb-3">
                <label class="form-label">Delivery Experience Rating</label>
                <div class="rating-stars" id="deliveryRating">
                    <i class="fa fa-star-o" data-rating="1"></i>
                    <i class="fa fa-star-o" data-rating="2"></i>
                    <i class="fa fa-star-o" data-rating="3"></i>
                    <i class="fa fa-star-o" data-rating="4"></i>
                    <i class="fa fa-star-o" data-rating="5"></i>
                    <input type="hidden" name="delivery_rating" id="deliveryRatingInput" value="0">
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Food Taste Rating</label>
                <div class="rating-stars" id="tasteRating">
                    <i class="fa fa-star-o" data-rating="1"></i>
                    <i class="fa fa-star-o" data-rating="2"></i>
                    <i class="fa fa-star-o" data-rating="3"></i>
                    <i class="fa fa-star-o" data-rating="4"></i>
                    <i class="fa fa-star-o" data-rating="5"></i>
                    <input type="hidden" name="taste_rating" id="tasteRatingInput" value="0">
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Your Feedback & Suggestions</label>
                <textarea class="form-control" name="feedback" rows="4" required></textarea>
            </div>
            
            <button type="submit" name="submit_feedback" class="btn btn-primary">Submit Feedback</button>
        </form>
    </div>

    <!-- Job Application Form -->
    <div class="help-section">
        <h2><i class="fa fa-briefcase"></i> Delivery Job Opportunity</h2>
        <?php if (isset($application_success) && $application_success): ?>
            <div class="alert alert-success">Your application has been submitted successfully!</div>
        <?php endif; ?>
        
        <form method="POST" class="mt-3">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mobile Number</label>
                    <input type="tel" class="form-control" name="mobile" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Full Address</label>
                    <input type="text" class="form-control" name="address" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Why do you want to work with us?</label>
                <textarea class="form-control" name="reason" rows="3" required></textarea>
            </div>
            
            <div class="alert alert-info">
                <p><strong>Salary:</strong> ₹15,000 to ₹20,000 based on performance</p>
                <p>By clicking "Apply Now", you agree to the salary terms mentioned above.</p>
            </div>
            
            <button type="submit" name="submit_job_application" class="btn btn-success">Apply Now</button>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Rating stars functionality
document.querySelectorAll('.rating-stars').forEach(container => {
    const inputId = container.id + 'Input';
    const input = document.getElementById(inputId);

    container.querySelectorAll('i').forEach((star, index) => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            input.value = rating;

            container.querySelectorAll('i').forEach((s, idx) => {
                if (idx < rating) {
                    s.classList.add('fa-star');
                    s.classList.remove('fa-star-o');
                } else {
                    s.classList.add('fa-star-o');
                    s.classList.remove('fa-star');
                }
            });
        });
    });
});
</script>

</body>
</html>
