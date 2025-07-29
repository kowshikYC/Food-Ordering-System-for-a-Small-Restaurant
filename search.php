<?php
session_start();
include 'db.php';

$searchTerm = isset($_GET['q']) ? trim($_GET['q']) : '';

// Search query for food items
$stmt = $conn->prepare("
    SELECT * FROM food_items 
    WHERE name LIKE CONCAT('%', ?, '%') 
    OR description LIKE CONCAT('%', ?, '%')
    ORDER BY name
");
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$results = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - CravFoods</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .search-container {
            padding: 20px 0;
            background: #f8f9fa;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .search-results {
            margin-top: 20px;
        }
        .food-card {
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        .food-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .no-results {
            text-align: center;
            padding: 50px;
            color: #6c757d;
        }
    </style>
</head>
<body>

<!-- Navigation Bar (Same as your index.php) -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="food_images/cravfoods_logo.png" alt="CravFoods Logo" width="50" height="50" class="me-2">
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="fa fa-fw fa-home"></i> Home</a></li>
                <li class="nav-item"><a class="nav-link" href="menu.php"><i class="fa fa-fw fa-cutlery"></i> Menu</a></li>
                <li class="nav-item"><a class="nav-link" href="account.php"><i class="fa fa-fw fa-user"></i> Account</a></li>
                <li class="nav-item"><a class="nav-link" href="help.php"><i class="fa fa-fw fa-question-circle"></i> Help</a></li>
            </ul>
            <form class="d-flex" action="search.php" method="GET">
                <div class="input-group">
                    <input class="form-control" type="search" name="q" placeholder="Search foods..." 
                           value="<?= htmlspecialchars($searchTerm) ?>" aria-label="Search">
                    <button class="btn btn-outline-light" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</nav>

<!-- Search Results Section -->
<div class="container">
    <div class="search-container">
        <h2>Search food item <?= htmlspecialchars($searchTerm) ?></h2>
    </div>
    
    <div class="search-results">
        <?php if ($results->num_rows > 0): ?>
            <div class="row">
                <?php while ($item = $results->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="card food-card h-100">
                            <img src="uploads/<?= htmlspecialchars($item['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['name']) ?>" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                                <p class="card-text text-muted"><?= htmlspecialchars($item['description']) ?></p>
                                <p class="card-text"><strong>₹<?= number_format($item['price'], 2) ?></strong></p>
                                <a href="add_to_cart.php?item_id=<?= $item['id'] ?>" class="btn btn-warning">Add to Cart</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-results">
                <i class="fa fa-search fa-4x mb-3"></i>
                <h3>No results found</h3>
                <p>We couldn't find any items matching "<?= htmlspecialchars($searchTerm) ?>"</p>
                <a href="menu.php" class="btn btn-primary">Browse Full Menu</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>