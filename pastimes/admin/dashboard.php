<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "ClothingStore");

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

// Redirect if not admin
if(!isset($_SESSION['username']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$message = "";

// Add new user
// Add new user
if(isset($_POST['add_user'])){
    $first    = $_POST['first_name'];
    $last     = $_POST['last_name'];
    $email    = $_POST['email'];
    $uname    = $_POST['username'];
    $pass     = md5($_POST['password']);
    $role     = $_POST['role'];
    $status   = $_POST['status'];

    // Check if username or email already exists
    $check = "SELECT user_id FROM tblUser WHERE username='$uname' OR email='$email'";
    $checkResult = mysqli_query($conn, $check);

    if(mysqli_num_rows($checkResult) > 0){
        $message = "❌ Username or email already exists. Please use different details.";
    } else {
        $sql = "INSERT INTO tblUser (first_name, last_name, email, username, password, role, verification_status)
                VALUES ('$first','$last','$email','$uname','$pass','$role','$status')";
        if(mysqli_query($conn, $sql)){
            $message = "✅ User added successfully!";
        } else {
            $message = "❌ Error: " . mysqli_error($conn);
        }
    }

}

// Update user
if(isset($_POST['update_user'])){
    $uid    = $_POST['user_id'];
    $first  = $_POST['first_name'];
    $last   = $_POST['last_name'];
    $email  = $_POST['email'];
    $role   = $_POST['role'];
    $status = $_POST['status'];

    $sql = "UPDATE tblUser SET first_name='$first', last_name='$last', email='$email', role='$role', verification_status='$status' WHERE user_id=" . $uid;
    if(mysqli_query($conn, $sql)){
        $message = " User updated successfully!";
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}

// Verify user
if(isset($_POST['verify'])){
    $uid = $_POST['user_id'];
    $sql = "UPDATE tblUser SET verification_status='verified' WHERE user_id=" . $uid;
    if(mysqli_query($conn, $sql)){
        $message = " User verified!";
    }
}

// Delete user
if(isset($_POST['delete'])){
    $uid = $_POST['user_id'];
    $sql = "DELETE FROM tblUser WHERE user_id=" . $uid;
    if(mysqli_query($conn, $sql)){
        $message = " User deleted.";
    }
}

// Fetch all users
$result = mysqli_query($conn, "SELECT * FROM tblUser ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard – Pastimes</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .modal-bg {
            display:none;
            position:fixed;
            top:0; left:0;
            width:100%; height:100%;
            background:rgba(0,0,0,0.5);
            z-index:999;
            justify-content:center;
            align-items:center;
        }
        .modal-bg.active { display:flex; }
        .modal {
            background:#fff;
            padding:36px;
            border-radius:12px;
            width:480px;
            max-width:95%;
        }
        .modal h3 { color:#d4537e; margin-bottom:20px; }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="../index.php" class="logo">Pastimes</a>
    <div>
        <span style="font-size:14px; color:#d4537e;">
            Admin: <?php echo $_SESSION['username']; ?>
        </span>
    </div>
    <a href="../logout.php" class="btn btn-pink">Sign Out</a>
</nav>

<div class="admin-container">
    <h2>Admin Dashboard</h2>
    <p style="color:#555; margin-bottom:20px;">Manage and verify user registrations below.</p>

    <?php if($message != ""): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Add User Button -->
    <button onclick="document.getElementById('addModal').classList.add('active')"
            class="btn btn-pink" style="margin-bottom:24px;">
        + Add New User
    </button>

    <!-- Users Table -->
    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['user_id']; ?></td>
            <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['role']; ?></td>
            <td>
                <span class="badge <?php echo $row['verification_status']=='verified' ? 'badge-verified' : 'badge-pending'; ?>">
                    <?php echo $row['verification_status']; ?>
                </span>
            </td>
            <td style="display:flex; gap:6px; flex-wrap:wrap;">

                <!-- Verify button -->
                <?php if($row['verification_status'] == 'pending'): ?>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                    <input type="submit" name="verify" value="Verify"
                           class="btn btn-pink" style="padding:5px 10px; font-size:12px;">
                </form>
                <?php endif; ?>

                <!-- Edit button - opens modal with user data -->
                <button onclick="openEdit(
                    '<?php echo $row['user_id']; ?>',
                    '<?php echo $row['first_name']; ?>',
                    '<?php echo $row['last_name']; ?>',
                    '<?php echo $row['email']; ?>',
                    '<?php echo $row['role']; ?>',
                    '<?php echo $row['verification_status']; ?>'
                )" class="btn btn-outline" style="padding:5px 10px; font-size:12px;">Edit</button>

                <!-- Delete button -->
                <form method="post" style="display:inline;"
                      onsubmit="return confirm('Delete this user?');">
                    <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                    <input type="submit" name="delete" value="Delete"
                           class="btn btn-outline" style="padding:5px 10px; font-size:12px; color:#c00; border-color:#c00;">
                </form>

            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- ADD USER MODAL -->
<div class="modal-bg" id="addModal">
    <div class="modal">
        <h3>Add New User</h3>
        <form method="post">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" required>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role">
                    <option value="buyer">Buyer</option>
                    <option value="seller">Seller</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="verified">Verified</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            <div style="display:flex; gap:12px; margin-top:10px;">
                <input type="submit" name="add_user" value="Add User" class="btn btn-pink">
                <button type="button" onclick="document.getElementById('addModal').classList.remove('active')"
                        class="btn btn-outline">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT USER MODAL -->
<div class="modal-bg" id="editModal">
    <div class="modal">
        <h3>Edit User</h3>
        <form method="post">
            <input type="hidden" name="user_id" id="edit_id">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" id="edit_first" required>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" id="edit_last" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="edit_email" required>
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" id="edit_role">
                    <option value="buyer">Buyer</option>
                    <option value="seller">Seller</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="edit_status">
                    <option value="verified">Verified</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            <div style="display:flex; gap:12px; margin-top:10px;">
                <input type="submit" name="update_user" value="Update User" class="btn btn-pink">
                <button type="button" onclick="document.getElementById('editModal').classList.remove('active')"
                        class="btn btn-outline">Cancel</button>
            </div>
        </form>
    </div>
</div>

<footer>
    <p style="text-align:center; padding:30px; color:#aaa; font-size:13px;">© 2025 Pastimes</p>
</footer>

<script>
// Opens edit modal and fills in the user's current data
function openEdit(id, first, last, email, role, status){
    document.getElementById('edit_id').value     = id;
    document.getElementById('edit_first').value  = first;
    document.getElementById('edit_last').value   = last;
    document.getElementById('edit_email').value  = email;
    document.getElementById('edit_role').value   = role;
    document.getElementById('edit_status').value = status;
    document.getElementById('editModal').classList.add('active');
}
</script>

</body>
</html>