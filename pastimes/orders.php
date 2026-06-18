<?php
session_start();

// Must be logged in
if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "ClothingStore");
if(!$conn){ die("Connection failed: " . mysqli_connect_error()); }

$user_id = $_SESSION['user_id'];

// Fetch all orders for this user
$orders = mysqli_query($conn, "SELECT * FROM tblAorder WHERE user_id=$user_id ORDER BY order_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders – Pastimes</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .orders-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 24px;
        }
        .order-card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border-left: 4px solid #d4537e;
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        .order-ref {
            font-size: 16px;
            font-weight: 700;
            color: #d4537e;
        }
        .order-date {
            font-size: 13px;
            color: #aaa;
        }
        .order-items-list {
            margin-bottom: 16px;
        }
        .order-item-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: #444;
            padding: 8px 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .order-total {
            display: flex;
            justify-content: space-between;
            font-size: 16px;
            font-weight: 700;
            color: #111;
            margin-top: 12px;
        }
        .empty-orders {
            text-align: center;
            padding: 80px 20px;
            color: #aaa;
        }
        .empty-orders div { font-size: 60px; margin-bottom: 16px; }
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

<div class="orders-container">
    <h2 style="font-size:26px; font-weight:700; margin-bottom:6px;">My Orders</h2>
    <p style="color:#999; margin-bottom:30px;">Your purchase history</p>

    <?php if(mysqli_num_rows($orders) == 0): ?>
    <div class="empty-orders">
        <div>📦</div>
        <h3>No orders yet</h3>
        <p>You haven't placed any orders yet</p>
        <a href="shop.php" class="btn btn-pink" style="margin-top:20px;">Start Shopping</a>
    </div>
    <?php else: ?>
        <?php while($order = mysqli_fetch_assoc($orders)):
            // Generate ref number
            $ref = "PAT-" . date('Ymd', strtotime($order['order_date'])) . "-" . str_pad($order['order_id'], 4, '0', STR_PAD_LEFT);

            // Get order items
            $items = mysqli_query($conn, "SELECT tblOrderItems.*, tblClothes.title, tblClothes.price, tblClothes.brand
                                         FROM tblOrderItems
                                         JOIN tblClothes ON tblOrderItems.product_id = tblClothes.product_id
                                         WHERE tblOrderItems.order_id = ".$order['order_id']);
        ?>
        <div class="order-card">
            <div class="order-header">
                <div>
                    <div class="order-ref">Order #<?php echo $ref; ?></div>
                    <div class="order-date"><?php echo date('d M Y, H:i', strtotime($order['order_date'])); ?></div>
                </div>
                <span class="badge badge-verified"><?php echo $order['payment_status']; ?></span>
            </div>

            <div class="order-items-list">
                <?php while($item = mysqli_fetch_assoc($items)): ?>
                <div class="order-item-row">
                    <span><?php echo $item['title']; ?> (<?php echo $item['brand']; ?>)</span>
                    <span>Qty: <?php echo $item['quantity']; ?> × R<?php echo number_format($item['price'], 2); ?></span>
                </div>
                <?php endwhile; ?>
            </div>

            <div class="order-total">
                <span>Total</span>
                <span style="color:#d4537e;">R<?php echo number_format($order['total_amount'], 2); ?></span>
            </div>
        </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<footer>
    <div class="footer-inner">
        <div class="footer-logo">Pastimes</div>
        <div class="footer-copy">© 2026 Pastimes – Pre-loved Fashion.</div>
    </div>
</footer>

</body>
</html>