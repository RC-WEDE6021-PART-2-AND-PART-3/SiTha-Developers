<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "ClothingStore");
if(!$conn){ die("Connection failed: " . mysqli_connect_error()); }

if(!isset($_SESSION['username'])){
    echo "Not logged in - redirecting...";
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Add item to cart
if(isset($_POST['add_to_cart'])){
    $product_id = $_POST['product_id'];
    $check = "SELECT * FROM tblCart WHERE user_id=$user_id AND product_id=$product_id";
    $exists = mysqli_query($conn, $check);

    if(mysqli_num_rows($exists) > 0){
        $sql = "UPDATE tblCart SET quantity = quantity + 1 WHERE user_id=$user_id AND product_id=$product_id";
        mysqli_query($conn, $sql);
    } else {
        $sql = "INSERT INTO tblCart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)";
        mysqli_query($conn, $sql);
    }
    $message = "✅ Item added to cart!";
}

// Update quantity
if(isset($_POST['update_cart'])){
    $cart_id  = $_POST['cart_id'];
    $quantity = $_POST['quantity'];
    if($quantity <= 0){
        $sql = "DELETE FROM tblCart WHERE cart_id=$cart_id AND user_id=$user_id";
    } else {
        $sql = "UPDATE tblCart SET quantity=$quantity WHERE cart_id=$cart_id AND user_id=$user_id";
    }
    mysqli_query($conn, $sql);
    $message = "✅ Cart updated!";
}

// Remove item
if(isset($_GET['remove'])){
    $cart_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM tblCart WHERE cart_id=$cart_id AND user_id=$user_id");
    header("Location: cart.php");
    exit();
}

// Checkout
if(isset($_POST['checkout'])){
    $totalQuery  = "SELECT SUM(tblClothes.price * tblCart.quantity) as total
                    FROM tblCart
                    JOIN tblClothes ON tblCart.product_id = tblClothes.product_id
                    WHERE tblCart.user_id = $user_id";
    $totalResult = mysqli_query($conn, $totalQuery);
    $totalRow    = mysqli_fetch_assoc($totalResult);
    $total       = $totalRow['total'];

    // Create order
    $orderSql = "INSERT INTO tblAorder (user_id, total_amount, payment_status)
                 VALUES ($user_id, $total, 'completed')";
    mysqli_query($conn, $orderSql);
    $order_id = mysqli_insert_id($conn);

    // Generate reference number
    $ref_number = "PAT-" . date('Ymd') . "-" . str_pad($order_id, 4, '0', STR_PAD_LEFT);

    // Move cart items to order items and decrement stock
    $cartItems2 = mysqli_query($conn, "SELECT * FROM tblCart WHERE user_id=$user_id");
    while($row2 = mysqli_fetch_assoc($cartItems2)){
        // Add to order items
        mysqli_query($conn, "INSERT INTO tblOrderItems (order_id, product_id, quantity)
                             VALUES ($order_id, ".$row2['product_id'].", ".$row2['quantity'].")");
    }

    // Clear cart after checkout
    mysqli_query($conn, "DELETE FROM tblCart WHERE user_id=$user_id");
    $message = "🎉 Order placed! Your reference number is <strong>$ref_number</strong>. Thank you for shopping with Pastimes!";
}

// Fetch cart items
$cartSql    = "SELECT tblCart.cart_id, tblCart.quantity, tblClothes.*
               FROM tblCart
               JOIN tblClothes ON tblCart.product_id = tblClothes.product_id
               WHERE tblCart.user_id = $user_id";
$cartResult = mysqli_query($conn, $cartSql);

$subtotal  = 0;
$cartItems = [];
while($row = mysqli_fetch_assoc($cartResult)){
    $subtotal    += $row['price'] * $row['quantity'];
    $cartItems[]  = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart – Pastimes</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .cart-layout {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 24px;
            display: flex;
            gap: 30px;
            align-items: flex-start;
        }
        .cart-items { flex: 1; }
        .cart-summary {
            width: 320px;
            flex-shrink: 0;
            background: #fff;
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        }
        .cart-item {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            display: flex;
            gap: 20px;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .cart-item img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 10px;
            flex-shrink: 0;
        }
        .cart-item-info { flex: 1; }
        .cart-item-title { font-weight:700; font-size:15px; color:#222; margin-bottom:4px; }
        .cart-item-brand { font-size:12px; color:#aaa; margin-bottom:8px; }
        .cart-item-price { font-size:18px; font-weight:700; color:#d4537e; }
        .qty-controls { display:flex; align-items:center; gap:10px; margin-top:10px; }
        .qty-input {
            width:40px; text-align:center;
            border:1px solid #eee; border-radius:6px;
            padding:4px; font-size:14px;
        }
        .summary-row {
            display:flex; justify-content:space-between;
            margin-bottom:14px; font-size:14px; color:#555;
        }
        .summary-total {
            display:flex; justify-content:space-between;
            font-size:18px; font-weight:700; color:#111;
            border-top:2px solid #f0f0f0;
            padding-top:14px; margin-top:14px;
        }
        .empty-cart { text-align:center; padding:60px 20px; color:#aaa; }
        .empty-cart div { font-size:60px; margin-bottom:16px; }
        .empty-cart h3 { font-size:20px; color:#333; margin-bottom:10px; }
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

<div class="cart-layout">
    <div class="cart-items">
        <h2 style="font-size:24px; font-weight:700; margin-bottom:24px;">Shopping Cart</h2>

        <?php if($message != ""): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if(count($cartItems) == 0): ?>
        <div class="empty-cart">
            <div>🛍️</div>
            <h3>Your cart is empty</h3>
            <p>Looks like you haven't added anything yet</p>
            <a href="shop.php" class="btn btn-pink" style="margin-top:20px;">Continue Shopping</a>
        </div>
        <?php else: ?>
            <?php foreach($cartItems as $item): ?>
            <div class="cart-item">
                <img src="<?php echo $item['image_url']; ?>"
                     alt="<?php echo $item['title']; ?>"
                     onerror="this.src='https://placehold.co/90x90/fce4ee/d4537e?text=Item'">
                <div class="cart-item-info">
                    <div class="cart-item-title"><?php echo $item['title']; ?></div>
                    <div class="cart-item-brand"><?php echo $item['brand']; ?> • Size <?php echo $item['size']; ?></div>
                    <div class="cart-item-price">R<?php echo number_format($item['price'], 2); ?></div>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                        <div class="qty-controls">
                            <span style="font-size:13px; color:#777;">Qty:</span>
                            <input type="number" name="quantity"
                                   value="<?php echo $item['quantity']; ?>"
                                   min="0" max="10" class="qty-input">
                            <input type="submit" name="update_cart" value="Update"
                                   class="btn btn-outline" style="padding:5px 12px; font-size:12px;">
                        </div>
                    </form>
                </div>
                <div style="text-align:right;">
                    <div style="font-weight:700; color:#d4537e; font-size:16px; margin-bottom:10px;">
                        R<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                    </div>
                    <a href="cart.php?remove=<?php echo $item['cart_id']; ?>"
                       onclick="return confirm('Remove this item?')"
                       style="font-size:12px; color:#e00; text-decoration:none;">🗑 Remove</a>
                </div>
            </div>
            <?php endforeach; ?>
            <a href="shop.php" class="btn btn-outline" style="margin-top:10px;">← Continue Shopping</a>
        <?php endif; ?>
    </div>

    <?php if(count($cartItems) > 0): ?>
    <div class="cart-summary">
        <h3 style="font-size:18px; font-weight:700; margin-bottom:20px;">Order Summary</h3>
        <div class="summary-row">
            <span>Subtotal (<?php echo count($cartItems); ?> items)</span>
            <span>R<?php echo number_format($subtotal, 2); ?></span>
        </div>
        <div class="summary-row">
            <span>Shipping</span>
            <span style="color:#27ae60;">
                <?php echo $subtotal >= 500 ? 'FREE' : 'R50.00'; ?>
            </span>
        </div>
        <div class="summary-total">
            <span>Total</span>
            <span>R<?php echo number_format($subtotal >= 500 ? $subtotal : $subtotal + 50, 2); ?></span>
        </div>
        <form method="post">
            <input type="submit" name="checkout" value="Proceed to Checkout →"
                   class="btn btn-pink"
                   style="width:100%; padding:14px; font-size:15px; margin-top:10px;"
                   onclick="return confirm('Confirm your order?')">
        </form>
        <p style="text-align:center; font-size:12px; color:#aaa; margin-top:14px;">
            🔒 Secure checkout with buyer protection
        </p>
    </div>
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