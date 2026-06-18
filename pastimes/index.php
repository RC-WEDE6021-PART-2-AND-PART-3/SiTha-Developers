<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pastimes – Pre-loved Fashion</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<!-- Announcement bar -->
<div class="announcement-bar">Free delivery on orders over R500 🚚</div>

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

<!-- Hero section -->
<div class="hero">
    <div class="hero-text">
        <p style="color:#d4537e; font-size:13px; font-weight:600; margin-bottom:12px;">
            ✨ New Season Arrivals
        </p>
        <h1>Pre-loved Fashion,<br><span>Newly Loved</span></h1>
        <p>Discover unique second-hand pieces from top brands. Buy sustainable, sell your wardrobe, and join South Africa's thrift community.</p>
        <div class="hero-buttons">
            <a href="shop.php" class="btn btn-pink" style="padding:13px 28px; font-size:15px;">Shop Now →</a>
            <a href="register.php" class="btn btn-outline" style="padding:13px 28px; font-size:15px;">Start Selling</a>
        </div>
        <div class="hero-stats">
            <div><strong>50K+</strong><span>Items Listed</span></div>
            <div><strong>10K+</strong><span>Happy Buyers</span></div>
            <div><strong>5K+</strong><span>Sellers</span></div>
        </div>
    </div>

    <!-- Hero image - save a nice fashion photo as images/hero.jpg -->
<div class="hero-image">
    <img src="images/hero.jpeg"
         alt="Fashion"
         style="width:100%; height:100%; object-fit:contain; object-position:center; background:#fce4ee;">
</div>
</div>

<!-- Features bar -->
<div class="features-bar">
    <div class="feature">
        <div class="feature-icon">🛡️</div>
        <div class="feature-text">
            <strong>Buyer Protection</strong>
            <span>Every purchase is protected</span>
        </div>
    </div>
    <div class="feature">
        <div class="feature-icon">📦</div>
        <div class="feature-text">
            <strong>Free Delivery</strong>
            <span>Orders over R500 nationwide</span>
        </div>
    </div>
    <div class="feature">
        <div class="feature-icon">♻️</div>
        <div class="feature-text">
            <strong>Sustainable Fashion</strong>
            <span>Give clothes a second life</span>
        </div>
    </div>
</div>

<!-- Categories section -->
<div class="section" style="background:#fff;">
    <div class="section-header">
        <div>
            <h2>Shop by Category</h2>
            <p>Find your perfect style</p>
        </div>
        <a href="shop.php">View All →</a>
    </div>

    <div class="category-grid">
        <?php
        // Category data with Unsplash images
        $categories = [
            ["Dresses",     "234", "https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=400&q=80"],
            ["Tops",        "567", "https://images.unsplash.com/photo-1562157873-818bc0726f68?w=400&q=80"],
            ["Jeans",       "189", "https://images.unsplash.com/photo-1542272604-787c3835535d?w=400&q=80"],
            ["Jackets",     "145", "https://images.unsplash.com/photo-1551028719-00167b16eac5?w=400&q=80"],
            ["Shoes",       "312", "https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&q=80"],
            ["Accessories", "423", "https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=400&q=80"],
        ];

        foreach($categories as $cat):
        ?>
        <a href="shop.php" class="category-card" style="text-decoration:none;">
            <img src="<?php echo $cat[2]; ?>" alt="<?php echo $cat[0]; ?>">
            <div class="cat-label">
                <strong><?php echo $cat[0]; ?></strong>
                <span><?php echo $cat[1]; ?> items</span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- Footer -->
<footer>
    <div class="footer-inner">
        <div class="footer-logo">Pastimes</div>
        <div class="footer-copy">© 2026 Pastimes – Pre-loved Fashion. All rights reserved.</div>
    </div>
</footer>

</body>
</html>