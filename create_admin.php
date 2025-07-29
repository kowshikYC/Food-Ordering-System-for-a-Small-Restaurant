<?php
include("db.php");

// Admin credentials
$username = 'admin';  // Admin username
$password = 'admin123';  // Admin password (choose your own)

// Check if the admin already exists
$query = "SELECT * FROM admins WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Admin with this username already exists!";
} else {
    // Hash the password before saving it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert query
    $insertQuery = "INSERT INTO admins (username, password) VALUES (?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param("ss", $username, $hashed_password);

    if ($insertStmt->execute()) {
        echo "Admin user created successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
