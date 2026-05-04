<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "ClothingStore");

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

$error = "";

if(isset($_POST['sub'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed   = md5($password);

    //$sql    = "SELECT * FROM tblUser WHERE username='$username' AND password='$hashed'";
    $sql = "SELECT * FROM tblUser WHERE (username='$username' OR email='$username') AND password='$hashed'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);

        if($row['verification_status'] == 'pending'){
            $error = "Your account is pending admin verification. Please wait.";
        } else {
            $_SESSION['user_id']    = $row['user_id'];
            $_SESSION['username']   = $row['username'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name']  = $row['last_name'];
            $_SESSION['email']      = $row['email'];
            $_SESSION['role']       = $row['role'];

            if($row['role'] == 'admin'){
                header("Location: admin/dashboard.php");
                exit();
            } else {
                header("Location: profile.php");
                exit();
            }
        }
    } else {
        $error = "Incorrect username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Pastimes</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<nav class="navbar">
    <a href="index.php" class="logo">Pastimes</a>
    <div>
        <a href="register.php" class="btn btn-outline">Register</a>
    </div>
</nav>

<div class="form-container">
    <h2>Welcome Back</h2>

    <?php if($error != ""): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Sticky form keeps username if password is wrong -->
    <form method="post" action="login.php">

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username"
                   value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <input type="submit" name="sub" value="Login"
               class="btn btn-pink"
               style="width:100%; padding:13px; font-size:16px;">
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
    <p style="margin-top:10px;">
        <a href="admin/dashboard.php" style="color:#999; font-size:13px;">Admin Login →</a>
    </p>
</div>

<footer>
    <p style="text-align:center; padding:30px; color:#aaa; font-size:13px;">
        © 2026 Pastimes
    </p>
</footer>
</body>
</html>