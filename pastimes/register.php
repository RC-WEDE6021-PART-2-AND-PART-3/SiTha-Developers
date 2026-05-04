<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "ClothingStore");

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

$error = "";
$success = "";

if(isset($_POST['sub'])){
    $first    = $_POST['first_name'];
    $last     = $_POST['last_name'];
    $email    = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    if($password != $confirm){
        $error = "Passwords do not match.";
    } else {
        $hashed = md5($password);

        $check = "SELECT user_id FROM tblUser WHERE username='$username' OR email='$email'";
        $result = mysqli_query($conn, $check);

        if(mysqli_num_rows($result) > 0){
            $error = "Username or email already exists. Please try another.";
        } else {
            $sql = "INSERT INTO tblUser (first_name, last_name, email, username, password, role, verification_status)
                    VALUES ('$first','$last','$email','$username','$hashed','buyer','pending')";

            if(mysqli_query($conn, $sql)){
                $success = "Registration successful! Please wait for admin to verify your account before logging in.";
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – Pastimes</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<nav class="navbar">
    <a href="index.php" class="logo">Pastimes</a>
    <div>
        <a href="login.php" class="btn btn-outline">Login</a>
    </div>
</nav>

<div class="form-container">
    <h2>Create Account</h2>

    <?php if($error != ""): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if($success != ""): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="post" action="register.php">

        <div class="form-group">
            <label>First Name</label>
            <!-- Sticky form - keeps value if there's an error -->
            <input type="text" name="first_name"
                   value="<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : ''; ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name"
                   value="<?php echo isset($_POST['last_name']) ? $_POST['last_name'] : ''; ?>"
                   required>
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email"
                   value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>"
                   required>
        </div>

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

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm" required>
        </div>

        <input type="submit" name="sub" value="Register"
               class="btn btn-pink"
               style="width:100%; padding:13px; font-size:16px;">
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

<footer>
    <p style="text-align:center; padding:30px; color:#aaa; font-size:13px;">
        © 2026 Pastimes
    </p>
</footer>
</body>
</html>