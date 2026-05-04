<?php
session_start();

// Connect to database
$conn = mysqli_connect("localhost", "root", "", "ClothingStore");

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all clothes using associative array
$sql    = "SELECT * FROM tblClothes";
$result = mysqli_query($conn, $sql);
$total  = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop All – Pastimes</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<!-- Announcement bar -->
<div class="announcement-bar">
    Free delivery on orders over R500 🚚
</div>

<!-- Navbar -->
<nav class="navbar">
    <a href="index.php" class="logo">Pastimes</a>

    <div class="nav-links">
        <a href="shop.php">Shop</a>
        <a href="shop.php">Dresses</a>
        <a href="shop.php">Tops</a>
        <a href="shop.php">Jeans</a>
        <a href="shop.php">Shoes</a>
    </div>

    <div class="search-bar">
        <span>🔍</span>
        <input type="text" placeholder="Search for brands, styles...">
    </div>

    <div class="nav-right">
        <a href="register.php" class="btn btn-pink">Sell Now</a>
        <a href="#" class="nav-icon">♡</a>
        <a href="#" class="nav-icon">🛍</a>
        <?php if(isset($_SESSION['username'])): ?>
            <a href="profile.php" class="nav-icon">👤</a>
        <?php else: ?>
            <a href="login.php" class="nav-icon">👤</a>
        <?php endif; ?>
    </div>
</nav>

<!-- Shop layout with sidebar -->
<div class="shop-layout">

    <!-- LEFT SIDEBAR FILTERS -->
    <div class="shop-sidebar">
        <div class="filter-group">
            <h4>Category</h4>
            <?php
            $cats = ["Dresses"=>234,"Tops"=>567,"Jeans"=>189,"Jackets"=>145,"Shoes"=>312,"Accessories"=>423];
            foreach($cats as $name => $count):
            ?>
            <label>
                <div><input type="checkbox"> <?php echo $name; ?></div>
                <span>(<?php echo $count; ?>)</span>
            </label>
            <?php endforeach; ?>
        </div>

        <div class="filter-group">
            <h4>Condition</h4>
            <?php foreach(["Like New","Good","Fair"] as $cond): ?>
            <label><input type="checkbox"> <?php echo $cond; ?></label>
            <?php endforeach; ?>
        </div>

        <div class="filter-group">
            <h4>Size</h4>
            <div class="size-grid">
                <?php foreach(["XS","S","M","L","XL","XXL","26","28","30","32","34","36","38","40"] as $s): ?>
                <button class="size-btn"><?php echo $s; ?></button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- MAIN PRODUCT AREA -->
    <div class="shop-main">
        <div class="shop-toolbar">
            <div>
                <h2>Shop All</h2>
                <p><?php echo $total; ?> items available</p>
            </div>
        </div>

        <!-- Product grid - tblClothes read as associative array -->
        <div class="product-grid">
            <?php while($item = mysqli_fetch_assoc($result)): ?>
            <div class="product-card">
                <div class="card-img">
                    <!-- Product image -->
                    <img src="<?php echo $item['image_url']; ?>"
                         alt="<?php echo $item['title']; ?>"
                         onerror="this.src='https://placehold.co/200x220/fce4ee/d4537e?text=<?php echo urlencode($item['title']); ?>'">
                    <span class="badge-new">New</span>
                </div>
                <div class="card-info">
                    <div class="card-brand"><?php echo strtoupper($item['brand']); ?></div>
                    <div class="card-title"><?php echo $item['title']; ?></div>
                    <div class="card-meta">
                        <?php echo $item['size']; ?> &bull; <?php echo $item['condition_type']; ?>
                    </div>
                    <div class="card-price">
                        R<?php echo number_format($item['price'], 2); ?>
                    </div>
                    <!-- Add to Cart - shows price in popup as per rubric -->
                    <button onclick="addToCart('<?php echo addslashes($item['title']); ?>', <?php echo $item['price']; ?>)"
                            class="btn btn-pink" style="width:100%; padding:9px; font-size:13px;">
                        🛒 Add to Cart
                    </button>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    <div class="footer-inner">
        <div class="footer-logo">Pastimes</div>
        <div class="footer-copy">© 2026 Pastimes – Pre-loved Fashion. All rights reserved.</div>
    </div>
</footer>

<script>
// Shows item name and sell price in popup - rubric requirement
function addToCart(name, price){
    alert("🛒 Added to Cart!\n\nItem: " + name + "\nSell Price: R" + parseFloat(price).toFixed(2));
}
</script>

</body>
</html>