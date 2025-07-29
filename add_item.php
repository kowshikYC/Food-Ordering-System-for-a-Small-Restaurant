<?php
// add_item.php
session_start();
include("db.php");

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = (float) $_POST['price'];
    $category_id = (int) $_POST['category'];

    // Handle image upload
    $image_name = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $target_path = "uploads/" . basename($image_name);

    if (move_uploaded_file($tmp_name, $target_path)) {
        // Insert into DB
        $sql = "INSERT INTO food_items (name, price, image, category_id) 
                VALUES ('$name', '$price', '$image_name', '$category_id')";
        if (mysqli_query($conn, $sql)) {
            $msg = "Food item added successfully!";
        } else {
            $msg = "Database error: " . mysqli_error($conn);
        }
    } else {
        $msg = "Failed to upload image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Food Item | CravFoods</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h2>Add New Food Item</h2>
    
    <?php if (isset($msg)) echo "<div class='alert alert-info'>$msg</div>"; ?>

    <form action="add_item.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Food Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Price (₹)</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Category</label>
            <select name="category" class="form-control" required>
                <option value="">-- Select Category --</option>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM categories");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='{$row['id']}'>" . ucfirst($row['name']) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" accept="image/*" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Item</button>
    </form>
</body>
</html>
