<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pastimes – Pre-loved Fashion</title>
    <link rel="stylesheet" href="/pastimes/assets/style.css">
</head>
<body>

<nav class="navbar">
    <a href="/pastimes/index.php" class="logo">Pastimes</a>
    <div>
        <a href="/pastimes/index.php">Shop</a>
        <a href="#">Dresses</a>
        <a href="#">Tops</a>
        <a href="#">Jeans</a>
    </div>
    <div>
        <?php if(isset($_SESSION['username'])): ?>
            <span style="margin-right:14px; font-size:14px; color:#d4537e;">
                <?php echo "Hi, " . $_SESSION['first_name']; ?>
            </span>
            <?php if($_SESSION['role'] == 'admin'): ?>
                <a href="/pastimes/admin/dashboard.php" class="btn btn-outline" style="margin-right:8px;">Admin Panel</a>
            <?php endif; ?>
            <a href="/pastimes/logout.php" class="btn btn-pink">Sign Out</a>
        <?php else: ?>
            <a href="/pastimes/login.php" class="btn btn-outline" style="margin-right:8px;">Login</a>
            <a href="/pastimes/register.php" class="btn btn-pink">Register</a>
        <?php endif; ?>
    </div>
</nav>