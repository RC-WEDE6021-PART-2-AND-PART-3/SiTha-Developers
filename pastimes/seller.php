<?php
session_start();

// Must be logged in to sell
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "ClothingStore");
if(!$conn){ die("Connection failed: " . mysqli_connect_error()); }

$user_id = $_SESSION['user_id'];
$message = "";
$error   = "";

// Handle sell request submission
if(isset($_POST['sub'])){
    $title     = $_POST['title'];
    $desc      = $_POST['description'];
    $price     = $_POST['price'];
    $size      = $_POST['size'];
    $brand     = $_POST['brand'];
    $category  = $_POST['category'];
    $condition = $_POST['condition_type'];
    $image_url = $_POST['image_url'];

    // Insert into tblClothes with pending status
    $sql = "INSERT INTO tblClothes
                (user_id, title, description, price, size, brand, category, condition_type, image_url)
            VALUES
                ($user_id,'$title','$desc',$price,'$size','$brand','$category','$condition','$image_url')";

    if(mysqli_query($conn, $sql)){
        $message = "✅ Your item has been submitted! The admin will review it shortly.";
    } else {
        $error = "❌ Error: " . mysqli_error($conn);
    }
}

// Fetch this seller's listings
$myItems = mysqli_query($conn, "SELECT * FROM tblClothes WHERE user_id=$user_id ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell Your Item – Pastimes</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .sell-layout {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 24px;
            display: flex;
            gap: 40px;
            align-items: flex-start;
        }

        .sell-form {
            width: 420px;
            flex-shrink: 0;
            background: #fff;
            border-radius: 14px;
            padding: 32px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        }

        .sell-form h2 {
            font-size: 22px;
            font-weight: 700;
            color: #111;
            margin-bottom: 6px;
        }

        .sell-form p {
            font-size: 13px;
            color: #999;
            margin-bottom: 24px;
        }

        .my-listings { flex: 1; }

        .my-listings h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #111;
        }

        .listing-card {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 14px;
            display: flex;
            gap: 16px;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .listing-card img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            flex-shrink: 0;
        }

        .listing-info { flex: 1; }

        .listing-title {
            font-weight: 700;
            font-size: 14px;
            color: #222;
            margin-bottom: 4px;
        }

        .listing-meta {
            font-size: 12px;
            color: #aaa;
            margin-bottom: 6px;
        }

        .listing-price {
            font-size: 16px;
            font-weight: 700;
            color: #d4537e;
        }
    </style>
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

<div class="sell-layout">

    <!-- LEFT: Sell form -->
    <div class="sell-form">
        <h2>Sell Your Item</h2>
        <p>Turn your pre-loved fashion into cash. It's free to list!</p>

        <?php if($message != ""): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if($error != ""): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="post" action="seller.php">

            <!-- Title -->
            <div class="form-group">
                <label>Item Title</label>
                <input type="text" name="title"
                       placeholder="e.g. Vintage Floral Maxi Dress"
                       value="<?php echo isset($_POST['title']) ? $_POST['title'] : ''; ?>"
                       required>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label>Description</label>
                <input type="text" name="description"
                       placeholder="Describe your item"
                       value="<?php echo isset($_POST['description']) ? $_POST['description'] : ''; ?>">
            </div>

            <!-- Brand -->
            <div class="form-group">
                <label>Brand</label>
                <input type="text" name="brand"
                       placeholder="e.g. Zara, H&M, Nike"
                       value="<?php echo isset($_POST['brand']) ? $_POST['brand'] : ''; ?>"
                       required>
            </div>

            <!-- Price -->
            <div class="form-group">
                <label>Price (R)</label>
                <input type="number" name="price"
                       placeholder="e.g. 350"
                       step="0.01" min="1"
                       value="<?php echo isset($_POST['price']) ? $_POST['price'] : ''; ?>"
                       required>
            </div>

            <!-- Category -->
            <div class="form-group">
                <label>Category</label>
                <select name="category">
                    <?php foreach(["Dresses","Tops","Jeans","Jackets","Shoes","Accessories"] as $c): ?>
                    <option value="<?php echo $c; ?>"
                        <?php echo (isset($_POST['category']) && $_POST['category']==$c) ? 'selected' : ''; ?>>
                        <?php echo $c; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Size -->
            <div class="form-group">
                <label>Size</label>
                <select name="size">
                    <?php foreach(["XS","S","M","L","XL","XXL","26","28","30","32","34","36","38","40"] as $s): ?>
                    <option value="<?php echo $s; ?>"
                        <?php echo (isset($_POST['size']) && $_POST['size']==$s) ? 'selected' : ''; ?>>
                        <?php echo $s; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Condition -->
            <div class="form-group">
                <label>Condition</label>
                <select name="condition_type">
                    <?php foreach(["Like New","Good","Fair"] as $cond): ?>
                    <option value="<?php echo $cond; ?>"
                        <?php echo (isset($_POST['condition_type']) && $_POST['condition_type']==$cond) ? 'selected' : ''; ?>>
                        <?php echo $cond; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Image path -->
            <div class="form-group">
                <label>Image filename (e.g. images/item1.jpeg)</label>
                <input type="text" name="image_url"
                       placeholder="images/yourimage.jpeg"
                       value="<?php echo isset($_POST['image_url']) ? $_POST['image_url'] : ''; ?>">
            </div>

            <input type="submit" name="sub" value="Submit Listing"
                   class="btn btn-pink"
                   style="width:100%; padding:13px; font-size:15px;">

        </form>
    </div>

    <!-- RIGHT: My listings -->
    <div class="my-listings">
        <h3>My Listings</h3>

        <?php if(mysqli_num_rows($myItems) == 0): ?>
            <div style="text-align:center; padding:40px; color:#aaa;">
                <div style="font-size:48px; margin-bottom:16px;">👗</div>
                <p>You haven't listed anything yet.</p>
            </div>
        <?php else: ?>
            <?php while($item = mysqli_fetch_assoc($myItems)): ?>
            <div class="listing-card">
                <img src="<?php echo $item['image_url']; ?>"
                     alt="<?php echo $item['title']; ?>"
                     onerror="this.src='https://placehold.co/70x70/fce4ee/d4537e?text=Item'">
                <div class="listing-info">
                    <div class="listing-title"><?php echo $item['title']; ?></div>
                    <div class="listing-meta">
                        <?php echo $item['brand']; ?> •
                        <?php echo $item['category']; ?> •
                        Size <?php echo $item['size']; ?> •
                        <?php echo $item['condition_type']; ?>
                    </div>
                    <div class="listing-price">R<?php echo number_format($item['price'], 2); ?></div>
                </div>
                <span class="badge badge-verified">Active</span>
            </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<footer>
    <div class="footer-inner">
        <div class="footer-logo">Pastimes</div>
        <div class="footer-copy">© 2026 Pastimes – Pre-loved Fashion.</div>
    </div>
</footer>

</body>
</html>