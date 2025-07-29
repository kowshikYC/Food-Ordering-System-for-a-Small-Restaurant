<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include 'db.php'; 

$userId = $_SESSION['user_id'];

// Get user details from the database
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// If user is not found, you can handle it here (optional)
if (!$user) {
    // Optionally redirect or display an error
    header("Location: login.php");
    exit();
}

$name = $user['name']; // Get the name from the database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CravFoods | Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .card:hover {
            transform: scale(1.05);
            transition: 0.3s;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .category-title {
            text-align: center;
            margin-top: 20px;
        }
        .card-img-top {
            height: 250px;
            object-fit: cover;
        }
        .navbar-brand img {
            margin-right: 10px;
        }
        .carousel-inner img {
            height: 600px;
           object-fit: cover;
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
          <a class="nav-link active" href="index.php">
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
        <li class="nav-item"><a class="nav-link" href="help.php">
          <i class="fa fa-fw fa-question-circle"></i> Help</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Carousel -->
<div id="foodCarousel" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="food_images/banner1.png" class="d-block w-100" alt="Delicious Food 1">
    </div>
    <div class="carousel-item">
      <img src="food_images/banner2.0.png" class="d-block w-100" alt="Delicious Food 2">
    </div>
    <div class="carousel-item">
      <img src="food_images/banner3.png" class="d-block w-100" alt="Delicious Food 3">
    </div>
    <div class="carousel-item">
      <img src="food_images/banner4.png" class="d-block w-100" alt="Delicious Food 4">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#foodCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#foodCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

<!-- Category Section -->
<div class="container">
    <h2 class="category-title">What's On Your Mind, <?= htmlspecialchars($name) ?>?</h2>
    <div class="row row-cols-1 row-cols-md-4 g-4 mt-4">
        <?php
        $categories = [
            ["Biryani", "biryani.jpg", "biryani"],
            ["Pizza", "pizza.avif", "pizza"],
            ["Burger", "burger.webp", "burger"],
            ["Shawarma", "shawarma.jpg", "shawarma"],
            ["Fried Rice", "friedrice.jpg", "friedrice"],
            ["Seafood", "seafood.webp", "seafood"],
            ["Grill/BBQ", "grill.jpg", "grill"],
            ["Cakes", "cakes.webp", "cakes"],
            ["Shakes", "shakes.jpg", "shakes"],
            ["Parotta", "parotta.jpg", "parotta"],
            ["Pulka", "pulka.jpg", "pulka"],
            ["Meals", "meals.webp", "meals"]
        ];

        foreach ($categories as $cat) {
          $name = $cat[0];
          $image = $cat[1];
          $key = urlencode($cat[2]); // Just in case
      
          echo '
          <div class="col">
              <div class="card h-100 shadow">
                  <img src="food_images/' . $image . '" class="card-img-top" alt="' . $name . '">
                  <div class="card-body text-center">
                      <h5 class="card-title">' . $name . '</h5>
                      <a href="menu.php?category=' . $key . '" class="btn btn-warning">View Items</a>
                  </div>
              </div>
          </div>';
      }
      ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
