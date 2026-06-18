<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "ClothingStore");
if(!$conn){ die("Connection failed: " . mysqli_connect_error()); }

// Only admin can access
if(!isset($_SESSION['username']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
} 

$admin_id = $_SESSION['user_id'];
$message  = "";

// Send a message
if(isset($_POST['send'])){
    $receiver_id  = $_POST['receiver_id'];
    $message_text = $_POST['message_text'];

    $sql = "INSERT INTO tblMessages (sender_id, receiver_id, message_text)
            VALUES ($admin_id, $receiver_id, '$message_text')";

    if(mysqli_query($conn, $sql)){
        $message = "✅ Message sent successfully!";
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}

// Delete a message
if(isset($_GET['delete'])){
    $mid = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM tblMessages WHERE message_id=$mid");
    header("Location: messages.php");
    exit();
}

// Fetch all users to message
$users = mysqli_query($conn, "SELECT user_id, first_name, last_name, role FROM tblUser WHERE user_id != $admin_id ORDER BY role");

// Fetch all messages with sender and receiver names
$msgs = mysqli_query($conn, "
    SELECT tblMessages.*,
           CONCAT(s.first_name,' ',s.last_name) AS sender_name,
           CONCAT(r.first_name,' ',r.last_name) AS receiver_name
    FROM tblMessages
    JOIN tblUser s ON tblMessages.sender_id   = s.user_id
    JOIN tblUser r ON tblMessages.receiver_id = r.user_id
    ORDER BY tblMessages.timestamp DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages – Pastimes Admin</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .messages-layout {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 24px;
            display: flex;
            gap: 30px;
            align-items: flex-start;
        }

        .compose-box {
            width: 360px;
            flex-shrink: 0;
            background: #fff;
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        }

        .compose-box h3 {
            font-size: 18px;
            font-weight: 700;
            color: #111;
            margin-bottom: 20px;
        }

        .compose-box textarea {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid #eee;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            min-height: 120px;
            background: #fafafa;
        }

        .compose-box textarea:focus {
            outline: none;
            border-color: #d4537e;
            background: #fff;
        }

        .messages-list { flex: 1; }

        .messages-list h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #111;
        }

        .msg-card {
            background: #fff;
            border-radius: 12px;
            padding: 18px 20px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-left: 4px solid #d4537e;
        }

        .msg-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .msg-names {
            font-size: 13px;
            font-weight: 700;
            color: #222;
        }

        .msg-names span {
            color: #d4537e;
        }

        .msg-time {
            font-size: 11px;
            color: #bbb;
        }

        .msg-text {
            font-size: 14px;
            color: #444;
            line-height: 1.6;
        }

        .msg-delete {
            font-size: 12px;
            color: #e00;
            text-decoration: none;
            margin-top: 8px;
            display: inline-block;
        }

        .empty-messages {
            text-align: center;
            padding: 60px 20px;
            color: #aaa;
        }

        .empty-messages div { font-size: 48px; margin-bottom: 16px; }
    </style>
</head>
<body>

<div class="announcement-bar">Pastimes Admin Panel</div>

<nav class="navbar">
    <a href="../index.php" class="logo">Pastimes</a>
    <div class="nav-links">
        <a href="dashboard.php">Users</a>
        <a href="clothes.php">Clothes</a>
        <a href="reports.php">Reports</a>
        <a href="messages.php" style="color:#d4537e; font-weight:700;">Messages</a>
    </div>
    <div class="nav-right">
        <span style="font-size:14px; color:#d4537e;">Admin: <?php echo $_SESSION['username']; ?></span>
        <a href="../logout.php" class="btn btn-pink">Sign Out</a>
    </div>
</nav>

<div class="messages-layout">

    <!-- LEFT: Compose message -->
    <div class="compose-box">
        <h3>Send Message</h3>

        <?php if($message != ""): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="post">
            <!-- Select recipient -->
            <div class="form-group">
                <label>Send To</label>
                <select name="receiver_id" required>
                    <option value="">-- Select user --</option>
                    <?php
                    // Reset the users query
                    mysqli_data_seek($users, 0);
                    while($u = mysqli_fetch_assoc($users)):
                    ?>
                    <option value="<?php echo $u['user_id']; ?>">
                        <?php echo $u['first_name']." ".$u['last_name']." (".$u['role'].")"; ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Message text -->
            <div class="form-group">
                <label>Message</label>
                <textarea name="message_text"
                          placeholder="Type your message here..."
                          required></textarea>
            </div>

            <input type="submit" name="send" value="Send Message"
                   class="btn btn-pink"
                   style="width:100%; padding:12px; font-size:14px;">
        </form>
    </div>

    <!-- RIGHT: All messages -->
    <div class="messages-list">
        <h3>All Messages</h3>

        <?php if(mysqli_num_rows($msgs) == 0): ?>
            <div class="empty-messages">
                <div>💬</div>
                <p>No messages yet.</p>
            </div>
        <?php else: ?>
            <?php while($msg = mysqli_fetch_assoc($msgs)): ?>
            <div class="msg-card">
                <div class="msg-header">
                    <div class="msg-names">
                        <span><?php echo $msg['sender_name']; ?></span>
                        → <?php echo $msg['receiver_name']; ?>
                    </div>
                    <div class="msg-time">
                        <?php echo date('d M Y, H:i', strtotime($msg['timestamp'])); ?>
                    </div>
                </div>
                <div class="msg-text"><?php echo $msg['message_text']; ?></div>
                <a href="messages.php?delete=<?php echo $msg['message_id']; ?>"
                   onclick="return confirm('Delete this message?')"
                   class="msg-delete">🗑 Delete</a>
            </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<footer>
    <div class="footer-inner">
        <div class="footer-logo">Pastimes</div>
        <div class="footer-copy">© 2026 Pastimes</div>
    </div>
</footer>

</body>
</html>