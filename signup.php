<?php
include("db.php");

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if user exists (based on mobile or email)
    $check = mysqli_query($conn, "SELECT * FROM users WHERE mobile = '$mobile' OR email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $msg = "User already exists. Please login!";
    } else {
        $sql = "INSERT INTO users (name, mobile, email, address, password) 
                VALUES ('$name', '$mobile', '$email', '$address', '$password')";
        if (mysqli_query($conn, $sql)) {
            $msg = "Registered successfully! Please login.";
        } else {
            $msg = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 col-md-6">
        <h2 class="text-center">Sign Up</h2>
        <?php if ($msg != "") echo "<div class='alert alert-info'>$msg</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Mobile Number</label>
                <input type="tel" name="mobile" class="form-control" pattern="[6-9]{1}[0-9]{9}" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
            <a href="login.php" class="btn btn-link">Already have an account?</a>
        </form>
    </div>
</body>
</html>
