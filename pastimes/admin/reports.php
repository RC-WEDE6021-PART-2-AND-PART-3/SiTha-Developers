<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "ClothingStore");
if(!$conn){ die("Connection failed: " . mysqli_connect_error()); }

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

// Fetch all orders with user details
$orders = mysqli_query($conn, "
    SELECT tblAorder.*,
           CONCAT(tblUser.first_name,' ',tblUser.last_name) AS customer_name,
           tblUser.email
    FROM tblAorder
    JOIN tblUser ON tblAorder.user_id = tblUser.user_id
    ORDER BY tblAorder.order_date DESC
");

// Get total revenue
$revenue = mysqli_query($conn, "SELECT SUM(total_amount) as total FROM tblAorder WHERE payment_status='completed'");
$rev     = mysqli_fetch_assoc($revenue);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports – Pastimes Admin</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .stats-row {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            flex: 1;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            text-align: center;
        }
        .stat-card strong {
            display: block;
            font-size: 32px;
            font-weight: 700;
            color: #d4537e;
            margin-bottom: 6px;
        }
        .stat-card span {
            font-size: 13px;
            color: #999;
        }
    </style>
</head>
<body>

<div class="announcement-bar">Pastimes Admin Panel</div>

<nav class="navbar">
    <a href="../index.php" class="logo">Pastimes</a>
    <div class="nav-links">
        <a href="dashboard.php">Users</a>
        <a href="clothes.php">Clothes</a>
        <a href="messages.php">Messages</a>
        <a href="reports.php" style="color:#d4537e; font-weight:700;">Reports</a>
    </div>
    <div class="nav-right">
        <span style="font-size:14px; color:#d4537e;">Admin: <?php echo $_SESSION['username']; ?></span>
        <a href="../logout.php" class="btn btn-pink">Sign Out</a>
    </div>
</nav>

<div class="admin-container">
    <h2>Purchase Reports</h2>
    <p style="color:#555; margin-bottom:24px;">Overview of all customer orders.</p>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-card">
            <strong>R<?php echo number_format($rev['total'] ?? 0, 2); ?></strong>
            <span>Total Revenue</span>
        </div>
        <div class="stat-card">
            <strong><?php echo mysqli_num_rows($orders); ?></strong>
            <span>Total Orders</span>
        </div>
    </div>

    <!-- Orders table -->
    <table class="data-table">
        <tr>
            <th>Reference</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Total</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
        <?php
        mysqli_data_seek($orders, 0);
        while($order = mysqli_fetch_assoc($orders)):
            $ref = "PAT-" . date('Ymd', strtotime($order['order_date'])) . "-" . str_pad($order['order_id'], 4, '0', STR_PAD_LEFT);
        ?>
        <tr>
            <td style="color:#d4537e; font-weight:700;"><?php echo $ref; ?></td>
            <td><?php echo $order['customer_name']; ?></td>
            <td><?php echo $order['email']; ?></td>
            <td style="font-weight:700;">R<?php echo number_format($order['total_amount'], 2); ?></td>
            <td><?php echo date('d M Y', strtotime($order['order_date'])); ?></td>
            <td>
                <span class="badge badge-verified"><?php echo $order['payment_status']; ?></span>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<footer>
    <div class="footer-inner">
        <div class="footer-logo">Pastimes</div>
        <div class="footer-copy">© 2026 Pastimes</div>
    </div>
</footer>

</body>
</html>