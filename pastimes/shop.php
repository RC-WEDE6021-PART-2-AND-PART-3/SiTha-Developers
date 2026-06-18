<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "ClothingStore");
if(!$conn){ die("Connection failed: " . mysqli_connect_error()); }

// Build filter query
$where = "WHERE 1=1";

if(isset($_GET['category']) && $_GET['category'] != ""){
    $cat    = mysqli_real_escape_string($conn, $_GET['category']);
    $where .= " AND category='$cat'";
}

if(isset($_GET['condition']) && $_GET['condition'] != ""){
    $cond   = mysqli_real_escape_string($conn, $_GET['condition']);
    $where .= " AND condition_type='$cond'";
}

if(isset($_GET['size']) && $_GET['size'] != ""){
    $size   = mysqli_real_escape_string($conn, $_GET['size']);
    $where .= " AND size='$size'";
}

// Fetch filtered clothes
$sql    = "SELECT * FROM tblClothes $where";
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
    <style>
        .active-filter {
            background: #d4537e !important;
            color: #fff !important;
            border-color: #d4537e !important;
        }
        .filter-link {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            color: #444;
            margin-bottom: 10px;
            cursor: pointer;
            text-decoration: none;
            padding: 4px 0;
        }
        .filter-link:hover { color: #d4537e; }
        .filter-link.active { color: #d4537e; font-weight: 700; }
        .filter-count { color: #bbb; font-size: 12px; }
        .clear-filters {
            display: inline-block;
            margin-bottom: 20px;
            font-size: 12px;
            color: #d4537e;
            text-decoration: none;
            border: 1px solid #d4537e;
            padding: 4px 10px;
            border-radius: 20px;
        }
    </style>
</head>
<body>

<div class="announcement-bar">Free delivery on orders over R500 🚚</div>

<nav class="navbar">
    <a href="index.php" class="logo">Pastimes</a>
    <div class="nav-links">
        <a href="shop.php">Shop</a>
        <a href="shop.php?category=Dresses">Dresses</a>
        <a href="shop.php?category=Tops">Tops</a>
        <a href="shop.php?category=Jeans">Jeans</a>
        <a href="shop.php?category=Shoes">Shoes</a>
    </div>
    <div class="search-bar">
        <span>🔍</span>
        <input type="text" placeholder="Search for brands, styles...">
    </div>
    <div class="nav-right">
        <a href="seller.php" class="btn btn-pink">Sell Now</a>
        <a href="shop.php" class="nav-icon" title="Wishlist">♡</a>
        <a href="cart.php" class="nav-icon" title="Cart">🛒</a>
        <a href="orders.php" class="nav-icon" title="My Orders">📦</a>
        <?php if(isset($_SESSION['username'])): ?>
            <a href="profile.php" class="nav-icon" title="My Profile">👤</a>
            <a href="logout.php" class="btn btn-outline" style="font-size:13px; padding:7px 14px;">Sign Out</a>
        <?php else: ?>
            <a href="login.php" class="nav-icon" title="Login">👤</a>
        <?php endif; ?>
    </div>
</nav>

<div class="shop-layout">

    <!-- SIDEBAR FILTERS -->
    <div class="shop-sidebar">

        <?php if(isset($_GET['category']) || isset($_GET['condition']) || isset($_GET['size'])): ?>
            <a href="shop.php" class="clear-filters">✕ Clear filters</a>
        <?php endif; ?>

        <!-- Category filter -->
        <div class="filter-group">
            <h4>Category</h4>
            <?php
            $cats = ["Dresses","Tops","Jeans","Jackets","Shoes","Accessories"];
            foreach($cats as $c):
                $count  = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tblClothes WHERE category='$c'"));
                $active = (isset($_GET['category']) && $_GET['category'] == $c) ? 'active' : '';
            ?>
            <a href="shop.php?category=<?php echo urlencode($c); ?><?php echo isset($_GET['condition']) ? '&condition='.$_GET['condition'] : ''; ?><?php echo isset($_GET['size']) ? '&size='.$_GET['size'] : ''; ?>"
               class="filter-link <?php echo $active; ?>">
                <span><?php echo $c; ?></span>
                <span class="filter-count">(<?php echo $count; ?>)</span>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- Condition filter -->
        <div class="filter-group">
            <h4>Condition</h4>
            <?php foreach(["Like New","Good","Fair"] as $cond):
                $active = (isset($_GET['condition']) && $_GET['condition'] == $cond) ? 'active' : '';
            ?>
            <a href="shop.php?condition=<?php echo urlencode($cond); ?><?php echo isset($_GET['category']) ? '&category='.$_GET['category'] : ''; ?>"
               class="filter-link <?php echo $active; ?>">
                <?php echo $cond; ?>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- Size filter -->
        <div class="filter-group">
            <h4>Size</h4>
            <div class="size-grid">
                <?php foreach(["XS","S","M","L","XL","XXL","26","28","30","32","34","36","38","40"] as $s):
                    $active = (isset($_GET['size']) && $_GET['size'] == $s) ? 'active-filter' : '';
                ?>
                <a href="shop.php?size=<?php echo $s; ?><?php echo isset($_GET['category']) ? '&category='.$_GET['category'] : ''; ?>"
                   class="size-btn <?php echo $active; ?>"
                   style="text-decoration:none; text-align:center;">
                    <?php echo $s; ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- MAIN PRODUCT AREA -->
    <div class="shop-main">
        <div class="shop-toolbar">
            <div>
                <h2>Shop All</h2>
                <p><?php echo $total; ?> items available
                    <?php if(isset($_GET['category'])): ?>
                        in <strong><?php echo $_GET['category']; ?></strong>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <?php if($total == 0): ?>
            <div style="text-align:center; padding:60px; color:#aaa;">
                <div style="font-size:48px; margin-bottom:16px;">🔍</div>
                <h3>No items found</h3>
                <p>Try a different filter</p>
                <a href="shop.php" class="btn btn-pink" style="margin-top:16px;">View All</a>
            </div>
        <?php else: ?>
        <div class="product-grid">
            <?php while($item = mysqli_fetch_assoc($result)): ?>
            <div class="product-card">
                <div class="card-img">
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
                    <form method="post" action="cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                        <input type="hidden" name="add_to_cart" value="1">
                        <button type="submit" class="btn btn-pink"
                                style="width:100%; padding:9px; font-size:13px;">
                            🛒 Add to Cart
                        </button>
                    </form>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<footer>
    <div class="footer-inner">
        <div class="footer-logo">Pastimes</div>
        <div class="footer-copy">© 2026 Pastimes – Pre-loved Fashion. All rights reserved.</div>
    </div>
</footer>

</body>
</html>