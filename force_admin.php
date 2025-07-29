<?php
include("db.php");

$username = 'admin';
$password = 'admin123';

// Delete if already exists
mysqli_query($conn, "DELETE FROM admins WHERE username = '$username'");

// Generate new hash
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert new admin
$stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashed_password);
if ($stmt->execute()) {
    echo "✅ Admin inserted successfully with password: $password";
} else {
    echo "❌ Failed to insert admin: " . $stmt->error;
}
