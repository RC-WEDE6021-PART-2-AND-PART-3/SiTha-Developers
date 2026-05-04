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

<nav class="navbar">
    <a href="index.php" class="logo">Pastimes</a>
    <div>
        <a href="index.php">Home</a>
    </div>
    <div>
        <a href="logout.php" class="btn btn-pink">Sign Out</a>
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