<?php
// menu.php
session_start();
include ("db.php");
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$category = strtolower($category);
$category_title = $category ? ucfirst($category) . ' Items' : 'Our Full Menu';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $category_title; ?> | CravFoods</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .card:hover { transform: scale(1.05); transition: 0.3s; }
        .navbar-brand { font-weight: bold; }
        .category-title { text-align: center; margin-top: 20px; }
        .card-img-top { height: 250px; object-fit: cover; }
        .navbar-brand img { margin-right: 10px; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="food_images/cravfoods_logo.png" alt="CravFoods Logo" width="50" height="50" class="me-2">
    </a>
    <div class="collapse navbar-collapse" id="navbarNav">
    <form class="d-flex" action="search.php" method="GET">
                <div class="input-group">
                    <input type="text" name="query" placeholder="Search Menu Items..." value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>" required>
                    <button class="btn btn-outline-light" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php"><i class="fa fa-fw fa-home"></i> Home</a></li>
        <li class="nav-item"><a class="nav-link active" href="menu.php"><i class="fa fa-fw fa-cutlery"></i> Menu</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php"><i class="fa fa-shopping-cart"></i> Cart</a></li>
        <li class="nav-item"><a class="nav-link" href="account.php"><i class="fa fa-fw fa-user"></i> Account</a></li>
        <li class="nav-item"><a class="nav-link" href="help.php"><i class="fa fa-fw fa-question-circle"></i> Help</a></li>
        </ul>
    </div>
  </div>
</nav>

<!-- Items -->
<div class="container">
    <h2 class="category-title"><?php echo $category_title; ?></h2>
    <div class="row row-cols-1 row-cols-md-4 g-4 mt-4">
        <?php
        $items = [
          "biryani" => [
              ["id" => 1, "name" => "Chicken Biryani", "price" => 180, "image" => "chicken_biryani.jpg"],
              ["id" => 2, "name" => "Mutton Biryani", "price" => 220, "image" => "mutton_biryani.jpg"],
              ["id" => 3, "name" => "Veg Biryani", "price" => 150, "image" => "veg_biryani.avif"],
              ["id" => 4, "name" => "Prawns Biryani", "price" => 230, "image" => "prawns_biryani.jpg"],
              ["id" => 5, "name" => "Hyderabadi Biryani", "price" => 200, "image" => "hyd_biryani.jpg"],
              ["id" => 6, "name" => "Egg Biryani", "price" => 160, "image" => "egg_biryani.jpg"],
              ["id" => 7, "name" => "Paneer Biryani", "price" => 170, "image" => "paneer_biryani.jpg"],
              ["id" => 8, "name" => "Kolkata Biryani", "price" => 210, "image" => "kolkata_biryani.jpg"],
              ["id" => 9, "name" => "Dum Biryani", "price" => 190, "image" => "dum_biryani.jpg"],
              ["id" => 10, "name" => "Special Combo Biryani", "price" => 250, "image" => "combo_biryani.jpg"]
          ],
          "pizza" => [
              ["id" => 11, "name" => "Margherita Pizza", "price" => 160, "image" => "margherita.jpg"],
              ["id" => 12, "name" => "Cheese Burst Pizza", "price" => 200, "image" => "cheese_burst.webp"],
              ["id" => 13, "name" => "Farmhouse Pizza", "price" => 220, "image" => "farmhouse.avif"],
              ["id" => 14, "name" => "Paneer Pizza", "price" => 230, "image" => "paneer_pizza.png"],
              ["id" => 15, "name" => "Tandoori Chicken Pizza", "price" => 250, "image" => "tandoori_chicken.webp"],
              ["id" => 16, "name" => "Veggie Supreme", "price" => 210, "image" => "veggie_supreme.jpg"],
              ["id" => 17, "name" => "Peri Peri Pizza", "price" => 240, "image" => "peri_peri.jpeg"],
              ["id" => 18, "name" => "BBQ Chicken Pizza", "price" => 260, "image" => "bbq_chicken.jpg"],
              ["id" => 19, "name" => "Mushroom Pizza", "price" => 190, "image" => "mushroom_pizza.jpg"],
              ["id" => 20, "name" => "Pepperoni Pizza", "price" => 270, "image" => "pepperoni.jpg"]
          ],
          "burger" => [
              ["id" => 21, "name" => "Veg Burger", "price" => 120, "image" => "veg_burger.jpg"],
              ["id" => 22, "name" => "Cheese Burger", "price" => 150, "image" => "cheese_burger.jpg"],
              ["id" => 23, "name" => "Chicken Burger", "price" => 180, "image" => "chicken_burger.webp"],
              ["id" => 24, "name" => "Double Patty Burger", "price" => 220, "image" => "double_patty.jpg"],
              ["id" => 25, "name" => "Grilled Burger", "price" => 200, "image" => "grilled_burger.jpg"],
              ["id" => 26, "name" => "Paneer Burger", "price" => 160, "image" => "paneer_burger.jpg"],
              ["id" => 27, "name" => "Aloo Tikki Burger", "price" => 130, "image" => "aloo_tikki.avif"],
              ["id" => 28, "name" => "Spicy Chicken Burger", "price" => 190, "image" => "spicy_chicken.webp"],
              ["id" => 29, "name" => "Egg Burger", "price" => 140, "image" => "egg_burger.jpg"],
              ["id" => 30, "name" => "Zinger Burger", "price" => 210, "image" => "zinger.jpg"]
          ],
          "shawarma" => [
              ["id" => 31, "name" => "Classic Chicken Shawarma", "price" => 130, "image" => "classic_shawarma.jpg"],
              ["id" => 32, "name" => "Spicy Shawarma", "price" => 150, "image" => "spicy_shawarma.webp"],
              ["id" => 33, "name" => "Paneer Shawarma", "price" => 140, "image" => "paneer_shawarma.jpeg"],
              ["id" => 34, "name" => "Cheese Shawarma", "price" => 160, "image" => "cheese_shawarma.jpg"],
              ["id" => 35, "name" => "Egg Shawarma", "price" => 120, "image" => "egg_shawarma.jpg"],
              ["id" => 36, "name" => "BBQ Shawarma", "price" => 170, "image" => "bbq_shawarma.jpg"],
              ["id" => 37, "name" => "Garlic Mayo Shawarma", "price" => 135, "image" => "garlic_mayo.jpeg"],
              ["id" => 38, "name" => "Loaded Shawarma", "price" => 180, "image" => "loaded_shawarma.jpg"],
              ["id" => 39, "name" => "Roll Shawarma", "price" => 145, "image" => "roll_shawarma.webp"],
              ["id" => 40, "name" => "Combo Shawarma", "price" => 200, "image" => "combo_shawarma.jpg"]
          ],
          "friedrice" => [
              ["id" => 41, "name" => "Veg Fried Rice", "price" => 120, "image" => "veg_friedrice.jpg"],
              ["id" => 42, "name" => "Egg Fried Rice", "price" => 130, "image" => "egg_friedrice.jpg"],
              ["id" => 43, "name" => "Chicken Fried Rice", "price" => 150, "image" => "chicken_friedrice.jpg"],
              ["id" => 44, "name" => "Paneer Fried Rice", "price" => 140, "image" => "paneer_friedrice.webp"],
              ["id" => 45, "name" => "Mixed Fried Rice", "price" => 170, "image" => "mixed_friedrice.jpg"],
              ["id" => 46, "name" => "Garlic Fried Rice", "price" => 135, "image" => "garlic_friedrice.jpg"],
              ["id" => 47, "name" => "Schezwan Fried Rice", "price" => 160, "image" => "schezwan.webp"],
              ["id" => 48, "name" => "Mushroom Fried Rice", "price" => 140, "image" => "mushroom_friedrice.jpg"],
              ["id" => 49, "name" => "Tandoori Fried Rice", "price" => 150, "image" => "tandoori_rice.jpg"],
              ["id" => 50, "name" => "Chinese Combo", "price" => 190, "image" => "combo_rice.webp"]
          ],
          "seafood" => [
              ["id" => 51, "name" => "Fish Fry", "price" => 200, "image" => "fish_fry.jpg"],
              ["id" => 52, "name" => "Prawn Curry", "price" => 250, "image" => "prawn_curry.jpg"],
              ["id" => 53, "name" => "Crab Masala", "price" => 280, "image" => "crab.avif"],
              ["id" => 54, "name" => "Fish Biryani", "price" => 230, "image" => "fish_biryani.jpg"],
              ["id" => 55, "name" => "Seafood Platter", "price" => 300, "image" => "seafood_platter.jpg"],
              ["id" => 56, "name" => "Tandoori Fish", "price" => 240, "image" => "tandoori_fish.jpg"],
              ["id" => 57, "name" => "Prawn Fry", "price" => 220, "image" => "prawn_fry.jpg"],
              ["id" => 58, "name" => "Fish Curry", "price" => 210, "image" => "fish_curry.jpg"],
              ["id" => 59, "name" => "Crab Fry", "price" => 270, "image" => "crab_fry.webp"],
              ["id" => 60, "name" => "Spicy Prawn Rice", "price" => 230, "image" => "spicy_prawn.jpg"]
          ],
          "grill" => [
              ["id" => 61, "name" => "Grilled Chicken", "price" => 250, "image" => "grilled_chicken.jpg"],
              ["id" => 62, "name" => "BBQ Wings", "price" => 200, "image" => "bbq_wings.jpg"],
              ["id" => 63, "name" => "Paneer Tikka", "price" => 180, "image" => "paneer_tikka.jpg"],
              ["id" => 64, "name" => "Grilled Fish", "price" => 260, "image" => "grilled_fish.jpg"],
              ["id" => 65, "name" => "Tandoori Chicken", "price" => 230, "image" => "tandoori_chicken.jpg"],
              ["id" => 66, "name" => "BBQ Paneer", "price" => 200, "image" => "bbq_paneer.jpg"],
              ["id" => 67, "name" => "Chicken Seekh Kebab", "price" => 220, "image" => "seekh_kebab.jpg"],
              ["id" => 68, "name" => "Mutton Kebab", "price" => 280, "image" => "mutton_kebab.jpg"],
              ["id" => 69, "name" => "Stuffed Mushrooms", "price" => 190, "image" => "stuffed_mushrooms.webp"],
              ["id" => 70, "name" => "Mixed Grill Platter", "price" => 320, "image" => "grill_platter.jpg"]
          ],
          "cakes" => [
              ["id" => 71, "name" => "Chocolate Cake", "price" => 150, "image" => "chocolate_cake.jpg"],
              ["id" => 72, "name" => "Black Forest", "price" => 170, "image" => "black_forest.webp"],
              ["id" => 73, "name" => "Red Velvet", "price" => 180, "image" => "red_velvet.jpg"],
              ["id" => 74, "name" => "Pineapple Cake", "price" => 160, "image" => "pineapple.avif"],
              ["id" => 75, "name" => "Butterscotch", "price" => 175, "image" => "butterscotch.webp"],
              ["id" => 76, "name" => "Vanilla Cake", "price" => 140, "image" => "vanilla.avif"],
              ["id" => 77, "name" => "Fruit Cake", "price" => 190, "image" => "fruit.jpg"],
              ["id" => 78, "name" => "Rainbow Cake", "price" => 210, "image" => "rainbow.webp"],
              ["id" => 79, "name" => "Cup Cakes", "price" => 120, "image" => "cupcakes.webp"],
              ["id" => 80, "name" => "Cheesecake", "price" => 220, "image" => "cheesecake.jpg"]
          ],
          "shakes" => [
              ["id" => 81, "name" => "Chocolate Shake", "price" => 120, "image" => "choco_shake.jpg"],
              ["id" => 82, "name" => "Strawberry Shake", "price" => 130, "image" => "strawberry_shake.webp"],
              ["id" => 83, "name" => "Mango Shake", "price" => 125, "image" => "mango_shake.jpg"],
              ["id" => 84, "name" => "Vanilla Shake", "price" => 110, "image" => "vanilla_shake.jpg"],
              ["id" => 85, "name" => "Oreo Shake", "price" => 140, "image" => "oreo_shake.webp"],
              ["id" => 86, "name" => "Banana Shake", "price" => 115, "image" => "banana_shake.avif"],
              ["id" => 87, "name" => "Cold Coffee", "price" => 100, "image" => "cold_coffee.jpg"],
              ["id" => 88, "name" => "KitKat Shake", "price" => 150, "image" => "kitkat.jpg"],
              ["id" => 89, "name" => "Butterscotch Shake", "price" => 130, "image" => "butterscotch_shake.webp"],
              ["id" => 90, "name" => "Dry Fruit Shake", "price" => 160, "image" => "dryfruit.png"]
          ],
          "parotta" => [
              ["id" => 91, "name" => "Veg Parotta", "price" => 40, "image" => "veg_parotta.jpg"],
              ["id" => 92, "name" => "Chicken Kothu Parotta", "price" => 100, "image" => "kothu_chicken.jpg"],
              ["id" => 93, "name" => "Egg Kothu Parotta", "price" => 90, "image" => "egg_kothu.jpg"],
              ["id" => 94, "name" => "Parotta with Curry", "price" => 80, "image" => "parotta_curry.avif"],
              ["id" => 95, "name" => "Layered Parotta", "price" => 50, "image" => "layered.jpeg"],
              ["id" => 96, "name" => "Mutton Kothu", "price" => 120, "image" => "mutton_kothu.jpg"],
              ["id" => 97, "name" => "Cheese Parotta", "price" => 70, "image" => "cheese_parotta.jpg"],
              ["id" => 98, "name" => "Garlic Parotta", "price" => 60, "image" => "garlic_parotta.jpg"],
              ["id" => 99, "name" => "Double Egg Parotta", "price" => 95, "image" => "double_egg.jpg"],
              ["id" => 100, "name" => "Paneer Parotta", "price" => 85, "image" => "paneer_parotta.jpg"]
          ],
          "pulka" => [
              ["id" => 101, "name" => "Plain Pulka", "price" => 30, "image" => "plain_pulka.jpg"],
              ["id" => 102, "name" => "Pulka with Dal", "price" => 50, "image" => "pulka_dal.jpg"],
              ["id" => 103, "name" => "Pulka with Chicken Curry", "price" => 90, "image" => "pulka_chicken.webp"],
              ["id" => 104, "name" => "Pulka with Paneer", "price" => 80, "image" => "pulka_paneer.webp"],
              ["id" => 105, "name" => "Stuffed Pulka", "price" => 70, "image" => "stuffed_pulka.jpg"],
              ["id" => 106, "name" => "Butter Pulka", "price" => 60, "image" => "butter_pulka.avif"],
              ["id" => 107, "name" => "Garlic Pulka", "price" => 65, "image" => "garlic_pulka.jpg"],
              ["id" => 108, "name" => "Tandoori Pulka", "price" => 75, "image" => "tandoori_pulka.avif"],
              ["id" => 109, "name" => "Mix Veg Pulka", "price" => 85, "image" => "mixveg_pulka.jpg"],
              ["id" => 110, "name" => "Ragi Pulka", "price" => 100, "image" => "ragi_pulka.webp"]
          ],
          "meals" => [
              ["id" => 111, "name" => "Veg Meals", "price" => 100, "image" => "veg_meals.avif"],
              ["id" => 112, "name" => "Chicken Meals", "price" => 150, "image" => "chicken_meals.jpg"],
              ["id" => 113, "name" => "Mutton Meals", "price" => 200, "image" => "mutton_meals.webp"],
              ["id" => 114, "name" => "Fish Meals", "price" => 180, "image" => "fish_meals.jpg"],
              ["id" => 115, "name" => "Egg Meals", "price" => 130, "image" => "egg_meals.avif"],
              ["id" => 116, "name" => "Prawn Meals", "price" => 220, "image" => "prawn_meals.png"],
              ["id" => 117, "name" => "Combo Meals", "price" => 250, "image" => "combo_meals.jpg"],
              ["id" => 118, "name" => "Tandoori Chicken Meals", "price" => 240, "image" => "tandoori_meals.png"],
              ["id" => 119, "name" => "Biryani Meals", "price" => 230, "image" => "biryani_meals.jpg"],
              ["id" => 120, "name" => "Special Meals", "price" => 300, "image" => "special_meals.jpg"]
          ]
      ];
      
      if ($category && array_key_exists($category, $items)) {
          // Display items for a specific category
          foreach ($items[$category] as $food) {
              echo '
              <div class="col">
                  <div class="card h-100 shadow">
                      <img src="uploads/' . $food['image'] . '" class="card-img-top" alt="' . $food['name'] . '">
                      <div class="card-body text-center">
                          <h5 class="card-title">' . $food['name'] . '</h5>
                          <p class="card-text">₹' . $food['price'] . '</p>
                          <a href="add_to_cart.php?food_id=' . $food['id'] . '" class="btn btn-warning">Add to Cart</a>
                      </div>
                  </div>
              </div>';
          }
      } else {
          // Display all items when no category is specified or category doesn't exist
          $all_items = [];
          foreach ($items as $category_items) {
              $all_items = array_merge($all_items, $category_items);
          }
          
          foreach ($all_items as $food) {
              echo '
              <div class="col">
                  <div class="card h-100 shadow">
                      <img src="uploads/' . $food['image'] . '" class="card-img-top" alt="' . $food['name'] . '">
                      <div class="card-body text-center">
                          <h5 class="card-title">' . $food['name'] . '</h5>
                          <p class="card-text">₹' . $food['price'] . '</p>
                          <a href="add_to_cart.php?food_id=' . $food['id'] . '" class="btn btn-warning">Add to Cart</a>
                      </div>
                  </div>
              </div>';
          }
      }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>