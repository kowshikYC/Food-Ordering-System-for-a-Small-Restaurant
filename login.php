<?php
include("db.php");
session_start();

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE mobile = '$mobile'";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) == 1) {
        $user = mysqli_fetch_assoc($res);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'];
            header("Location: index.php");
            exit();
        } else {
            $msg = "Invalid password!";
        }
    } else {
        $msg = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
   <style>
    </style>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light" >
    <div class="container mt-5 col-md-6">
        <h2 class="text-center">Login</h2>
        <?php if ($msg != "") echo "<div class='alert alert-danger'>$msg</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Mobile Number</label>
                <input type="tel" name="mobile" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Login</button>
            <a href="signup.php" class="btn btn-link">New user? Register here</a>
        </form>
    </div>
</body>
</html>
