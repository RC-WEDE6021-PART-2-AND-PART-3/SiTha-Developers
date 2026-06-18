<?php
session_start();

// If not logged in, send to login page
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "ClothingStore");

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch full user data using associative array - as required by assignment
$uid    = $_SESSION['user_id'];
$sql    = "SELECT * FROM tblUser WHERE user_id=$uid";
$result = mysqli_query($conn, $sql);
$user   = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile – Pastimes</title>
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

<!-- Assignment requirement: display string identifying the user -->
<div style="background:#fff0f5; border-bottom:2px solid #d4537e; padding:14px 40px;">
    <p style="color:#d4537e; font-weight:bold; font-size:16px; margin:0;">
        ✅ User <?php echo $user['first_name'] . " " . $user['last_name']; ?> is logged in
    </p>
</div>

<div style="max-width:700px; margin:40px auto; padding:0 20px;">
    <h2 style="color:#d4537e; margin-bottom:24px;">My Profile</h2>

    <!-- Associative array display - assignment requirement -->
    <table class="data-table">
        <tr>
            <th>Field</th>
            <th>Value</th>
        </tr>
        <?php foreach($user as $key => $value): ?>
            <?php if($key == 'password') continue; // don't show password ?>
            <tr>
                <td><strong><?php echo ucfirst(str_replace('_', ' ', $key)); ?></strong></td>
                <td><?php echo $value; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div style="margin-top:30px; display:flex; gap:14px;">
        <a href="index.php" class="btn btn-pink">Browse Shop</a>
        <a href="logout.php" class="btn btn-outline">Sign Out</a>
    </div>
</div>

<footer>
    <p style="text-align:center; padding:30px; color:#aaa; font-size:13px;">
        © 2026 Pastimes
    </p>
</footer>
</body>
</html>