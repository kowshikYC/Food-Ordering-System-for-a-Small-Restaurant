<?php
session_start();
include("db.php"); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart | CravFoods</title>
    <link rel="stylesheet" href="foodorder.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
   
    <style>
    .navbar {
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        /*background-color: #2c3e50 !important; */
    }

    .navbar-brand img {
        transition: transform 0.3s ease;
    }

    .navbar-brand:hover img {
        transform: scale(1.05);
    }

    .nav-link {
        font-weight: 500;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
        position: relative;
        color: white !important; /* Always white */
    }

    .nav-link:hover, .nav-link.active {
        color: white !important; /* On hover and active, stay white */
    }

    .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 1rem;
        right: 1rem;
        height: 2px;
        background-color: white;
    }
    </style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="food_images/cravfoods_logo.png" alt="CravFoods Logo" width="50" height="50" class="me-2">
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
            <i class="fa fa-fw fa-home"></i> Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="search.php">
            <i class="fa fa-fw fa-search"></i> Search
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="account.php">
            <i class="fa fa-fw fa-user"></i> Account
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="cart.php">
            <i class="fa fa-fw fa-shopping-cart"></i> Cart
          </a>
        </li>
       <li class="nav-item">
          <a class="nav-link" href="help.php">
            <i class="fa fa-fw fa-question-circle"></i> Help
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- 🛒 Cart Section -->
<div class="container mt-5">
    <h2>Your Cart</h2>

    <?php if (!empty($cart)): ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Food Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($cart as $food_id => $quantity):
                    $query = "SELECT * FROM food_items WHERE id = ?";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, "i", $food_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($result && mysqli_num_rows($result) > 0) {
                        $item = mysqli_fetch_assoc($result);
                        $subtotal = $item['price'] * $quantity;
                        $total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>₹<?= number_format($item['price'], 2) ?></td>
                    <td>
                        <a href="update_quantity.php?food_id=<?= $food_id ?>&action=decrease" class="btn btn-sm btn-outline-secondary">−</a>
                        <span class="mx-2"><?= $quantity ?></span>
                        <a href="update_quantity.php?food_id=<?= $food_id ?>&action=increase" class="btn btn-sm btn-outline-secondary">+</a>
                    </td>
                    <td>₹<?= number_format($subtotal, 2) ?></td>
                    <td>
                        <a href="remove_from_cart.php?food_id=<?= $food_id ?>" class="btn btn-danger btn-sm">Remove</a>
                    </td>
                </tr>
                <?php
                    } else {
                        echo "<tr><td colspan='5'>Item not found in the database. Please try again later.</td></tr>";
                    }
                endforeach;
                ?>
                <tr class="table-warning">
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td colspan="2"><strong>₹<?= number_format($total, 2) ?></strong></td>
                </tr>
            </tbody>
        </table>

        <a href="menu.php" class="btn btn-primary">← Back to Menu</a>
        <a href="checkout.php" class="btn btn-success">Place Order</a>
        <a href="clear_cart.php" class="btn btn-secondary">Clear Cart</a>

    <?php else: ?>
        <p class="alert alert-info">Your cart is empty. <a href="menu.php">Browse Menu</a></p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
