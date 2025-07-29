<?php
// admin_login.php
session_start();
include("db.php");

// Enable error reporting (for development only)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$error = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Use prepared statements for security
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $username = trim($_POST['username']);  
    $password = trim($_POST['password']);  

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($admin = $result->fetch_assoc()) {
        // Check if the provided password matches the hashed password from DB
        echo "<pre>";
        echo "Username entered: '$username'\n";
        echo "Password entered: '$password'\n";
        echo "Password hash from DB: '{$admin['password']}'\n";

        if (password_verify($password, $admin['password'])) {
            echo "✅ Password Matched!";
        } else {
            echo "❌ Password NOT Matched!";
        }

        // After password verification, continue with session setup and redirection
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: admin_dashboard.php");
        exit(); // This is the correct place to exit after the redirect
    } else {
        $error = "Invalid password.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | CravFoods</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center mb-4">Admin Login</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="mx-auto" style="max-width: 400px;">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>

</body>
</html>
